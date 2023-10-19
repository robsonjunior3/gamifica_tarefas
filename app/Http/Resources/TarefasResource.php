<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TarefasResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'descricao' => $this->descricao,
            'pontuacao' => $this->pontuacao,
            'criador_id' => $this->criador_id,
            'responsavel_id' => $this->responsavel_id,
            'concluida' => (bool) $this->concluida
        ];
    }
}
