<?php
namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Chỉ Admin mới quản lý users
     */
    public function viewAny(User $user)
    {
        return $user->role === 'Admin';
    }

    public function view(User $user, User $model)
    {
        return $user->id === $model->id || $user->role === 'Admin';
    }

    public function create(User $user)
    {
        return $user->role === 'Admin';
    }

    public function update(User $user, User $model)
    {
        return $user->id === $model->id || $user->role === 'Admin';
    }

    public function delete(User $user, User $model)
    {
        return $user->role === 'Admin';
    }
}