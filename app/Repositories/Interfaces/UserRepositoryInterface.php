<?php
namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserRepositoryInterface
{
    public function softDelete(int $userId): bool;
    public function update(int $id, array $data): bool;
    public function find(int $id): ?User;
}
