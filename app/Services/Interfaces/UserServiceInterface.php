<?php

namespace App\Services\Interfaces;

use App\Models\User;

interface UserServiceInterface
{
    public function softDeleteUser(int $userId): bool;
    public function update(int $id, array $data): bool;
    public function getUserByid(int $id): ?User;
}
