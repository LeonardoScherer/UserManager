<?php

namespace App\Services\Interfaces;

use App\Models\User;

interface AuthServiceInterface
{
    public function login(array $credentials): ?string;
    public function register(array $data): User;
}
