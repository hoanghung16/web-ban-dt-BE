<?php
namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Inventory;
use App\Http\Requests\StoreOrderItemRequest;
use App\Http\Resources\OrderItemResource;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index()
    {
        return OrderItemResource::collection(OrderItem::with('product')->get());
    }

    public function store(StoreOrderItemRequest $request)
    {
        $validated = $request->validated();
        $orderItem = OrderItem::create($validated);

        $inventory = Inventory::where('ProductId', $validated['productid'])->first();
        if ($inventory) {
            $inventory->QuantityInStock -= $validated['quantity'];
            $inventory->save();
        }

        return response()->json(new OrderItemResource($orderItem->load('product')), 201);
    }

    public function destroy($id)
    {
        $orderItem = OrderItem::findOrFail($id);
        
        $inventory = Inventory::where('ProductId', $orderItem->productid)->first();
        if ($inventory) {
            $inventory->QuantityInStock += $orderItem->quantity;
            $inventory->save();
        }

        $orderItem->delete();
        return response()->json(['message' => 'Đã xóa sản phẩm khỏi đơn hàng và hoàn lại tồn kho']);
    }
}