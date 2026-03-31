<?php

namespace App\Traits;

trait ValidatesInventoryTrait
{
    /**
     * Get inventory validation rules
     */
    protected function getInventoryRules(): array
    {
        return [
            'productid' => 'required|exists:products,id|unique:inventories,productid,' . (isset($this->inventory->id) ? $this->inventory->id : 'null'),
            'QuantityInStock' => 'required|integer|min:0',
            'LastRestocking' => 'nullable|date',
        ];
    }

    /**
     * Get inventory validation messages
     */
    protected function getInventoryMessages(): array
    {
        return [
            'productid.required' => 'Sản phẩm không hợp lệ',
            'productid.exists' => 'Sản phẩm không tồn tại',
            'productid.unique' => 'Sản phẩm này đã có trong kho',
            'QuantityInStock.required' => 'Số lượng không được để trống',
            'QuantityInStock.integer' => 'Số lượng phải là số nguyên',
            'QuantityInStock.min' => 'Số lượng không được âm',
        ];
    }
}
