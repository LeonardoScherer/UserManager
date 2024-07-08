<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\UserServiceInterface;

class UserService implements UserServiceInterface
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function softDeleteUser(int $userId): bool
    {
        return $this->userRepository->softDelete($userId);
    }

    public function update(int $id, array $data): bool
    {
        return $this->userRepository->update($id, $data);
    }

    public function getUserById(int $userId): ?User
    {
        return $this->userRepository->find($userId);
    }
}
