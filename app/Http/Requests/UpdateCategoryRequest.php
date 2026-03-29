<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true; // TV03 sẽ thêm policy checks
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|string|max:255|unique:categories,name,' . $this->category->id,
            'slug' => 'sometimes|string|max:255|unique:categories,slug,' . $this->category->id,
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Tên danh mục đã tồn tại',
            'slug.unique' => 'Slug đã tồn tại',
        ];
    }
}