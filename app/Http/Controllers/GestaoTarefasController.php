<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tarefa;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\TarefasResource;

class GestaoTarefasController extends Controller
{
    /**
     * Associa um usuario a uma tarefa 
     */
    public function associarseTarefa(int $user_id, int $tarefa_id)
    {
        $usuario = User::find($user_id);
        $tarefa = Tarefa::find($tarefa_id);

        $tarefa->responsavel_id = $usuario->id;

        $tarefa->save();

        return TarefasResource::make($tarefa);
    }

    /**
     * Marca uma tarefa como concluida e atualiza os pontos do usuario
     */
    public function concluirTarefa(int $user_id, int $tarefa_id)
    {
        $usuario = User::find($user_id);
        $tarefa = Tarefa::find($tarefa_id);

        if($usuario->id == $tarefa->responsavel_id && !$tarefa->concluida)
        {
            $tarefa->concluida = true;
            
            $usuario->pontuacao += $tarefa->pontuacao;
            
            $usuario->save();
            $tarefa->save();

            return response()->json([UserResource::make($usuario), TarefasResource::make($tarefa)], 201);
        }
        return response()->json('O usuário não pode concluir a tarefa informada.', 404);
    }
}
