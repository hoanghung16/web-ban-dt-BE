<?php
namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Http\Resources\InventoryResource;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        return InventoryResource::collection(Inventory::with('product')->get());
    }

    public function show($id)
    {
        $inventory = Inventory::where('ProductId', $id)->firstOrFail();
        return new InventoryResource($inventory);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['QuantityInStock' => 'required|integer|min:0']);
        
        $inventory = Inventory::where('ProductId', $id)->firstOrFail();
        $inventory->update(['QuantityInStock' => $request->QuantityInStock]);
        
        return new InventoryResource($inventory);
    }
}