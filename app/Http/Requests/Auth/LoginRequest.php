<?php
namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Public endpoint
    }

    public function rules()
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6',
        ];
    }
    
    public function messages()
    {
        return [
            'email.exists' => 'Email không tồn tại trong hệ thống',
            'password.min' => 'Mật khẩu phải ít nhất 6 ký tự',
        ];
    }
}