<?php

namespace App\Traits;

trait ValidatesUserTrait
{
    /**
     * Get user validation rules
     */
    protected function getUserRules(): array
    {
        return [
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'nullable|string|in:admin,customer,user',
        ];
    }

    /**
     * Get update-specific user rules (password optional)
     */
    protected function getUserUpdateRules(): array
    {
        return [
            'fullname' => 'sometimes|string|max:255',
            'password' => 'nullable|string|min:6',
            'role' => 'sometimes|string|in:admin,customer,user',
        ];
    }

    /**
     * Get user validation messages
     */
    protected function getUserMessages(): array
    {
        return [
            'fullname.required' => 'Tên không được để trống',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã được đăng ký',
            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
        ];
    }
}
