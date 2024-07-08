<?php

namespace App\Services;

use App\Repositories\Interfaces\AddressRepositoryInterface;
use App\Services\Interfaces\AddressServiceInterface;
use App\Repositories\Interfaces\AuthRepositoryInterface;


class AddressService implements AddressServiceInterface
{
    protected $addressRepository;
    protected $authRepository;

    public function __construct(
        AddressRepositoryInterface $addressRepository,
        AuthRepositoryInterface $authRepository
    )
    {
        $this->addressRepository = $addressRepository;
        $this->authRepository = $authRepository;
    }

    public function create(array $data)
    {
        $userId = $this->authRepository->getCurrentUserById();

        if ($userId != $data['user_id']) {
            throw new \InvalidArgumentException('Você não tem permissão para adicionar endereço para este usuário.');
        }

        return $this->addressRepository->create($data);
    }

    public function getByUserId(int $userId)
    {
        return $this->addressRepository->findByUserId($userId);
    }
}
