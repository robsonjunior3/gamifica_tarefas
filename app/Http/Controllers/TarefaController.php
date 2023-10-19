<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use App\Http\Requests\StoretarefaRequest;
use App\Http\Requests\UpdatetarefaRequest;
use App\Http\Resources\TarefasResource;

use Illuminate\Http\Request;

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
    public function store(StoretarefaRequest $request)
    {
        $tarefa = Tarefa::create($request->validated());
        return TarefasResource::make($tarefa);
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
    public function update(UpdatetarefaRequest $request, tarefa $tarefa)
    {
        $tarefa->update($request->validated());
        return TarefasResource::make($tarefa);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(tarefa $tarefa)
    {
        $tarefa->delete();
        return response()->noContent();
    }
}
