<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Services\InventoryService;
use App\Http\Resources\InventoryResource;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateInventoryRequest;

class InventoryController extends Controller
{
    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index()
    {
        $inventory = $this->inventoryService->getAll();
        return InventoryResource::collection($inventory);
    }

    public function show($id)
    {
        $inventory = $this->inventoryService->getByProductId($id);
        return response()->json(['data' => new InventoryResource($inventory)]);
    }

    public function update(UpdateInventoryRequest $request, $id)
    {
        $inventory = $this->inventoryService->update($id, $request->validated());
        return response()->json(['data' => new InventoryResource($inventory)]);
    }
}
