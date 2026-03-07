<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index() { return response()->json(\App\Models\Inventory::all()); }
    
    public function store(Request $request) {
        $validated = $request->validate(['ProductId' => 'required|exists:products,id', 'QuantityInStock' => 'required|integer']);
        return response()->json(\App\Models\Inventory::create($validated), 201);
    }

    public function show($id) { return response()->json(\App\Models\Inventory::where('ProductId', $id)->firstOrFail()); }

    public function update(Request $request, $id) {
        $inv = \App\Models\Inventory::where('ProductId', $id)->firstOrFail();
        $inv->update($request->all());
        return response()->json($inv);
    }

    public function destroy($id) {
        \App\Models\Inventory::where('ProductId', $id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
