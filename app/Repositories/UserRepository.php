<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{

    public function createUser(array $user)
    {
        return User::create($user);
    }

    public function login(array $users)
    {
        $user = User::where('email', $users['email'])->first();
        if (!$user || !\Hash::check($users['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        $token = $user->createToken('Test Token')->plainTextToken;
        return response()->json(['token' => $token]);
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

    public function getUserById(int $id)
    {
        return User::find($id);
    }

    public function updateUser(int $id, array $data)
    {
        $user = $this->getUserById($id);
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'];

        User::where('id', $id)->update($data);
    }

    public function deleteUser(int $id)
    {
        User::destroy($id);
    }
}
