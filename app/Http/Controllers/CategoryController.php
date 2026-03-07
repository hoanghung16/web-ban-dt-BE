<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() { return response()->json(\App\Models\Category::all()); }
    
    public function store(Request $request) {
        $validated = $request->validate(['name' => 'required|string', 'slug' => 'required|string']);
        return response()->json(\App\Models\Category::create($validated), 201);
    }

    public function show($id) { return response()->json(\App\Models\Category::findOrFail($id)); }

    public function update(Request $request, $id) {
        $cat = \App\Models\Category::findOrFail($id);
        $cat->update($request->all());
        return response()->json($cat);
    }

    public function destroy($id) {
        \App\Models\Category::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
