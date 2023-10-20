<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Tarefa;
use Tests\TestCase;

class TarefaModelTest extends TestCase
{
    public function test_tarefas_index_returns_ok_response(): void
    {
        $response = $this->get('/api/tarefas');
        $response->assertStatus(200);
    }

    public function test_tarefas_get_returns_ok_response(): void
    {
        $tarefa = Tarefa::first();
        $response = $this->get('/api/tarefas/' . $tarefa->id);
        $response->assertStatus(200);
    }
}
