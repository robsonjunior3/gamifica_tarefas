<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class RankingController extends Controller
{
    /**
     * 
     */
    public function getTarefas()
    {
        return UserResource::collection(User::all()->sortByDesc('pontuacao'));;
    }
}
