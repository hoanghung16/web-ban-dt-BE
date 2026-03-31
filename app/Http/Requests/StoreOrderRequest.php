<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ValidatesOrderTrait;

class StoreOrderRequest extends FormRequest
{
    use ValidatesOrderTrait;

    public function authorize() { return true; }
    
    public function rules()
    {
        return $this->getOrderRules();
    }
    
    public function messages()
    {
        return $this->getOrderMessages();
    }
}