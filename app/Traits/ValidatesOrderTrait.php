<?php

namespace App\Traits;

trait ValidatesOrderTrait
{
    /**
     * Get order validation rules
     */
    protected function getOrderRules(): array
    {
        return [
            'userid' => 'required|exists:users,id',
            'status' => 'nullable|string|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'paymentstatus' => 'nullable|string|in:unpaid,paid,refunded',
            'totalprice' => 'required|numeric|min:0',
            'shipname' => 'required|string|max:255',
            'shipaddress' => 'required|string|max:1000',
            'shipphone' => 'required|string|max:20',
        ];
    }

    /**
     * Get order validation messages
     */
    protected function getOrderMessages(): array
    {
        return [
            'userid.required' => 'Người dùng không hợp lệ',
            'userid.exists' => 'Người dùng không tồn tại',
            'shipname.required' => 'Tên người nhận không được để trống',
            'shipaddress.required' => 'Địa chỉ giao hàng không được để trống',
            'shipphone.required' => 'Số điện thoại không được để trống',
        ];
    }
}
