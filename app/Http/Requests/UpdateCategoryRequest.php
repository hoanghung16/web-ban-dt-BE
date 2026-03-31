<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ValidatesCategoryTrait;

class UpdateCategoryRequest extends FormRequest
{
    use ValidatesCategoryTrait;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return $this->getCategoryRules();
    }
    
    public function messages()
    {
        return $this->getCategoryMessages();
    }
}