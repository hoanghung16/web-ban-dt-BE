<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{ProductController, CategoryController, UserController, 
                         InventoryController, OrderController, OrderItemController};

// PUBLIC endpoints
Route::get('/products', [ProductController::class, 'getProducts']); 
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);

// Protected CRUD (will add auth in Thành viên 03)
Route::apiResource('products', ProductController::class)->except(['show']);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('inventories', InventoryController::class);
Route::apiResource('orders', OrderController::class);
Route::apiResource('order-items', OrderItemController::class);
