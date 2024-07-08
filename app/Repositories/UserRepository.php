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
}
