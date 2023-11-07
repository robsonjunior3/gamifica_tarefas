<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tarefa;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TarefaModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_tarefas_index_returns_ok_response(): void
    {
        $user = User::create([ // Criando o usuario para o caso do uso de um BD de teste
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 3
        ]);
        
        $this->assertNotNull($user, 'Usuário não encontrado no banco de dados');

        $response = $this->actingAs($user)->get('/api/tarefas');
        $response->assertStatus(200);
    }

    public function test_tarefas_get_returns_ok_response(): void
    {
        $user = User::create([ // Criando o usuario para o caso do uso de um BD de teste
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 3
        ]);

        Tarefa::create([
            'nome' => 'Minha tarefa',
            'descricao' => 'Descrição da minha tarefa',
            'pontuacao' => 150,
            'criador_id' => 1
        ]);
        
        $this->assertNotNull($user, 'Usuário não encontrado no banco de dados');

        $response = $this->actingAs($user)->get('/api/tarefas/1');
        $response->assertStatus(200);
    }
}
