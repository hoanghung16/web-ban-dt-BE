<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminDashboardController;
use App\Http\Controllers\{ProductController, CategoryController, UserController, 
                         InventoryController, OrderController, OrderItemController};

// PUBLIC endpoints (no auth required)
Route::get('/products', [ProductController::class, 'getProducts']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);

// Auth endpoints (no auth required for login/register)
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);

Route::middleware('auth:sanctum')->group(function () {
    // Auth endpoints
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);
    Route::post('/auth/change-password', [AuthController::class, 'changePassword']);
    Route::put('/auth/profile', [AuthController::class, 'updateProfile']);
    
// Admin-only endpoints grouping
    Route::middleware('admin')->group(function() {
        // Admin Dashboard
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index']);
        
        // Product CRUD (except index and show, which are public)
        Route::apiResource('products', ProductController::class)->except(['show', 'index']);
        Route::post('products/upload-image', [ProductController::class, 'uploadImage']);
        
        // Category CRUD (except index and show)
        Route::apiResource('categories', CategoryController::class)->except(['show', 'index']);
        
        // User CRUD
        Route::apiResource('users', UserController::class);
        
        // User role update (promote/demote)
        Route::put('/users/{id}/role', [UserController::class, 'updateRole']);
        
        // Inventory CRUD
        Route::apiResource('inventories', InventoryController::class);
    });
    
    // Orders (own orders for customers, all for admin)
    Route::apiResource('orders', OrderController::class);
    
    // OrderItems (create for own order)
    Route::apiResource('order-items', OrderItemController::class);
});