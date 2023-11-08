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
        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 3
        ]);

        $response = $this->actingAs($user)->get('/api/tarefas');
        $response->assertStatus(200);
    }

    public function test_tarefas_show_returns_ok_response(): void
    {
        $user = User::create([
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

        $response = $this->actingAs($user)->get('/api/tarefas/1');
        $response->assertStatus(200);
    }

    // user 1 nao cria tarefa
    public function test_usuario_de_nivel_1_nao_pode_criar_tarefa(): void
    {
        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 1
        ]);

        $response = $this->actingAs($user)->post('/api/tarefas', [
            'nome' => 'Minha tarefa',
            'descricao' => 'Descrição da minha tarefa',
            'pontuacao' => 150,
            'criador_id' => 1
        ]);

        $response->assertStatus(403);
    }

    // user 2 cria tarefa
    public function test_usuario_de_nivel_2_pode_criar_tarefa(): void
    {
        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 2
        ]);

        $response = $this->actingAs($user)->post('/api/tarefas', [
            'nome' => 'Minha tarefa',
            'descricao' => 'Descrição da minha tarefa',
            'pontuacao' => 150,
            'criador_id' => 1
        ]);

        $response->assertStatus(201);
    }
    
    // user 3 cria tarefa
    public function test_usuario_de_nivel_3_pode_criar_tarefa(): void
    {
        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 3
        ]);

        $response = $this->actingAs($user)->post('/api/tarefas', [
            'nome' => 'Minha tarefa',
            'descricao' => 'Descrição da minha tarefa',
            'pontuacao' => 150,
            'criador_id' => 1
        ]);

        $response->assertStatus(201);
    }
    
    // user 1 nao edita tarefa
    public function test_usuario_de_nivel_1_nao_pode_editar_tarefa(): void
    {
        User::create([
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 2
        ]);

        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 1
        ]);
        
        Tarefa::create([
            'nome' => 'Minha tarefa',
            'descricao' => 'Descrição da minha tarefa',
            'pontuacao' => 150,
            'criador_id' => 1
        ]);

        $this->actingAs($user);

        $response = $this->patch('/api/tarefas/1', [
            'nome' => '(edit) Minha tarefa',
            'descricao' => '(edit) Descrição da minha tarefa',
            'pontuacao' => 50,
            'criador_id' => 1
        ]);

        $response->assertStatus(403);
    }

    // user 2 so edita a tarefa que ele mesmo criou
    public function test_usuario_de_nivel_2_pode_editar_a_propria_tarefa(): void
    {
        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 2
        ]);
        
        Tarefa::create([
            'nome' => 'Minha tarefa',
            'descricao' => 'Descrição da minha tarefa',
            'pontuacao' => 150,
            'criador_id' => 1
        ]);

        $this->actingAs($user);

        $response = $this->patch('/api/tarefas/1', [
            'nome' => '(edit) Minha tarefa',
            'descricao' => '(edit) Descrição da minha tarefa',
            'pontuacao' => 50,
            'criador_id' => 1
        ]);

        $response->assertStatus(200);
    }

    // user 2 nao edita tarefas criadas por outros
    public function test_usuario_de_nivel_2_nao_pode_editar_tarefas_criadas_por_outros_usuarios(): void
    {
        User::create([
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 2
        ]);

        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 2
        ]);
        
        Tarefa::create([
            'nome' => 'Minha tarefa',
            'descricao' => 'Descrição da minha tarefa',
            'pontuacao' => 150,
            'criador_id' => 1
        ]);

        $this->actingAs($user);

        $response = $this->patch('/api/tarefas/1', [
            'nome' => '(edit) Minha tarefa',
            'descricao' => '(edit) Descrição da minha tarefa',
            'pontuacao' => 50,
            'criador_id' => 1
        ]);

        $response->assertStatus(403);
    }

    // user 3 edita a propria tarefa
    public function test_usuario_de_nivel_3_pode_editar_a_propria_tarefa(): void
    {
        $user = User::create([
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

        $this->actingAs($user);

        $response = $this->patch('/api/tarefas/1', [
            'nome' => '(edit) Minha tarefa',
            'descricao' => '(edit) Descrição da minha tarefa',
            'pontuacao' => 50,
            'criador_id' => 1
        ]);

        $response->assertStatus(200);
    }
    
    // user 3 edita qqr tarefa
    public function test_usuario_de_nivel_3_pode_editar_tarefas_criadas_por_outros_usuarios(): void
    {
        User::create([
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 2
        ]);

        $user = User::create([
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

        $this->actingAs($user);

        $response = $this->patch('/api/tarefas/1', [
            'nome' => '(edit) Minha tarefa',
            'descricao' => '(edit) Descrição da minha tarefa',
            'pontuacao' => 50,
            'criador_id' => 1
        ]);

        $response->assertStatus(200);
    }

    // user 1 nao remove tarefa
    public function test_usuario_de_nivel_1_nao_pode_remover_tarefa(): void
    {
        User::create([
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 2
        ]);

        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 1
        ]);
        
        Tarefa::create([
            'nome' => 'Minha tarefa',
            'descricao' => 'Descrição da minha tarefa',
            'pontuacao' => 150,
            'criador_id' => 1
        ]);

        $this->actingAs($user);

        $response = $this->delete('/api/tarefas/1');

        $response->assertStatus(403);
    }

    // user 2 remove a propria tarefa
    public function test_usuario_de_nivel_2_pode_remover_a_propria_tarefa(): void
    {
        $user = User::create([
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 2
        ]);

        Tarefa::create([
            'nome' => 'Minha tarefa',
            'descricao' => 'Descrição da minha tarefa',
            'pontuacao' => 150,
            'criador_id' => 1
        ]);

        $this->actingAs($user);

        $response = $this->delete('/api/tarefas/1');

        $response->assertStatus(200);
    }

    // user 2 nao remove a terefa de outros usuarios
    public function test_usuario_de_nivel_2_nao_pode_remover_tarefas_de_outros_usuarios(): void
    {
        User::create([
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 2
        ]);

        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 2
        ]);
        
        Tarefa::create([
            'nome' => 'Minha tarefa',
            'descricao' => 'Descrição da minha tarefa',
            'pontuacao' => 150,
            'criador_id' => 1
        ]);

        $this->actingAs($user);

        $response = $this->delete('/api/tarefas/1');

        $response->assertStatus(403);
    }

    // user 3 remove a propria tarefa
    public function test_usuario_de_nivel_3_pode_remover_a_propria_tarefa(): void
    {
        $user = User::create([
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 3
        ]);

        Tarefa::create([
            'nome' => 'Minha tarefa',
            'descricao' => 'Descrição da minha tarefa',
            'pontuacao' => 150,
            'criador_id' => 1
        ]);

        $this->actingAs($user);

        $response = $this->delete('/api/tarefas/1');

        $response->assertStatus(200);
    }

    // user 3 remove a tarefa de outros usuarios
    public function test_usuario_de_nivel_3_pode_remover_qualquer_tarefa(): void
    {
        User::create([
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 2
        ]);

        $user = User::create([
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

        $this->actingAs($user);

        $response = $this->delete('/api/tarefas/1');

        $response->assertStatus(200);
    }
}
