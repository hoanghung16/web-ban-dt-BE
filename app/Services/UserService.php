<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserService
{
    /**
     * Get all users with pagination
     */
    public function getAllUsers($perPage = 20): LengthAwarePaginator
    {
        return User::query()
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get users by role
     */
    public function getByRole(string $role, $perPage = 20): LengthAwarePaginator
    {
        return User::query()
            ->where('role', $role)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Search users
     */
    public function search(string $query, $perPage = 20): LengthAwarePaginator
    {
        return User::query()
            ->where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get user by ID
     */
    public function getById(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Get admin users
     */
    public function getAdmins()
    {
        return User::where('role', 'admin')->get();
    }

    /**
     * Create new user
     */
    public function create(array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        return User::create($data);
    }

    /**
     * Update user
     */
    public function update(int $id, array $data): User
    {
        $user = User::findOrFail($id);
        
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }
        
        $user->update($data);
        return $user;
    }

    /**
     * Delete user
     */
    public function delete(int $id): bool
    {
        $user = User::findOrFail($id);
        return $user->delete();
    }

    /**
     * Change user password
     */
    public function changePassword(int $id, string $newPassword): bool
    {
        $user = User::findOrFail($id);
        $user->update(['password' => bcrypt($newPassword)]);
        return true;
    }

    /**
     * Promote user to admin
     */
    public function promoteToAdmin(int $id): User
    {
        $user = User::findOrFail($id);
        $user->update(['role' => 'admin']);
        return $user;
    }

    /**
     * Demote admin to customer
     */
    public function demoteToCustomer(int $id): User
    {
        $user = User::findOrFail($id);
        $user->update(['role' => 'customer']);
        return $user;
    }
}
