<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Frontend listing (public)
    public function getProducts(Request $request)
    {
        $query = Product::query()->with('category', 'inventory');
        
        // Filter by category
        if ($request->has('categoryid')) {
            $query->byCategory($request->categoryid);
        }
        
        // Search by name
        if ($request->has('search')) {
            $query->search($request->search);
        }
        
        // Publish filter
        if ($request->has('published')) {
            $query->published();
        }
        
        // Pagination
        $perPage = min($request->get('per_page', 12), 100);
        $products = $query->paginate($perPage);
        
        return ProductResource::collection($products);
    }
    
    // Admin panel (raw data)
    public function index(Request $request)
    {
        $perPage = min($request->get('per_page', 50), 100);
        return ProductResource::collection(
            Product::with('category')->paginate($perPage)
        );
    }
    
    public function store(StoreProductRequest $request)
    {
        // Không cần check explicit vì route đã có middleware
        $product = Product::create($request->validated());
        return (new ProductResource($product->load('category', 'inventory')))->response()->setStatusCode(201);
    }
    
    public function show($id)
    {
        $product = Product::with('category', 'inventory', 'orderItems')->findOrFail($id);
        return new ProductResource($product);
    }
    
   public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $this->authorize('update', $product); // Optional (route middleware already checks)
        
        $product->update($request->validated());
        return new ProductResource($product->load('category', 'inventory'));
    }
    
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $this->authorize('delete', $product);
        
        $product->delete();
        return response()->json(['message' => 'Sản phẩm đã xóa']);
    }
}