<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInventoryRequest extends FormRequest
{
    public function authorize()
    {
        return true; // TV03 sẽ check admin role
    }

    public function rules()
    {
        return [
            'QuantityInStock' => 'required|integer|min:0|max:999999',
        ];
    }

    public function messages()
    {
        return [
            'QuantityInStock.required' => 'Số lượng tồn kho là bắt buộc',
            'QuantityInStock.integer' => 'Số lượng phải là số nguyên',
            'QuantityInStock.min' => 'Số lượng không thể âm',
        ];
    }
}