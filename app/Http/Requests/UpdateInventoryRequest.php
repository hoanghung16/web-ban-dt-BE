<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ValidatesInventoryTrait;

class UpdateInventoryRequest extends FormRequest
{
    use ValidatesInventoryTrait;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return $this->getInventoryRules();
    }
    
    public function messages()
    {
        return $this->getInventoryMessages();
    }
}