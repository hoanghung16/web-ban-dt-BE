<?php
namespace App\Http\Controllers;
use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query()->with('user', 'items.product');
        
        // Filter by user
        if ($request->has('userid')) {
            $query->byUser($request->userid);
        }
        
        // Filter by status
        if ($request->has('status')) {
            $query->byStatus($request->status);
        }
        
        $perPage = min($request->get('per_page', 20), 100);
        return OrderResource::collection($query->orderBy('created_at', 'desc')->paginate($perPage));
    }
    
    public function store(StoreOrderRequest $request)
    {
        $validated = $request->validated();
        
        // Calculate total từ order_items nếu POST với items array
        if ($request->has('items')) {
            $total = 0;
            foreach ($request->items as $item) {
                $total += $item['quantity'] * $item['unitprice'];
            }
            $validated['totalprice'] = $total;
        }
        
        $order = Order::create($validated);
        return response()->json(new OrderResource($order->load('user', 'items.product')), 201);
    }
    
    public function show($id)
{
    $order = Order::findOrFail($id);
    
    // Check xem user có quyền view order này không
    $this->authorize('view', $order);
    
    return new OrderResource($order->load('user', 'items.product'));
}
    
    public function update(UpdateOrderRequest $request, $id)
{
    $order = Order::findOrFail($id);
    
    // Check xem user có quyền update không
    $this->authorize('update', $order);
    
    $order->update($request->validated());
    return new OrderResource($order->load('user', 'items.product'));
}
    
    public function destroy($id)
{
    $order = Order::findOrFail($id);
    
    // Check xem user có quyền delete không
    $this->authorize('delete', $order);
    
    $order->delete();
    return response()->json(['message' => 'Đơn hàng đã xóa']);
}
}