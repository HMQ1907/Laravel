<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CustomersImport implements ToModel, WithHeadingRow , WithValidation

{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $existingCustomer = Customer::where('email', $row['email'])->first();

        if (!$existingCustomer) {
            $customer = new Customer([
                'customer_name' => $row['name'],
                'email' => $row['email'],
                'tel_num' => $row['tel_num'],
                'address' => $row['address']
            ]);
            $customer->save();
            return $customer;
        }

        return null;
    }
    public function rules(): array
    {
        return [
            'name' => 'required|min:5',
            'email' => 'required|email|unique:customers,email',
            'tel_num' => 'required|regex:/^\d{10}$/',
            'address' => 'required',
        ];
    }
    public function customValidationMessages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên.',
            'name.min' => 'Tên phải có ít nhất :min ký tự.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại.',
            'tel_num.required' => 'Vui lòng nhập số điện thoại.',
            'tel_num.regex' => 'Số điện thoại phải có 10 chữ số.',
            'address.required' => 'Vui lòng nhập địa chỉ.',
        ];
    }
}
