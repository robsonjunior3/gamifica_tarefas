<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TarefaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GestaoTarefasController;
use App\Http\Controllers\RankingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    $user = Auth::user();
    return response()->json($user, 200);
    // return $request->user();
});



Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('tarefas', TarefaController::class);
    Route::resource('usuarios', UserController::class);
    
    Route::put('associar-tarefa/{tarefa_id}', [GestaoTarefasController::class, 'associarseTarefa']);
    Route::put('concluir-tarefa/{tarefa_id}', [GestaoTarefasController::class, 'concluirTarefa']);
    Route::put('desassociar-tarefa/{tarefa_id}', [GestaoTarefasController::class, 'desassociarTarefa']);
    
    Route::get('ranking', [RankingController::class, 'getTarefas']);
    Route::post('logout', [AuthController::class, 'logout']);
});