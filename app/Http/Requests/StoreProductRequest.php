<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            "name" => "required|string|max:255|unique:products,name",
            "price" => "required|numeric|min:0",
            "saleprice" => "nullable|numeric|min:0",
            "description" => "nullable|string|max:1000",
            "imageUrl" => "nullable|string",
            "categoryid" => "required|exists:categories,id",
        ];
    }
}
