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
        return strtolower($user->role) === 'admin';
    }

    public function update(User $user, Product $product)
    {
        return strtolower($user->role) === 'admin';
    }

    public function delete(User $user, Product $product)
    {
        return strtolower($user->role) === 'admin';
    }

    /**
     * Tất cả (auth hoặc not) có thể view product
     */
    public function view(?User $user, Product $product)
    {
        return $product->IsPublished || ($user && strtolower($user->role) === 'admin');
    }
}