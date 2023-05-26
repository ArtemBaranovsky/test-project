<?php

namespace App\Services;

use App\Contracts\UserServiceInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserService implements UserServiceInterface
{

    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function getUserByEmail($email)
    {
        return $this->userRepository->model()::where('email', $email)->first();
    }

    public function getAllUsers(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->userRepository->getAll();

    }

    public function getUserById($id)
    {
        return $this->userRepository->find($id);
    }

    public function createUser($data)
    {
        return $this->userRepository->create($data);
    }

    public function updateUser($id, $data)
    {
        return $this->userRepository->update($id, $data);
    }

    public function deleteUser($id): bool
    {
        return $this->userRepository->delete($id);
    }
}
