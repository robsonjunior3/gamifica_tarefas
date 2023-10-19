<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tarefa extends Model
{
    use HasFactory;

    protected $fillable = ['nome','descricao','pontuacao','criador_id','responsavel_id','concluida'];
}
