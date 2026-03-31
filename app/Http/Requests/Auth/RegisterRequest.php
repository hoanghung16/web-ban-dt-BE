<?php
namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Public endpoint
    }

    public function rules()
    {
        return [
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed', // password_confirmation required
        ];
    }
    
    public function messages()
    {
        return [
            'email.unique' => 'Email đã được đăng ký',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
        ];
    }
}