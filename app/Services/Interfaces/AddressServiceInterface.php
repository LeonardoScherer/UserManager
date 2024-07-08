<?php

namespace App\Services\Interfaces;

interface AddressServiceInterface
{
    public function create(array $data);
    public function getByUserId(int $userId);
}
