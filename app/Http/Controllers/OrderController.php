<?php
namespace App\Http\Controllers;
use App\Models\Order;
use App\Services\OrderService;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $userId = $request->get('userid');
        $status = $request->get('status');
        $perPage = min($request->get('per_page', 20), 100);
        $page = $request->get('page', 1);
        
        if ($userId) {
            $orders = $this->orderService->getByUserId($userId);
        } elseif ($status) {
            $orders = $this->orderService->getByStatus($status);
        } else {
            $orders = $this->orderService->getAllOrders($page, $perPage);
        }
        
        return OrderResource::collection($orders);
    }
    
    public function store(StoreOrderRequest $request)
    {
        $order = $this->orderService->create($request->validated());
        return response()->json(new OrderResource($order), 201);
    }
    
    public function show($id)
    {
        $order = $this->orderService->getById($id);
        return new OrderResource($order);
    }
    
    public function update(UpdateOrderRequest $request, $id)
    {
        $order = $this->orderService->update($id, $request->validated());
        return new OrderResource($order);
    }
    
    public function destroy($id)
    {
        $this->orderService->delete($id);
        return response()->json(['message' => 'Đơn hàng đã xóa']);
    }
}