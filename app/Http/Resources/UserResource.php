<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = Auth::user();
        return $user->nivel == 3 ?
        [
            'id' => $this->id,
            'nome' => $this->nome,
            'apelido' => $this->apelido,
            'password' => $this->password,
            'pontuacao' => $this->pontuacao,
            'nivel' => $this->nivel
        ]
        :
        [
            'id' => $this->id,
            'nome' => $this->nome,
            'pontuacao' => $this->pontuacao,
            'nivel' => $this->nivel
        ]
        ;
    }
}
