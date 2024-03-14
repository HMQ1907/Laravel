<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Exports\CustomersExport;
use Maatwebsite\Excel\Facades\Excel;
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
        try {
            $cusId = $request->input('customer_id');
            $customer = Customer::find($request->input('customer_id'));
            if (!$customer) {
                return response()->json(['error' => 'Người dùng không tồn tại'], 404);
            }
            $messages = [
                'email.unique' => 'Email này đã được đăng kí',
                'customer_tel.regex' => 'Số điện thoại không hợp lệ',
            ];

            $validator = Validator::make(
                $request->all(),
                [
                    'customer_name' => 'required|string|max:255',
                    'customer_email' => 'required|string|email|max:255|unique:customers,email,' . $cusId . ',customer_id',
                    'customer_address' => 'required|string|max:255',
                    'customer_tel' => 'required|string|regex:/^\d{10}$/',
                ],
                $messages,
            );

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 400);
            }
            $customer->customer_name = $request->input('customer_name');
            $customer->email = $request->input('customer_email');
            $customer->tel_num = $request->input('customer_tel');
            $customer->address = $request->input('customer_address');
            $customer->save();
            return response()->json(['message' => 'Cập nhật thành công khách hàng']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function export()
    {
        return Excel::download(new CustomersExport, 'customers.xlsx');
    }
}