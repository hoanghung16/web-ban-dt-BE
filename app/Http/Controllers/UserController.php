<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\UserService;
use App\Enums\UserRole;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        // Support both 'limit' and 'per_page' parameters
        $perPage = min($request->get('limit') ?? $request->get('per_page', 100), 100);
        $page = $request->get('page', 1);
        $users = $this->userService->getAllUsers($perPage);
        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->create($request->validated());
        return response()->json(['data' => new UserResource($user)], 201);
    }

    public function show($id)
    {
        $user = $this->userService->getById($id);
        return response()->json(['data' => new UserResource($user)]);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = $this->userService->update($id, $request->validated());
        return response()->json(['data' => new UserResource($user)]);
    }

    public function destroy($id)
    {
        $this->userService->delete($id);
        return response()->json(['message' => 'Deleted']);
    }
}
