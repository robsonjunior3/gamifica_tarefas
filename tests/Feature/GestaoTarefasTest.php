<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tarefa;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GestaoTarefasTest extends TestCase
{
    use RefreshDatabase;

    // associar a uma tarefa ja concluida
    public function test_associar_a_uma_tarefa_ja_concluida_gera_erro(){
        User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 2
        ]);
        
        Tarefa::create([
            "nome"=> "nome da tarefa",
            "descricao"=> "descricao da tarefa",
            "pontuacao"=> 150,
            "concluida"=> true,
            "criador_id"=> 1
        ]);

        $user = User::create([
            'nome' => 'Usuario um',
            'apelido' => 'usuario1',
            'password' => 'password',
            'nivel' => 1
        ]);

        $this->actingAs($user);

        $response = $this->put('/api/associar-tarefa/1');

        $response->assertStatus(403);
    }

    // associar a uma tarefa nao concluida
    public function test_associar_a_uma_tarefa_nao_concluida(){
        User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 2
        ]);
        
        Tarefa::create([
            "nome"=> "nome da tarefa",
            "descricao"=> "descricao da tarefa",
            "pontuacao"=> 150,
            "concluida"=> false,
            "criador_id"=> 1
        ]);

        $user = User::create([
            'nome' => 'Usuario um',
            'apelido' => 'usuario1',
            'password' => 'password',
            'nivel' => 1
        ]);

        $this->actingAs($user);

        $response = $this->put('/api/associar-tarefa/1');

        $response->assertStatus(200);
    }
    
    // associar a uma tarefa que ja esta associada a outro user
    public function test_associar_a_uma_tarefa_que_ja_esta_associada_a_outro_usuario_gera_erro(){
        User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 2
        ]);
        
        User::create([
            'nome' => 'Usuario um',
            'apelido' => 'usuario1',
            'password' => 'password',
            'nivel' => 1
        ]);

        Tarefa::create([
            "nome"=> "nome da tarefa",
            "descricao"=> "descricao da tarefa",
            "pontuacao"=> 150,
            "concluida"=> false,
            "criador_id"=> 1,
            "responsavel_id"=> 2
        ]);

        $user = User::create([
            'nome' => 'Usuario dois',
            'apelido' => 'usuario2',
            'password' => 'password',
            'nivel' => 1
        ]);

        $this->actingAs($user);

        $response = $this->put('/api/associar-tarefa/1');

        $response->assertStatus(403);
    }

    // concluir uma tarefa que nao me peretence
    public function test_concluir_uma_tarefa_que_nao_me_pertence_gera_erro(){
        User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 2
        ]);
        
        User::create([
            'nome' => 'Usuario um',
            'apelido' => 'usuario1',
            'password' => 'password',
            'nivel' => 1
        ]);

        Tarefa::create([
            "nome"=> "nome da tarefa",
            "descricao"=> "descricao da tarefa",
            "pontuacao"=> 150,
            "concluida"=> false,
            "criador_id"=> 1,
            "responsavel_id"=> 2
        ]);

        $user = User::create([
            'nome' => 'Usuario dois',
            'apelido' => 'usuario2',
            'password' => 'password',
            'nivel' => 1
        ]);

        $this->actingAs($user);

        $response = $this->put('/api/concluir-tarefa/1');

        $response->assertStatus(403);
    }

    // concluir uma tarefa que me pertence
    public function test_concluir_uma_tarefa_que_me_pertence(){
        User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 2
        ]);
        
        $user = User::create([
            'nome' => 'Usuario um',
            'apelido' => 'usuario1',
            'password' => 'password',
            'nivel' => 1
        ]);

        Tarefa::create([
            "nome"=> "nome da tarefa",
            "descricao"=> "descricao da tarefa",
            "pontuacao"=> 150,
            "concluida"=> false,
            "criador_id"=> 1,
            "responsavel_id"=> 2
        ]);

        $this->actingAs($user);

        $response = $this->put('/api/concluir-tarefa/1');

        $response->assertStatus(200);
    }
    
    // concluir uma tarefa ja concluida
    public function test_concluir_uma_tarefa_ja_concluida_gera_erro(){
        User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 2
        ]);
        
        $user = User::create([
            'nome' => 'Usuario um',
            'apelido' => 'usuario1',
            'password' => 'password',
            'nivel' => 1
        ]);

        Tarefa::create([
            "nome"=> "nome da tarefa",
            "descricao"=> "descricao da tarefa",
            "pontuacao"=> 150,
            "concluida"=> true,
            "criador_id"=> 1,
            "responsavel_id"=> 2
        ]);

        $this->actingAs($user);

        $response = $this->put('/api/concluir-tarefa/1');

        $response->assertStatus(403);
    }

    // desassociar de uma tarefa a qual nao estou associado
    public function test_desassociar_de_uma_tarefa_a_qual_nao_estou_associado_gera_erro(){
        User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 2
        ]);
        
        $user = User::create([
            'nome' => 'Usuario um',
            'apelido' => 'usuario1',
            'password' => 'password',
            'nivel' => 1
        ]);

        Tarefa::create([
            "nome"=> "nome da tarefa",
            "descricao"=> "descricao da tarefa",
            "pontuacao"=> 150,
            "concluida"=> false,
            "criador_id"=> 1
        ]);

        $this->actingAs($user);

        $response = $this->put('/api/desassociar-tarefa/1');

        $response->assertStatus(403);
    }

    // desassociar de uma tarefa ja concluida
    public function test_desassociar_de_uma_tarefa_ja_concluida_gera_erro(){
        User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 2
        ]);
        
        $user = User::create([
            'nome' => 'Usuario um',
            'apelido' => 'usuario1',
            'password' => 'password',
            'nivel' => 1
        ]);

        Tarefa::create([
            "nome"=> "nome da tarefa",
            "descricao"=> "descricao da tarefa",
            "pontuacao"=> 150,
            "concluida"=> true,
            "criador_id"=> 1,
            "responsavel_id"=> 1
        ]);

        $this->actingAs($user);

        $response = $this->put('/api/desassociar-tarefa/1');

        $response->assertStatus(403);
    }
    
    // desassociar de uma tarefa a qual outra pessoa que esta associada
    public function test_desassociar_de_uma_tarefa_a_qual_outra_pessoa_esta_associada_gera_erro(){
        User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 2
        ]);
        
        User::create([
            'nome' => 'Usuario um',
            'apelido' => 'usuario1',
            'password' => 'password',
            'nivel' => 1
        ]);

        Tarefa::create([
            "nome"=> "nome da tarefa",
            "descricao"=> "descricao da tarefa",
            "pontuacao"=> 150,
            "concluida"=> false,
            "criador_id"=> 1,
            "responsavel_id"=> 1
        ]);

        $user = User::create([
            'nome' => 'Usuario dois',
            'apelido' => 'usuario2',
            'password' => 'password',
            'nivel' => 1
        ]);

        $this->actingAs($user);

        $response = $this->put('/api/desassociar-tarefa/1');

        $response->assertStatus(403);
    }
}
