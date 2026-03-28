<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize() { return true; }
    
    public function rules()
    {
        return [
            'userid' => 'required|exists:users,id',
            'orderdate' => 'required|date',
            'status' => 'required|string|max:50',
            'paymentstatus' => 'required|string|max:50',
            'totalprice' => 'required|numeric|min:0',
            'shipname' => 'required|string|max:255',
            'shipaddress' => 'required|string|max:500',
            'shipphone' => 'required|string|max:20',
        ];
    }
}