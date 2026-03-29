<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize() { return true; }
    
    public function rules()
    {
        $productId = $this->route('id') ?? $this->route('product');
        
        return [
            'categoryid' => 'exists:categories,id',
            'name' => 'string|max:255|unique:products,name,' . $productId,
            'price' => 'numeric|min:0',
            'saleprice' => 'nullable|numeric|min:0|lt:price',
            'IsOnSale' => 'boolean',
            'IsPublished' => 'boolean',
            'imageUrl' => 'nullable|string|max:500',
        ];
    }
}