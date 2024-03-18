<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'product_name' => 'required|min:5',
            'product_price' => 'required|numeric|gt:0',
            'is_active' => 'required',
            'image' => [
                'nullable',
                'mimes:jpeg,png,jpg,jfif',
                'max:2048',
                'dimensions:max_width=1024,max_height=1024',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'product_name.required' => 'Vui lòng nhập tên sản phẩm.',
            'product_name.min' => 'Tên sản phẩm phải có ít nhất 5 ký tự.',
            'product_price.required' => 'Vui lòng nhập giá bán.',
            'product_price.numeric' => 'Giá bán chỉ được nhập số.',
            'product_price.gt' => 'Giá bán không được nhỏ hơn 0.',
            'is_active.required' => 'Vui lòng chọn trạng thái.',
            'image.mimes' => 'Chỉ cho phép các định dạng hình ảnh: jpeg, png, jpg, jfif.',
            'image.max' => 'Dung lượng hình ảnh không được vượt quá 2MB.',
            'image.dimensions' => 'Kích thước hình ảnh không được vượt quá 1024x1024 pixels.',
        ];
    }
}
