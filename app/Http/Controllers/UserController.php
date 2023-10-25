<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return UserResource::collection(User::all());;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if($user->nivel > $request->nivel || $user->nivel == 3) 
        {
            $request->validate([
                'nome' => 'required|string',
                'apelido' => 'required|string|unique:usuarios',
                'password' => 'required|string',
                'nivel' => 'required', //usuarios de nivel 2 nao podem criar usuarios de nivel 3
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
        return response()->json('O usuário autenticado não tem permissão para criar o usuario informado.', 401);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return UserResource::make(User::find($id));
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

        if(($user->nivel > $usuario->nivel && $user->nivel > $request->nivel) || $user->nivel == 3) 
        {
            $request->validate([
                'nome' => 'string',
                'apelido' => 'string|unique:usuarios,apelido,' . $id,
                'password' => 'string',
            ]);    
    
            $usuario->nome = $request->input('nome');
            $usuario->apelido = $request->input('apelido');
            $usuario->password = $request->input('password');
            $usuario->nivel = $request->input('nivel');
            $usuario->save();
            return UserResource::make($usuario);
        }
        return response()->json('O usuário autenticado não tem permissão para editar o usuario informado.', 401);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $usuario = User::find($id);
        
        if($user->nivel > $usuario->nivel || $user->nivel == 3) 
            User::find($id)->delete();
        
        return response()->json('Usuário removido com sucesso.');
    }
}
