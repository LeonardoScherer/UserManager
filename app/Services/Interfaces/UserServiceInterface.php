<?php

namespace App\Services\Interfaces;

interface UserServiceInterface
{
    public function softDeleteUser(int $userId): bool;
}
