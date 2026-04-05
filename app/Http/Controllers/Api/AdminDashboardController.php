<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    /**
     * Get admin dashboard data
     * GET /api/admin/dashboard
     */
    public function index()
    {
        try {
            // Total Revenue (sum of all paid orders)
            $total_revenue = Order::where('paymentstatus', 'paid')
                ->sum('totalprice') ?? 0;

            // Total Orders
            $total_orders = Order::count();

            // Total Customers
            $total_customers = User::where('role', 'customer')->count();

            // Total Products
            $total_products = Product::count();

            // Low Stock Alerts (less than 20 units)
            $low_stock_alerts = Product::whereHas('inventory', function($q) {
                $q->where('QuantityInStock', '<', 20);
            })->count();

            // Pending Orders (not confirmed)
            $pending_orders = Order::whereIn('status', ['pending', 'processing'])
                ->count();

            // Revenue Trend (last 7 days)
            $revenue_trend = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->format('Y-m-d');
                $amount = Order::whereDate('created_at', $date)
                    ->where('paymentstatus', 'paid')
                    ->sum('totalprice') ?? 0;
                $revenue_trend[$date] = (float) $amount;
            }

            // Top Products (this week)
            $top_products = DB::table('order_items')
                ->join('products', 'order_items.productid', '=', 'products.id')
                ->join('orders', 'order_items.orderid', '=', 'orders.id')
                ->where('orders.created_at', '>=', Carbon::now()->subDays(7))
                ->select('products.id', 'products.name', DB::raw('COUNT(*) as sales'))
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('sales')
                ->limit(5)
                ->get();

            // Recent Orders (last 5)
            $recent_orders = Order::with('user')
                ->latest()
                ->limit(5)
                ->get()
                ->map(function ($order) {
                    return [
                        'id' => $order->id,
                        'customer_name' => isset($order->user) ? $order->user->fullname : 'Unknown',
                        'status' => $order->status,
                        'total_amount' => (float) $order->totalprice,
                        'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                    ];
                });

            // Recent Customers (last 10)
            $recent_customers = User::where('role', 'customer')
                ->latest()
                ->limit(10)
                ->select('id', 'fullname', 'email', 'created_at')
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->fullname,
                        'email' => $user->email,
                        'joined_at' => $user->created_at->format('Y-m-d'),
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'total_revenue' => (float) $total_revenue,
                    'total_orders' => (int) $total_orders,
                    'total_customers' => (int) $total_customers,
                    'total_products' => (int) $total_products,
                    'low_stock_alerts' => (int) $low_stock_alerts,
                    'pending_orders' => (int) $pending_orders,
                    'revenue_trend' => (object) $revenue_trend,
                    'top_products' => $top_products->toArray(),
                    'recent_orders' => $recent_orders->toArray(),
                    'recent_customers' => $recent_customers->toArray(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch dashboard data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
