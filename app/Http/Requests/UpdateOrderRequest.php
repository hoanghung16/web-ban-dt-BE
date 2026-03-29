<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize() { return true; }
    
    public function rules()
    {
        return [
            'status' => 'string|max:50',
            'paymentstatus' => 'string|max:50',
            'shipname' => 'string|max:255',
            'shipaddress' => 'string|max:500',
            'shipphone' => 'string|max:20',
        ];
    }
}