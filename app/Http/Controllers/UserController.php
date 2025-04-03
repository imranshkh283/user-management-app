<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Http\Requests\UserRequest;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Response\ApiResponseClass;

use App\Events\UserRegister;

class UserController extends Controller
{
    private UserRepository $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(UserRequest $request)
    {
        try {
            $validated = $request->validated();
            $user = $this->userRepository->createUser($validated);

            event(new UserRegister($user));
            return response()->json($user, 201);
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }

    public function login(AuthRequest $request)
    {
        try {
            $validated = $request->validated();
            $user = $this->userRepository->login($validated);
            return response()->json($user, 200);
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }

    public function getAllUsers()
    {
        $users = $this->userRepository->getAllUsers();
        return ApiResponseClass::sendResponse($users, "All users fetched successfully", 200);
    }

    public function updateUser(UserUpdateRequest $request, int $id)
    {
        try {
            $validated = $request->validated();
            $user = $this->userRepository->updateUser($id, $validated);
            return response()->json($user, 200);
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }

    public function deleteUser(int $id)
    {
        $user = $this->userRepository->deleteUser($id);
        return response()->json($user, 200);
    }
}
