<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use App\Services\Interfaces\AuthServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return $this->authRepository->create($data);
    }
}
