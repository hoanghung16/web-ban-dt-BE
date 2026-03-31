<?php
namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    /**
     * Chỉ Admin mới tạo/update/delete product
     */
    public function create(User $user)
    {
        return $user->role === 'Admin';
    }

    public function update(User $user, Product $product)
    {
        return $user->role === 'Admin';
    }

    public function delete(User $user, Product $product)
    {
        return $user->role === 'Admin';
    }

    /**
     * Tất cả (auth hoặc not) có thể view product
     */
    public function view(?User $user, Product $product)
    {
        return $product->IsPublished || ($user && $user->role === 'Admin');
    }
}