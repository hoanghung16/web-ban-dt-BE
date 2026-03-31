<?php

namespace App\Traits;

trait ValidatesProductTrait
{
    /**
     * Get product validation rules
     */
    protected function getProductRules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:products,name,' . (isset($this->product->id) ? $this->product->id : 'null'),
            'price' => 'required|numeric|min:0',
            'saleprice' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'imageUrl' => 'nullable|url',
            'categoryid' => 'required|exists:categories,id',
        ];
    }

    /**
     * Get product validation messages
     */
    protected function getProductMessages(): array
    {
        return [
            'name.required' => 'Tên sản phẩm không được để trống',
            'name.unique' => 'Tên sản phẩm đã tồn tại',
            'price.required' => 'Giá không được để trống',
            'price.min' => 'Giá phải lớn hơn 0',
            'categoryid.required' => 'Danh mục không được để trống',
            'categoryid.exists' => 'Danh mục không hợp lệ',
        ];
    }
}
