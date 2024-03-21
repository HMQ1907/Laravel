<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
class CustomersImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function model(array $row)
    {
        return Customer::updateOrCreate(
            ['email' => $row['email']],
            [
                'customer_name' => $row['ten_khach_hang'],
                'tel_num' => $row['tel_num'],
                'address' => $row['address'],
            ],
        );
    }

    public function rules(): array
    {
        return [
            'ten_khach_hang' => 'required|min:5',
            'email' => 'required|email|',
            'tel_num' => 'required|regex:/^\d{10}$/',
            'address' => 'required',
        ];
    }
    /**
     * Returns an array of custom validation messages for the import.
     *
     * @return array
     */
    public function customValidationMessages(): array
    {
        return [
            'ten_khach_hang.required' => 'Vui lòng nhập tên.',
            'ten_khach_hang.min' => 'Tên phải có ít nhất :min ký tự.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'tel_num.required' => 'Vui lòng nhập số điện thoại.',
            'tel_num.regex' => 'Số điện thoại phải có 10 chữ số.',
            'address.required' => 'Vui lòng nhập địa chỉ.',
        ];
    }
}
