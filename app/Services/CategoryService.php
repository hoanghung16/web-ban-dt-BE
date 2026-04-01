<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryService
{
    /**
     * Get all categories
     */
    public function getAllCategories($perPage = 20): LengthAwarePaginator
    {
        return Category::query()
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get all categories as list (for dropdowns)
     */
    public function getCategoriesList()
    {
        return Category::query()
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Get category by ID
     */
    public function getById(int $id): ?Category
    {
        return Category::find($id);
    }

    /**
     * Create new category
     */
    public function create(array $data): Category
    {
        return Category::create($data);
    }

    /**
     * Update category
     */
    public function update(int $id, array $data): Category
    {
        $category = Category::findOrFail($id);
        $category->update($data);
        return $category;
    }

    /**
     * Delete category
     */
    public function delete(int $id): bool
    {
        $category = Category::findOrFail($id);
        
        // Check if category has products
        $productCount = $category->products()->count();
        if ($productCount > 0) {
            throw new \Exception("Không thể xóa danh mục '{$category->name}' vì đang có {$productCount} sản phẩm. Vui lòng xóa hoặc chuyển các sản phẩm trước.");
        }
        
        return $category->delete();
    }

    /**
     * Get category with product count
     */
    public function getWithProductCount()
    {
        return Category::withCount('products')
            ->orderBy('name', 'asc')
            ->get();
    }
}
