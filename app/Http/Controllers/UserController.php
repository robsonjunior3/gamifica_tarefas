<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
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
        $request->validate([
            'nome' => 'required|string',
            'apelido' => 'required|string|unique:usuarios',
            'password' => 'required|string',
        ]);

        $usuario = new User([ 
            'nome' => $request->input('nome'),
            'apelido' => $request->input('apelido'),
            'password' => Hash::make($request->input('password')),
        ]);

        $usuario->save();
        return UserResource::make($usuario);
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
        $request->validate([
            'nome' => 'required|string',
            'apelido' => 'required|string|unique:usuarios,apelido,' . $id,
            'password' => 'string',
        ]);
        
        $usuario = User::find($id);

        if(!$usuario){
            return response()->json('Usuário não encontrado.', 404);
            // return redirect('/usuarios')->with('error', 'Usuário não encontrado.');
        }

        $usuario->nome = $request->input('nome');
        $usuario->apelido = $request->input('apelido');
        $usuario->password = Hash::make($request->input('password'));
        $usuario->save();
        return UserResource::make($usuario);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return response()->noContent();
    }
}
