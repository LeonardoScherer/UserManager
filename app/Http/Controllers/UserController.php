<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

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

    public function update(Request $request, $id)
    {
        if (Auth::id() != $id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|string|max:20|unique:users,phone,' . $id,
            'cpf' => 'required|string|regex:/^[0-9]{3}\.?[0-9]{3}\.?[0-9]{3}\-?[0-9]{2}$/|unique:users,cpf,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        try {
            $result = $this->userService->update($id, $validatedData);

            if ($result) {
                $user = $this->userService->getUserById($id);
                return response()->json( $user, 200);
            } else {
                return response()->json(['error' => 'Erro ao atualizar usuário.'], 500);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Erro ao atualizar usuário.'], 500);
        }
    }

    public function show()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(['message' => 'Usuário não encontrado'], 404);
            }

            return response()->json($user, 200);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['message' => 'O token de acesso expirou'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['message' => 'Token inválido'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['message' => 'Token ausente'], 401);
        }
    }
}
