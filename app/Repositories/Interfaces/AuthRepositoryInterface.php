<?php
namespace App\Repositories\Interfaces;

use App\Models\User;

interface AuthRepositoryInterface
{
    public function create(array $data): User;
    public function findByCredentials(array $credentials);
    public function getCurrentUserById(): ?int;
}
