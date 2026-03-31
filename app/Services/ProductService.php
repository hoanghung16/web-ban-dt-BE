<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    /**
     * Get all products with pagination
     */
    public function getAllProducts($page = 1, $perPage = 20): LengthAwarePaginator
    {
        return Product::query()
            ->with(['category', 'inventory'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Get products by category
     */
    public function getByCategory(int $categoryId, $perPage = 20): LengthAwarePaginator
    {
        return Product::query()
            ->where('categoryid', $categoryId)
            ->with(['category', 'inventory'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Search products by name or description
     */
    public function search(string $query, $perPage = 20): LengthAwarePaginator
    {
        return Product::query()
            ->where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->with(['category', 'inventory'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get product by ID
     */
    public function getById(int $id): ?Product
    {
        return Product::with(['category', 'inventory'])->find($id);
    }

    /**
     * Create new product
     */
    public function create(array $data): Product
    {
        return Product::create($data);
    }

    /**
     * Update product
     */
    public function update(int $id, array $data): Product
    {
        $product = Product::findOrFail($id);
        $product->update($data);
        return $product;
    }

    /**
     * Delete product
     */
    public function delete(int $id): bool
    {
        $product = Product::findOrFail($id);
        return $product->delete();
    }

    /**
     * Get related products (same category)
     */
    public function getRelated(int $productId, int $limit = 5)
    {
        $product = Product::find($productId);
        if (!$product) {
            return collect();
        }

        return Product::query()
            ->where('categoryid', $product->categoryid)
            ->where('id', '!=', $productId)
            ->limit($limit)
            ->get();
    }

    /**
     * Get featured products
     */
    public function getFeatured(int $limit = 8)
    {
        return Product::query()
            ->with(['category', 'inventory'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get products on sale
     */
    public function getOnSale(int $limit = 10)
    {
        return Product::query()
            ->whereNotNull('saleprice')
            ->where('saleprice', '>', 0)
            ->with(['category', 'inventory'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
