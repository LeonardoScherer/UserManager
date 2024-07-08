<?php
namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function softDelete(int $userId): bool
    {
        $user = User::find($userId);
        if ($user) {
            return $user->delete();
        }
        return false;
    }

    public function update(int $id, array $data): bool
    {
        $user = User::find($id);
        if (!$user) {
            return false;
        }

        return $user->update($data);
    }

    public function find(int $id): ?User
    {
        return User::find($id);
    }
}
