<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tarefa;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TarefasResource;

class GestaoTarefasController extends Controller
{
    /**
     * Associa um usuario a uma tarefa 
     */
    public function associarseTarefa(int $tarefa_id)
    {
        // $usuario = User::find($user_id);
        $usuario = Auth::user();
        $tarefa = Tarefa::find($tarefa_id);

        if($tarefa)
        {
            if($tarefa->responsavel_id == null && !$tarefa->concluida)
            {
                $tarefa->responsavel_id = $usuario->id;
        
                $tarefa->save();
        
                return TarefasResource::make($tarefa);
            }
        }
        return response()->json('O usuário não pode se vincular à tarefa informada', 403);
    }

    /**
     * Marca uma tarefa como concluida e atualiza os pontos do usuario
     */
    public function concluirTarefa(int $tarefa_id)
    {
        // $usuario = User::find($user_id);
        $usuario = Auth::user();
        $tarefa = Tarefa::find($tarefa_id);

        if($usuario->id == $tarefa->responsavel_id && !$tarefa->concluida)
        {
            $tarefa->concluida = true;
            
            $usuario->pontuacao += $tarefa->pontuacao;
            
            $usuario->save();
            $tarefa->save();

            return response()->json([UserResource::make($usuario), TarefasResource::make($tarefa)], 200);
        }
        return response()->json('O usuário não pode concluir a tarefa informada.', 403);
    }

    public function desassociarTarefa(int $tarefa_id){
        $usuario = Auth::user();
        $tarefa = Tarefa::find($tarefa_id);

        if(!$tarefa->concluida)
        {
            if($usuario->id == $tarefa->responsavel_id)
            {
                $tarefa->responsavel_id = NULL;
    
                $tarefa->save();
                return response()->json(TarefasResource::make($tarefa), 201);
            }
            return response()->json('O usuário não pode se desasociar da tarefa informada.', 403);
        }
        return response()->json('A tarefa já foi concluída. O usuário não pode se desasociar da tarefa informada.', 403);
    }
}