<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Đăng nhập & cấp token
     * POST /api/auth/login
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        
        // Kiểm tra password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email hoặc mật khẩu không đúng',
                'errors' => ['credentials' => ['Thông tin đăng nhập không hợp lệ']]
            ], 401);
        }
        
        // Tạo token
        $token = $user->createToken('api-token', ['*'], now()->addHours(24))->plainTextToken;
        
        return response()->json([
            'message' => 'Đăng nhập thành công',
            'user' => new UserResource($user),
            'token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }
    
    /**
     * Đăng ký tài khoản mới
     * POST /api/auth/register
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'Customer', // Default role
        ]);
        
        // Tạo token đăng nhập luôn
        $token = $user->createToken('api-token', ['*'], now()->addHours(24))->plainTextToken;
        
        return response()->json([
            'message' => 'Đăng ký thành công',
            'user' => new UserResource($user),
            'token' => $token,
        ], 201);
    }
    
    /**
     * Lấy thông tin user hiện tại
     * GET /api/auth/me
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => new UserResource($request->user())
        ]);
    }
    
    /**
     * Đăng xuất (xóa token)
     * POST /api/auth/logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'message' => 'Đăng xuất thành công'
        ]);
    }
    
    /**
     * Làm mới token
     * POST /api/auth/refresh
     */
    public function refresh(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete(); // Xóa token cũ
        
        $token = $user->createToken('api-token', ['*'], now()->addHours(24))->plainTextToken;
        
        return response()->json([
            'message' => 'Token đã được làm mới',
            'token' => $token,
        ]);
    }
}
