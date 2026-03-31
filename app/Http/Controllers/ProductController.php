<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Services\ProductService;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    // Frontend listing (public)
    public function getProducts(Request $request)
    {
        $categoryId = $request->get('categoryid');
        $search = $request->get('search');
        $perPage = min($request->get('per_page', 12), 100);
        $page = $request->get('page', 1);

        if ($categoryId) {
            $products = $this->productService->getByCategory($categoryId);
        } elseif ($search) {
            $products = $this->productService->search($search);
        } else {
            $products = $this->productService->getAllProducts($page, $perPage);
        }
        
        return ProductResource::collection($products);
    }
    
    public function index(Request $request)
    {
        $perPage = min($request->get('per_page', 50), 100);
        $page = $request->get('page', 1);
        $products = $this->productService->getAllProducts($page, $perPage);
        return ProductResource::collection($products);
    }
    
    public function store(StoreProductRequest $request)
    {
        $product = $this->productService->create($request->validated());
        return (new ProductResource($product))->response()->setStatusCode(201);
    }
    
    public function show($id)
    {
        $product = $this->productService->getById($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json(new ProductResource($product));
    }
    
   public function update(UpdateProductRequest $request, $id)
    {
        $product = $this->productService->update($id, $request->validated());
        return response()->json(new ProductResource($product));
    }
    
    public function destroy($id)
    {
        $this->productService->delete($id);
        return response()->json(['message' => 'Sản phẩm đã xóa']);
    }
}