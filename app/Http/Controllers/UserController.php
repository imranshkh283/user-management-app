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

    public function createUser(UserRequest $request, Response $response)
    {
        try {
            $validated = $request->validated();
            $user = $this->userService->createUser($validated);
            return response()->json($user, $response->created());
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }
}
