<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        $id = $this->route("product") ?? "null";
        return [
            "name" => "sometimes|string|max:255|unique:products,name," . $id,
            "price" => "sometimes|numeric|min:0",
            "saleprice" => "nullable|numeric|min:0",
            "description" => "nullable|string|max:1000",
            "imageUrl" => "nullable|string",
            "categoryid" => "sometimes|exists:categories,id",
        ];
    }
}
