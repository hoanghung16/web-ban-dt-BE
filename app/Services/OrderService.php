<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrderService
{
    /**
     * Get all orders with pagination
     */
    public function getAllOrders($page = 1, $perPage = 20): LengthAwarePaginator
    {
        return Order::query()
            ->with(['user', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Get orders by user
     */
    public function getByUserId(int $userId, $page = 1, $perPage = 20): LengthAwarePaginator
    {
        return Order::query()
            ->where('userid', $userId)
            ->with(['user', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Get orders by status
     */
    public function getByStatus(string $status, $page = 1, $perPage = 20): LengthAwarePaginator       
    {
        return Order::query()
            ->where('status', $status)
            ->with(['user', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Get unpaid orders
     */
    public function getUnpaidOrders($perPage = 20): LengthAwarePaginator
    {
        return Order::query()
            ->where('paymentstatus', 'unpaid')
            ->with(['user', 'items.product'])
            ->orderBy('created_at', 'asc')
            ->paginate($perPage);
    }

    /**
     * Get order by ID
     */
    public function getById(int $id): ?Order
    {
        return Order::with(['user', 'items.product'])->find($id);
    }

    /**
     * Create new order
     */
    public function create(array $data): Order
    {
        return Order::create($data);
    }

    /**
     * Update order
     */
    public function update(int $id, array $data): Order
    {
        $order = Order::findOrFail($id);
        $order->update($data);
        return $order;
    }

    /**
     * Update order status
     */
    public function updateStatus(int $id, string $status): Order
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => $status]);
        return $order;
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(int $id, string $paymentStatus): Order
    {
        $order = Order::findOrFail($id);
        $order->update(['paymentstatus' => $paymentStatus]);
        return $order;
    }

    /**
     * Delete order
     */
    public function delete(int $id): bool
    {
        $order = Order::findOrFail($id);
        // Delete order items first
        $order->items()->delete();
        return $order->delete();
    }

    /**
     * Get order statistics
     */
    public function getStatistics()
    {
        return [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'delivered')->count(),
            'total_revenue' => Order::where('paymentstatus', 'paid')->sum('totalprice'),
            'unpaid_total' => Order::where('paymentstatus', 'unpaid')->sum('totalprice'),
        ];
    }
}
