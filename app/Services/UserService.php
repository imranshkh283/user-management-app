<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

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
}
