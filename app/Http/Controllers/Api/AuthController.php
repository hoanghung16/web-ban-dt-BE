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

    /**
     * Quên mật khẩu - gửi link đặt lại
     * POST /api/auth/forgot-password
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Email không tồn tại trong hệ thống',
        ]);

        // Tìm user theo email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Email không tồn tại',
                'errors' => ['email' => ['Email này chưa được đăng ký']]
            ], 404);
        }

        // Tạo reset token (trong thực tế nên dùng PasswordReset::create())
        // Tạm thời trả về success message
        return response()->json([
            'message' => 'Hướng dẫn đặt lại mật khẩu đã được gửi đến email của bạn',
        ], 200);
    }

    /**
     * Đổi mật khẩu
     * POST /api/auth/change-password
     */
    public function changePassword(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'current_password.required' => 'Mật khẩu hiện tại là bắt buộc',
            'password.required' => 'Mật khẩu mới là bắt buộc',
        ]);

        $user = $request->user();

        // Kiểm tra mật khẩu cũ
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Mật khẩu hiện tại không đúng',
                'errors' => ['current_password' => ['Mật khẩu không chính xác']]
            ], 422);
        }

        // Cập nhật mật khẩu mới
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'Mật khẩu đã được thay đổi thành công'
        ], 200);
    }

    /**
     * Cập nhật profil
     * PUT /api/auth/profile
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
        ]);

        $user = $request->user();
        $user->update([
            'fullname' => $request->fullname,
        ]);

        return response()->json([
            'message' => 'Thông tin cá nhân đã được cập nhật',
            'user' => new UserResource($user)
        ], 200);
    }
}
