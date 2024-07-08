<?php

namespace App\Repositories;

use App\Models\Address;
use App\Repositories\Interfaces\AddressRepositoryInterface;

class AddressRepository implements AddressRepositoryInterface
{
    public function create(array $data): Address
    {
        return Address::create($data);
    }

    public function findByUserId(int $userId)
    {
        return Address::where('user_id', $userId)->get();
    }
}
