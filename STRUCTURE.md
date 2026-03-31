# 🏗️ Backend Project Structure Guide - Laravel

## Thư mục chính

### `app/Services` 🆕
Lớp dịch vụ chứa logic kinh doanh (Business Logic Layer).

**Mục đích:**
- Tách logic xử lý ra khỏi Controllers
- Tạo code reusable
- Dễ test hơn

**Cấu trúc:**
```php
app/Services/
├── ProductService.php
├── CategoryService.php
├── OrderService.php
├── UserService.php
├── AuthService.php
└── InventoryService.php
```

**Ví dụ:**
```php
// app/Services/ProductService.php
<?php
namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function getAllProducts($page = 1, $perPage = 20)
    {
        return Product::paginate($perPage);
    }
    
    public function createProduct(array $data)
    {
        return Product::create($data);
    }
    
    public function updateProduct(int $id, array $data)
    {
        $product = Product::findOrFail($id);
        $product->update($data);
        return $product;
    }
}

// Usage in Controller
<?php
class ProductController extends Controller
{
    private $productService;
    
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    
    public function index()
    {
        $products = $this->productService->getAllProducts();
        return ProductResource::collection($products);
    }
}
```

---

### `app/Traits` 🆕
Reusable traits cho validation logic.

**Mục đích:**
- Reduce duplication trong Request classes
- Share common validation rules

**Cấu trúc:**
```php
app/Traits/
├── ValidatesStoreTrait.php
├── ValidatesUpdateTrait.php
└── ValidatesProductTrait.php
```

**Ví dụ:**
```php
// app/Traits/ValidatesProductTrait.php
<?php
namespace App\Traits;

trait ValidatesProductTrait
{
    protected function getProductRules()
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'saleprice' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'categoryid' => 'required|exists:categories,id',
        ];
    }
}

// Usage in Request class
<?php
class StoreProductRequest extends FormRequest
{
    use ValidatesProductTrait;
    
    public function rules()
    {
        return $this->getProductRules();
    }
}
```

---

### `app/Enums` 🆕
Type-safe constants cho status, roles, etc.

**Cấu trúc:**
```php
app/Enums/
├── OrderStatus.php
├── PaymentStatus.php
├── UserRole.php
└── InventoryStatus.php
```

**Ví dụ:**
```php
// app/Enums/OrderStatus.php
<?php
namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case PROCESSING = 'processing';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';
    
    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Chờ xử lý',
            self::CONFIRMED => 'Đã xác nhận',
            self::SHIPPED => 'Đã gửi',
            self::DELIVERED => 'Đã giao',
            self::CANCELLED => 'Đã hủy',
        };
    }
}

// Usage
$order->status = OrderStatus::PENDING->value;
echo OrderStatus::PENDING->label(); // "Chờ xử lý"
```

---

### `app/Helpers` 🆕
Utility functions cho formatting, calculations, etc.

**Cấu trúc:**
```php
app/Helpers/
├── PriceHelper.php
├── DateHelper.php
├── ValidationHelper.php
└── functions.php
```

**Ví dụ:**
```php
// app/Helpers/PriceHelper.php
<?php
namespace App\Helpers;

class PriceHelper
{
    public static function formatPrice($price)
    {
        return number_format($price, 0, '', '.');
    }
    
    public static function discountAmount($original, $sale)
    {
        return max(0, $original - $sale);
    }
    
    public static function discountPercent($original, $sale)
    {
        if ($original == 0) return 0;
        return round(($original - $sale) / $original * 100);
    }
}

// Usage
PriceHelper::formatPrice(1500000); // "1.500.000"
```

---

### `app/Http/Controllers`
Controllers điều phối request.

**Quy tắc:**
- Controllers **KHÔNG** chứa business logic
- Controllers **CÓ** inject Services để gọi logic
- Controllers focus vào HTTP stuff (request, response)

**Cấu trúc hiện tại:**
```php
app/Http/Controllers/
├── Api/
│   └── AuthController.php
├── CategoryController.php
├── ProductController.php
├── OrderController.php
├── OrderItemController.php
├── UserController.php
├── InventoryController.php
└── Controller.php
```

**Best Practice:**
```php
<?php
class ProductController extends Controller
{
    // ✅ Inject service vào constructor
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    
    public function index()
    {
        // ✅ Call service, không chứa logic
        $products = $this->productService->getAllProducts();
        return ProductResource::collection($products);
    }
    
    public function store(StoreProductRequest $request)
    {
        // ✅ Use $request->validated() đã được validate
        $product = $this->productService->createProduct(
            $request->validated()
        );
        return new ProductResource($product);
    }
}
```

---

### `app/Http/Requests`
Form validation classes.

**Cấu trúc:**
```php
app/Http/Requests/
├── Auth/
│   ├── LoginRequest.php
│   └── RegisterRequest.php
├── Product/
│   ├── StoreProductRequest.php
│   └── UpdateProductRequest.php
├── Category/
│   ├── StoreCategoryRequest.php
│   └── UpdateCategoryRequest.php
├── Order/
│   ├── StoreOrderRequest.php
│   └── UpdateOrderRequest.php
├── User/
│   ├── StoreUserRequest.php
│   └── UpdateUserRequest.php
└── Inventory/
    └── UpdateInventoryRequest.php
```

