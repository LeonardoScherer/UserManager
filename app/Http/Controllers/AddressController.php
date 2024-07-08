<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\AddressServiceInterface;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    protected $addressService;

    public function __construct(AddressServiceInterface $addressService)
    {
        $this->addressService = $addressService;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'street' => 'required|string|max:255',
            'number' => 'required|string|max:20',
            'neighborhood' => 'required|string|max:255',
            'complement' => 'nullable|string|max:255',
            'cep' => 'required|string|max:10',
        ]);

        try {
            $address = $this->addressService->create($data);

            return response()->json($address, 201);
        } catch (\InvalidArgumentException $th) {
            return response()->json(['message' => 'Erro ao criar endereço.', 'error' => $th->getMessage()], 500);
        }
    }

}
