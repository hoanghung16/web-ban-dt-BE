<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize() { return true; }
    
    public function rules()
    {
        return [
            'categoryid' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:products',
            'price' => 'required|numeric|min:0',
            'saleprice' => 'nullable|numeric|min:0|lt:price',
            'IsOnSale' => 'boolean',
            'IsPublished' => 'boolean',
            'imageUrl' => 'nullable|string|max:500',
        ];
    }
    
    public function messages()
    {
        return [
            'categoryid.exists' => 'Danh mục không tồn tại',
            'saleprice.lt' => 'Giá giảm phải nhỏ hơn giá gốc',
            'name.unique' => 'Tên sản phẩm đã tồn tại',
        ];
    }
}