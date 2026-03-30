<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Http\Resources\InventoryResource;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateInventoryRequest;

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

    public function update(UpdateInventoryRequest $request, Inventory $inventory)
    {
        $inventory->update($request->validated());
        return new InventoryResource($inventory);
    }
}
