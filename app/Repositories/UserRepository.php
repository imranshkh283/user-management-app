<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Cache;
use App\Models\User;

class UserRepository
{

    public function createUser(array $user)
    {
        return User::create($user);
    }

    public function cacheAllUsers()
    {
        Cache::remember('all-users', now()->addMinutes(5), function () {
            return User::all();
        });
    }

    public function getAllUsers()
    {
        if (Cache::has('all-users')) {
            return Cache::get('all-users');
        } else {
            $this->cacheAllUsers();
        }
    }
}
