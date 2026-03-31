<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\{ProductController, CategoryController, UserController, 
                         InventoryController, OrderController, OrderItemController};

// PUBLIC endpoints (no auth required)
Route::get('/products', [ProductController::class, 'getProducts']); 
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);

// Auth endpoints (no auth required for login/register)
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    // Auth endpoints
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);
    
    // Product CRUD (Admin only)
    Route::apiResource('products', ProductController::class)
        ->except(['show', 'index'])
        ->middleware('can:create,App\Models\Product'); // Check policy
    
    // Category CRUD (Admin only)
    Route::apiResource('categories', CategoryController::class)
        ->middleware('can:create,App\Models\Category');
    
    // User CRUD (Admin only)
    Route::apiResource('users', UserController::class)
        ->middleware('can:viewAny,App\Models\User');
    
    // Inventory CRUD (Admin only)
    Route::apiResource('inventories', InventoryController::class)
        ->middleware('can:create,App\Models\Inventory');
    
    // Orders (own orders for customers, all for admin)
    Route::apiResource('orders', OrderController::class);
    
    // OrderItems (create for own order)
    Route::apiResource('order-items', OrderItemController::class);
});