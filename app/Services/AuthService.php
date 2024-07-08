<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use App\Services\Interfaces\AuthServiceInterface;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

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

    public function login(array $credentials): ?string
    {
        try {
            if (!$user = $this->authRepository->findByCredentials($credentials)) {
                return null;
            }

            if (!$token = JWTAuth::fromUser($user)) {
                return null;
            }
        } catch (JWTException $e) {
            return null;
        }

        return $token;
    }
}