**Best Practice - Sử dụng Traits:**
```php
<?php
class StoreProductRequest extends FormRequest
{
    use ValidatesProductTrait;
    
    public function rules()
    {
        return $this->getProductRules();
    }
}

class UpdateProductRequest extends FormRequest
{
    use ValidatesProductTrait;
    
    public function rules()
    {
        // Sử dụng lại từ trait, có thể override nếu cần
        $rules = $this->getProductRules();
        $rules['name'] = 'sometimes|string|max:255'; // Thay đổi nếu cần
        return $rules;
    }
}
```

---

### `app/Http/Resources`
API response transformers.

**Mục đích:**
- Format dữ liệu trước khi trả về client
- Ẩn sensitive fields
- Consistent API responses

**Cấu trúc:**
```php
app/Http/Resources/
├── CategoryResource.php
├── ProductResource.php
├── OrderResource.php
├── OrderItemResource.php
├── UserResource.php
└── InventoryResource.php
```

**Ví dụ:**
```php
<?php
class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'inventory' => new InventoryResource($this->whenLoaded('inventory')),
        ];
    }
}
```

---

### `app/Models`
Database models.

**Cấu trúc:**
```php
app/Models/
├── User.php
├── Category.php
├── Product.php
├── Order.php
├── OrderItem.php
└── Inventory.php
```

**Best Practice:**
```php
<?php
class Product extends Model
{
    // ✅ Relationships
    public function category()
    {
        return $this->belongsTo(Category::class, 'categoryid');
    }
    
    public function inventory()
    {
        return $this->hasOne(Inventory::class, 'productid');
    }
    
    // ✅ Scopes for common queries
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
    
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('categoryid', $categoryId);
    }
    
    // ❌ KHÔNG chứa business logic ở model
    // Nó nên ở Services
}
```

---

### `app/Policies`
Authorization logic.

**Cấu trúc:**
```php
app/Policies/
├── UserPolicy.php
├── ProductPolicy.php
├── OrderPolicy.php
└── CategoryPolicy.php
```

**Ví dụ:**
```php
<?php
class OrderPolicy
{
    // Admin có thể update tất cả orders
    // Customer chỉ có thể view orders của mình
    public function update(User $user, Order $order)
    {
        return $user->isAdmin() || $user->id === $order->userid;
    }
}
```

---

### `database/migrations`
Database schemas.

**Cấu trúc:**
```php
database/migrations/
├── 2014_10_12_000000_create_users_table.php
├── 2026_03_24_091131_create_categories_table.php
├── 2026_03_24_091138_create_products_table.php
├── 2026_03_24_091145_create_inventories_table.php
├── 2026_03_24_091152_create_orders_table.php
└── 2026_03_24_091158_create_order_items_table.php
```

---

## ✅ Best Practices

### 1. Controller - Service Pattern
```php
// ✅ Good
class ProductController
{
    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }
    
    public function index()
    {
        return ProductResource::collection(
            $this->service->getAllProducts()
        );
    }
}

// ❌ Bad: Logic in controller
public function index()
{
    $products = Product::where('active', true)
        ->with('category', 'inventory')
        ->paginate(20);
    return ProductResource::collection($products);
}
```

### 2. Use Enums for Constants
```php
// ❌ Bad
if ($order->status === 'pending') {}
$order->status = 'shipped';

// ✅ Good
if ($order->status === OrderStatus::PENDING->value) {}
$order->status = OrderStatus::SHIPPED->value;
```

### 3. Validation Traits
```php
// ❌ Bad: Duplicate rules
class StoreProductRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ];
    }
}

class UpdateProductRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ];
    }
}

// ✅ Good: Use trait
trait ValidatesProductTrait
{
    protected function getProductRules()
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ];
    }
}
```

### 4. Eager Loading
```php
// ✅ Good: Eager load relationships
public function index()
{
    $products = Product::with('category', 'inventory')
        ->paginate(20);
    return ProductResource::collection($products);
}
```

---

## 📋 Migration Steps

1. **Create Services directory and classes**
   ```bash
   mkdir app/Services
   touch app/Services/{ProductService,CategoryService,OrderService}.php
   ```

2. **Create Traits for validation**
   ```bash
   mkdir app/Traits
   touch app/Traits/ValidatesProductTrait.php
   ```

3. **Create Enums**
   ```bash
   mkdir app/Enums
   touch app/Enums/{OrderStatus,PaymentStatus,UserRole}.php
   ```

4. **Update Controllers** - Inject services instead of using models directly

5. **Test the refactoring**
   ```bash
   php artisan test
   ```

---

## 🚀 Performance Optimization

1. **Use Eager Loading**: `Product::with('category', 'inventory')`
2. **Use Pagination**: Avoid loading all records
3. **Cache frequently accessed data**: Categories, statuses
4. **Use Indexes**: On foreign keys and frequently queried fields
5. **Query Optimization**: Use `select()` to limit columns

---

## 📚 References

- See `ARCHITECTURE.md` for full project structure
- Laravel Service Pattern: [Link](https://laravel.io)
- PHP Enums: [Link](https://www.php.net/manual/en/language.enumerations.php)
