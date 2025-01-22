<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function createUser(array $user);

    public function getAllUsers();

    public function getUserById(int $id);

    public function updateUser(int $id, array $data);

    public function deleteUser(int $id);

    public function cacheAllUsers();
}
