<?php
namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Tất cả authenticated users có thể tạo order
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Chỉ chủ order hoặc Admin mới có thể view/update/delete
     */
    public function view(User $user, Order $order)
    {
        return $user->id === $order->userid || $user->role === 'Admin';
    }

    public function update(User $user, Order $order)
    {
        return $user->id === $order->userid || $user->role === 'Admin';
    }

    public function delete(User $user, Order $order)
    {
        return $user->id === $order->userid || $user->role === 'Admin';
    }
}