<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && strtolower($request->user()->role) !== 'admin') {
            return response()->json([
                'message' => 'Bạn không có quyền truy cập tài nguyên này',
                'error' => 'Unauthorized'
            ], 403);
        }

        return $next($request);
    }
}