<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if($user->nivel > 1)
            return UserResource::collection(User::all());
        else 
            return response()->json('O usuário logado não tem permissão para verificar a lista de usuário', 401);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (($request->nivel >= 1 && $request->nivel <= 3) && (($user->nivel > $request->nivel) || $user->nivel == 3)) 
        {
            $request->validate([
                'nome' => 'required|string',
                'apelido' => 'required|string|unique:usuarios',
                'password' => 'required|string',
                'nivel' => 'required',
            ]);

            $usuario = new User([ 
                'nome' => $request->input('nome'),
                'apelido' => $request->input('apelido'),
                'password' => $request->input('password'),
                'nivel' => $request->input('nivel'),
            ]);

            $usuario->save();
            return UserResource::make($usuario);
        }
        return response()->json('O usuário autenticado não tem permissão para criar o usuario informado.', 403);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::user();
        if($user->nivel > 1)
            return UserResource::make(User::find($id));
        else 
            return response()->json('O usuário logado não tem permissão para verificar a lista de usuário', 401);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $usuario = User::find($id);
        
        if(!$usuario)
            return response()->json('Usuário não encontrado.', 404);

        if(($request->nivel >= 1 && $request->nivel <= 3) && 
            (($user->nivel > $usuario->nivel && $user->nivel > $request->nivel) || $user->nivel == 3))
        {
            $request->validate([
                'nome' => 'string',
                'apelido' => 'string|unique:usuarios,apelido,' . $id,
                'password' => 'string',
            ]);    
            
            $usuario->nome = $request->input('nome') ? $request->input('nome') : $usuario->nome;
            $usuario->apelido = $request->input('apelido') ? $request->input('apelido') : $usuario->apelido;
            $usuario->password = $request->input('password') ? $request->input('password') : $usuario->password;
            $usuario->nivel = $request->input('nivel') ? $request->input('nivel') : $usuario->nivel;

            $usuario->save();

            return UserResource::make($usuario);
        }
        return response()->json('O usuário autenticado não tem permissão para editar o usuario informado.', 403);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $usuario = User::find($id);
        
        if($usuario)
        {
            if($user->nivel > $usuario->nivel || $user->nivel == 3)
            {
                User::find($id)->delete();
                return response()->json('Usuário removido com sucesso.', 200);
            }
            return response()->json('Não foi possível remover o usuário informado.', 403);
        }
        return response()->json('Não foi possível remover o usuário informado.', 404);
        
    }
}