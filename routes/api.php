<?php

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
    return $request->user();
});

Route::resource('tarefas', TarefaController::class);
Route::resource('usuarios', UserController::class);

Route::put('associar-tarefa/{user_id}/{tarefa_id}', [GestaoTarefasController::class, 'associarseTarefa']);
Route::put('concluir-tarefa/{user_id}/{tarefa_id}', [GestaoTarefasController::class, 'concluirTarefa']);
Route::get('ranking', [RankingController::class, 'getTarefas']);

Route::post('/login', function(Request $request) {
    if(Auth::attempt(['apelido'=> $request->apelido,'password'=> $request->password])) {
        $user = Auth::user();
        $token = $user->createToken('jwt_gamifica_tarefas');
        return response()->json($token->plainTextToken, 200);
    }
    return response()->json('Usuário invalido', 200);
});

