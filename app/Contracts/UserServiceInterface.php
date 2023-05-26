<?php

namespace App\Contracts;

interface UserServiceInterface
{
    public function getUserByEmail($email);

    public function getAllUsers(): \Illuminate\Database\Eloquent\Collection;

    public function getUserById($id);

    public function createUser($data);

    public function updateUser($id, $data);

    public function deleteUser($id): bool;
}
