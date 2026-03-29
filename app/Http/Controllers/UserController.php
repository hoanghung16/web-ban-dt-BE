<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(\App\Http\Resources\UserResource::collection(\App\Models\User::all()));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'role' => 'string'
        ]);

        try {
            $validated['fullname'] = $validated['name']; // Gắn fullname bằng name
            unset($validated['name']); // Gỡ name ra trước khi đẩy vào create()
            $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);

            $user = \App\Models\User::create($validated);
            return response()->json(new \App\Http\Resources\UserResource($user), 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function show($id)
    {
        return response()->json(new \App\Http\Resources\UserResource(\App\Models\User::findOrFail($id)));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        // Hash password nếu có
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);
        return new UserResource($user);
    }

    public function destroy($id)
    {
        \App\Models\User::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
