<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Illuminate\Http\Response;

class UserController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function createUser(UserRequest $request)
    {
        try {
            $validated = $request->validated();
            $user = $this->userService->createUserAndCache($validated);
            return response()->json($user, 201);
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }

    public function getAllUsers()
    {
        $users = $this->userService->getAllUsers();
        return response()->json($users, 200);
    }
}
