<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function destroy(Request $request)
    {
        $userId = $request->input('user_id');

        $success = $this->userService->softDeleteUser($userId);

        if ($success) {
            return response()->json(['message' => 'Usuário deletado com sucesso'], 200);
        } else {
            return response()->json(['message' => 'Falha ao deletar usuário'], 500);
        }
    }
}
