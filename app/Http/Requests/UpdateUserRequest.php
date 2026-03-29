<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize() { return true; }
    
    public function rules()
{
    return [
        'fullname' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|unique:users,email,' . $this->user->id,
        'password' => 'sometimes|string|min:6|confirmed',
    ];
}
}