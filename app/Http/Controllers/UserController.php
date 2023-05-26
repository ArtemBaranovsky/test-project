<?php

namespace App\Http\Controllers;

use App\Contracts\UserServiceInterface;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{

    public function __construct(protected UserServiceInterface $userService)
    {
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        $users = $this->userService->getAllUsers();

        return response()->json($users, 200);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json($user, 200);
    }

    public function store(UserRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();

        $user = $this->userService->createUser($data);

        return response()->json($user, 201);
    }

    public function update(UserRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();

        $user = $this->userService->updateUser($id, $data);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json($user, 200);
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result = $this->userService->deleteUser($id);

        if (!$result) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
