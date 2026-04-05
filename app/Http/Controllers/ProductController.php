<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Services\ProductService;
use App\Services\CloudinaryService;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ProductService $productService;
    protected CloudinaryService $cloudinaryService;

    public function __construct(ProductService $productService, CloudinaryService $cloudinaryService)
    {
        $this->productService = $productService;
        $this->cloudinaryService = $cloudinaryService;
    }

    // Frontend listing (public)
    public function getProducts(Request $request)
    {
        $categoryId = $request->get('categoryid');
        $search = $request->get('search');
        // Support both 'limit' and 'per_page' parameters
        $perPage = min($request->get('limit') ?? $request->get('per_page', 12), 100);
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
        $product = Product::find($id);
        
        if ($product && env('UPLOAD_DRIVER', 'local') === 'cloudinary' && $product->cloudinary_public_id) {
            // Delete from Cloudinary
            $this->cloudinaryService->deleteFile($product->cloudinary_public_id);
        }
        
        $this->productService->delete($id);
        return response()->json(['message' => 'Sản phẩm đã xóa']);
    }

    /**
     * Upload hình ảnh sản phẩm
     * POST /api/products/upload-image
     * Support both local storage and Cloudinary based on UPLOAD_DRIVER env
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120' // 5MB max
        ], [
            'image.required' => 'Vui lòng chọn hình ảnh',
            'image.image' => 'Tệp phải là hình ảnh',
            'image.mimes' => 'Chỉ chấp nhận: jpeg, png, jpg, gif, webp',
            'image.max' => 'Kích thước tối đa: 5MB'
        ]);

        try {
            $file = $request->file('image');
            $uploadDriver = env('UPLOAD_DRIVER', 'local');
            
            if ($uploadDriver === 'cloudinary') {
                // Upload to Cloudinary
                $result = $this->cloudinaryService->uploadFile($file, 'products');

                if (!$result['success']) {
                    return response()->json([
                        'message' => 'Lỗi upload: ' . $result['error']
                    ], 500);
                }

                return response()->json([
                    'message' => 'Upload thành công',
                    'imageUrl' => $result['url'],
                    'public_id' => $result['public_id'],
                    'fullUrl' => $result['url']
                ], 200);
            } else {
                // Upload to local storage
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images/products'), $filename);
                $imagePath = '/images/products/' . $filename;

                return response()->json([
                    'message' => 'Upload thành công',
                    'imageUrl' => $imagePath,
                    'public_id' => null,
                    'fullUrl' => url($imagePath)
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lỗi upload: ' . $e->getMessage()
            ], 500);
        }
    }
}