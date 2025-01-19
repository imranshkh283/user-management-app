<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{

    public function createUser(array $user)
    {
        return User::create($user);
    }

    public function getUserByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public function getUserByPhone(string $phone)
    {
        return User::where('phone', $phone)->first();
    }
}
