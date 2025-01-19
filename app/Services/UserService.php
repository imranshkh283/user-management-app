<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Cache;

class UserService
{
    protected $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUserAndCache(array $data)
    {
        $data['password'] = bcrypt($data['password']);
        $this->userRepository->createUser($data);

        $users = $this->userRepository->getAllUsers();

        return $users;
    }

    public function getAllUsers()
    {
        $users = $this->userRepository->getAllUsers();

        return $users;
    }

    public function updateUser(int $id, array $data)
    {
        if (!$this->userRepository->getUserById($id)) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $this->userRepository->updateUser($id, $data);
        $users = $this->userRepository->getAllUsers();

        return $users;
    }

    public function deleteUser(int $id)
    {
        if (!$this->userRepository->getUserById($id)) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $this->userRepository->deleteUser($id);
        $users = $this->userRepository->getAllUsers();

        return $users;
    }
}
