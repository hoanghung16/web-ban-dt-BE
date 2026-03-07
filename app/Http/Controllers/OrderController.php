<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index() { return response()->json(\App\Models\Order::all()); }
    
    public function store(Request $request) {
        $validated = $request->validate([
            'userid' => 'required|exists:users,id',
            'orderdate' => 'required|date',
            'status' => 'required|string',
            'paymentstatus' => 'required|string',
            'totalprice' => 'required|numeric',
            'shipname' => 'required|string',
            'shipaddress' => 'required|string',
            'shipphone' => 'required|string'
        ]);
        return response()->json(\App\Models\Order::create($validated), 201);
    }

    public function show($id) { return response()->json(\App\Models\Order::findOrFail($id)); }

    public function update(Request $request, $id) {
        $order = \App\Models\Order::findOrFail($id);
        $order->update($request->all());
        return response()->json($order);
    }

    public function destroy($id) {
        \App\Models\Order::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
