<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return response()->json([
                'message' => 'Unauthenticated',
                'error' => 'Unauthorized'
            ], 401);
        }
        
        // Check if user is admin
        if (strtolower($request->user()->role) !== 'admin') {
            return response()->json([
                'message' => 'Bạn không có quyền truy cập tài nguyên này',
                'error' => 'Forbidden'
            ], 403);
        }

        return $next($request);
    }
}