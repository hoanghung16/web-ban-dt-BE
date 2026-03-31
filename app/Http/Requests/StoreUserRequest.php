<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ValidatesUserTrait;

class StoreUserRequest extends FormRequest
{
    use ValidatesUserTrait;

    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        return $this->getUserRules();
    }
    
    public function messages()
    {
        return $this->getUserMessages();
    }
}