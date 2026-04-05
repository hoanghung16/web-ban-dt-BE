<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Login route (named for compatibility with Laravel auth)
Route::get('/login', function () {
    return response()->json(['message' => 'Please use /api/auth/login'], 401);
})->name('login');

Route::get('/', function () {
    // Trả về danh sách user thay vì trang welcome của Laravel
    return \App\Models\User::all();
});

Route::get('/users', function () {
    return \App\Models\User::all();
});

Route::get('/users/{id}', function ($id) {
    $user = \App\Models\User::find($id);
    
    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }
    
    return response()->json($user);
});
