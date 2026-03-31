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
        return strtolower($user->role) === 'admin';
    }

    public function view(User $user, User $model)
    {
        return $user->id === $model->id || strtolower($user->role) === 'admin';
    }

    public function create(User $user)
    {
        return strtolower($user->role) === 'admin';
    }

    public function update(User $user, User $model)
    {
        return $user->id === $model->id || strtolower($user->role) === 'admin';
    }

    public function delete(User $user, User $model)
    {
        return strtolower($user->role) === 'admin';
    }
}