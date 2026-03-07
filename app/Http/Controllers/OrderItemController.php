<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index() { return response()->json(\App\Models\OrderItem::all()); }
    
    public function store(Request $request) {
        $validated = $request->validate([
            'orderid' => 'required|exists:orders,id',
            'productid' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'unitprice' => 'required|numeric'
        ]);
        return response()->json(\App\Models\OrderItem::create($validated), 201);
    }

    public function show($id) { return response()->json(\App\Models\OrderItem::findOrFail($id)); }

    public function update(Request $request, $id) {
        $item = \App\Models\OrderItem::findOrFail($id);
        $item->update($request->all());
        return response()->json($item);
    }

    public function destroy($id) {
        \App\Models\OrderItem::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
