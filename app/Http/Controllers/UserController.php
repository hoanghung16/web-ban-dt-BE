<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() { 
        return response()->json(\App\Http\Resources\UserResource::collection(\App\Models\User::all())); 
    }
    
    public function store(Request $request) {
        $validated = $request->validate(['fullname' => 'required|string', 'email' => 'required|email|unique:users', 'password' => 'required', 'role' => 'string']);
        $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        return response()->json(new \App\Http\Resources\UserResource(\App\Models\User::create($validated)), 201);
    }

    public function show($id) { 
        return response()->json(new \App\Http\Resources\UserResource(\App\Models\User::findOrFail($id))); 
    }

    public function update(Request $request, $id) {
        $user = \App\Models\User::findOrFail($id);
        $data = $request->all();
        if(isset($data['password'])) $data['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);
        $user->update($data);
        return response()->json($user);
    }

    public function destroy($id) {
        \App\Models\User::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
