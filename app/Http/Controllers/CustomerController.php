<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Customer::select('customer_id', 'customer_name', 'email', 'tel_num', 'address', 'is_active');

            if ($request->filled('customer_name')) {
                $query->where('customer_name', 'like', '%' . $request->input('customer_name') . '%');
            }
            if ($request->filled('customer_email')) {
                $query->where('email', 'like', '%' . $request->input('customer_email') . '%');
            }
            if ($request->filled('customer_status')) {
                $query->where('is_active', $request->input('customer_status'));
            }
            if ($request->filled('customer_address')) {
                $query->where('address', 'like', '%' . $request->input('customer_address') . '%');
            }

            $customers = $query->paginate(20);

            if ($request->ajax()) {
                if ($customers->isEmpty()) {
                    return response()->json(['error' => 'Không tìm thấy khách hàng']);
                }
                $view = view('customer.table', ['customers' => $customers])->render();
                $paginationLinks = $customers->links()->toHtml();
                return response()->json(['html' => $view, 'pagination_links' => $paginationLinks]);
            }
            return view('customer.index', ['customers' => $customers]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function create(CustomerRequest $request)
    {
        try {
            $customer = new Customer();
            $customer->customer_name = $request->input('name');
            $customer->email = $request->input('email');
            $customer->tel_num = $request->input('tel_num');
            $customer->address = $request->input('address');
            $customer->is_active = $request->input('is_active');
            $customer->save();
            return response()->json(['message' => 'Thêm thành công khách hàng']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        dd($request->all());
    }
}
