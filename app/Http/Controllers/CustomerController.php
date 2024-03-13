<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //
    public function index()
    {
        $customers = Customer::select('customer_id','customer_name','email','tel_num','address','is_active')->paginate(10);
        
        return view('customer.index', ['customers' => $customers]);
    }
}
