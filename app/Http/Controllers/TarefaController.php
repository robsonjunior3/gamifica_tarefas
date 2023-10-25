<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TarefasResource;

use App\Http\Requests\StoretarefaRequest;
use App\Http\Requests\UpdatetarefaRequest;

class TarefaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TarefasResource::collection(Tarefa::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if($user->nivel > 1){
            $request->validate([
                'nome' => 'required|string|max:255',
                'descricao' => 'required|string',
                'pontuacao' => 'required|integer',
                'criador_id' => 'exists:usuarios,id',
                'responsavel_id' => 'nullable|exists:usuarios,id',
                'concluida' => 'boolean',
            ]);

            $tarefa = new Tarefa([ 
                'nome' => $request->input('nome'),
                'descricao' => $request->input('descricao'),
                'pontuacao' => $request->input('pontuacao'),
                'criador_id' => $user->id,
            ]);

            $tarefa->save();
            return TarefasResource::make($tarefa);
        }
        return response()->json('O usuário autenticado não tem permissão para cadastrar uma nova tarefa.', 401);
    }

    /**
     * Display the specified resource.
     */
    public function show(tarefa $tarefa)
    {
        return TarefasResource::make($tarefa);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tarefa $tarefa)
    {
        // user deve ser adm ou ser o criador da tarefa
        $user = Auth::user();
        if($user->nivel == 3 || $user->id == $tarefa->criador_id){
            $request->validate([
                'nome' => 'string|max:255',
                'descricao' => 'string',
                'pontuacao' => 'integer',
                'criador_id' => 'exists:usuarios,id',
                'responsavel_id' => 'nullable|exists:usuarios,id',
                'concluida' => 'boolean',
            ]);
            $tarefa->nome = $request->input('nome') ? $request->input('nome') : $tarefa->nome;
            $tarefa->descricao = $request->input('descricao') ? $request->input('descricao') : $tarefa->descricao;
            $tarefa->pontuacao = $request->input('pontuacao') ? $request->input('pontuacao') : $tarefa->pontuacao;
            $tarefa->criador_id = $request->input('criador_id') ? $request->input('criador_id') : $tarefa->criador_id;
            $tarefa->responsavel_id = $request->input('responsavel_id') ? $request->input('responsavel_id') : $tarefa->responsavel_id;
            $tarefa->concluida = $request->input('concluida') ? $request->input('concluida') : $tarefa->concluida;
            
            $tarefa->save();

            return TarefasResource::make($tarefa);
        }
        return response()->json('O usuário autenticado não tem permissão para alterar a tarefa.', 401);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(tarefa $tarefa)
    {
        $user = Auth::user();
        if($user->nivel == 3 || $user->id == $tarefa->criador_id){
            $tarefa->delete();
            return response()->json('Tarefa removida com sucesso', 200);
        }
        return response()->json('O usuário autenticado não tem permissão para alterar a tarefa.', 401);
    }
}
