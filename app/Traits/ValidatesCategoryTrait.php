<?php

namespace App\Traits;

trait ValidatesCategoryTrait
{
    /**
     * Get category validation rules
     */
    protected function getCategoryRules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:categories,name,' . (isset($this->category->id) ? $this->category->id : 'null'),
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . (isset($this->category->id) ? $this->category->id : 'null'),
            'description' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get category validation messages
     */
    protected function getCategoryMessages(): array
    {
        return [
            'name.required' => 'Tên danh mục không được để trống',
            'name.unique' => 'Tên danh mục đã tồn tại',
            'slug.unique' => 'Slug đã tồn tại',
        ];
    }
}
