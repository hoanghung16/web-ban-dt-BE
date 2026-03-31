<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules()
    {
        return [
            "status" => "nullable|string|in:pending,confirmed,processing,shipped,delivered,cancelled",
            "paymentstatus" => "nullable|string|in:unpaid,paid,refunded",
            "totalprice" => "sometimes|numeric|min:0",
            "shipname" => "sometimes|string|max:255",
            "shipaddress" => "sometimes|string|max:1000",
            "shipphone" => "sometimes|string|max:20",
        ];
    }
}
