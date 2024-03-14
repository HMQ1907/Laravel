<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:5',
            'email' => 'required|email|unique:customers,email',
            'tel_num' => 'required|regex:/^\d{10}$/',
            'address' => 'required',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên khách hàng',
            'name.min' => 'Tên khách hàng phải có ít nhất 5 ký tự.',
            'email.required' => 'Vui lòng nhập email khách hàng',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã được sử dụng',
            'tel_num.required' => 'Vui lòng nhập số điện thoại khách hàng',
            'tel_num.regex' => 'Số điện thoại không đúng định dạng',
            'address.required' => 'Vui lòng nhập địa chỉ khách hàng',
        ];
    }
}
