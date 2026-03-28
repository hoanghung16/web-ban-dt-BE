<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize() { return true; }
    
    public function rules()
    {
        $userId = $this->route('id') ?? $this->route('user');

        return [
            'fullname' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users,email,' . $userId,
            'password' => 'nullable|string|min:8', 
            'role' => 'nullable|string|max:50',
        ];
    }
}