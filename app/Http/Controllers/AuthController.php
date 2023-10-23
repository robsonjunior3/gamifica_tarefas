<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Fazer o login na aplicacao
     * Retorna um token de autenticacao
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['apelido'=> $request->apelido,'password'=> $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('jwt_gamifica_tarefas');
            return response()->json($token->plainTextToken, 200);
        }
        return response()->json('Usuário invalido', 200);
    }

    /**
     * Fazer o logout na aplicacao
     * Desativa o token que o usuario esta utilizando 
     */
    public function logout()
    {
        $user = Auth::user();
        $user->tokens()->delete();
        return response()->json('Logout realizado com sucesso.', 200);
    }
}
