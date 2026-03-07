<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    // Cho trang bán hàng frontend (format đẹp)
    public function getProducts()
    {
        $products = Product::all();
        $formattedProducts = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => number_format($product->price, 0, ',', '.') . 'đ',
                'tag' => cloneTagFromMock($product->name), 
                'color' => 'Mặc định',
                'flash_sale' => $product->IsOnSale == 1,
                'image' => $product->imageUrl,
                'categoryid' => $product->categoryid,
            ];
        });
        return response()->json($formattedProducts);
    }

    // Cho trang Admin (dữ liệu gốc đầy đủ)
    public function index()
    {
        return response()->json(Product::all());
    }

    public function getCategories()
    {
        return response()->json(Category::all());
    }

    // --- CRUD ADMIN ---
    public function store(Request $request)
    {
        $validated = $request->validate([
            'categoryid' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'saleprice' => 'nullable|numeric',
            'IsOnSale' => 'boolean',
            'IsPublished' => 'boolean',
            'imageUrl' => 'nullable|string'
        ]);

        $product = Product::create($validated);
        return response()->json($product, 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $validated = $request->validate([
            'categoryid' => 'exists:categories,id',
            'name' => 'string|max:255',
            'price' => 'numeric',
            'saleprice' => 'nullable|numeric',
            'IsOnSale' => 'boolean',
            'IsPublished' => 'boolean',
            'imageUrl' => 'nullable|string'
        ]);

        $product->update($validated);
        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Xóa sản phẩm thành công']);
    }
}

function cloneTagFromMock($name) {
    if (strpos($name, 'iPhone 15') !== false) return 'Bán chạy';
    if (strpos($name, 'Xiaomi 14') !== false) return 'Leica';
    if (strpos($name, 'Poco') !== false) return 'Gaming';
    if (strpos($name, 'Redmi') !== false) return 'Giá Rẻ';
    if (strpos($name, '20W') !== false) return 'HOT';
    return '';
}
