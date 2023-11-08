<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_index_returns_ok_response(): void
    {
        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 3
        ]);

        $response = $this->actingAs($user)->get('/api/usuarios');
        $response->assertStatus(200);
    }

    public function test_user_show_returns_ok_response(): void
    {
        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 3
        ]);

        $usuario = User::first();
        $response = $this->actingAs($user)->get('/api/usuarios/' . $usuario->id);
        $response->assertStatus(200);
    }

    public function test_user_de_nivel_3_pode_criar_user_de_nivel_3(): void
    {
        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 3
        ]);
        
        $this->actingAs($user);

        $response = $this->post('/api/usuarios', [
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 3,
        ]);

        $response->assertStatus(201);
    }

    public function test_user_de_nivel_3_pode_criar_user_de_nivel_2(): void
    {
        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 3
        ]);
        
        $this->actingAs($user);

        $response = $this->post('/api/usuarios', [
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 2,
        ]);

        $response->assertStatus(201);
    }

    public function test_user_de_nivel_3_pode_criar_user_de_nivel_1(): void
    {
        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 3
        ]);
        
        $this->actingAs($user);

        $response = $this->post('/api/usuarios', [
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 1,
        ]);

        $response->assertStatus(201);
    }

    public function test_user_de_nivel_2_pode_criar_user_de_nivel_1(): void
    {
        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 2
        ]);
        
        $this->actingAs($user);

        $response = $this->post('/api/usuarios', [
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 1,
        ]);

        $response->assertStatus(201);
    }

    public function test_user_de_nivel_2_nao_pode_criar_user_de_nivel_3(): void
    {
        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 2
        ]);
        
        $this->actingAs($user);

        $response = $this->post('/api/usuarios', [
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 3,
        ]);

        $response->assertStatus(403);
    }

    public function test_user_de_nivel_2_nao_pode_criar_user_de_nivel_2(): void
    {
        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 2
        ]);
        
        $this->actingAs($user);

        $response = $this->post('/api/usuarios', [
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 2,
        ]);

        $response->assertStatus(403);
    }

    public function test_user_de_nivel_1_nao_pode_criar_usuarios(): void
    {
        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 1
        ]);
        
        $this->actingAs($user);

        $response = $this->post('/api/usuarios', [
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 1,
        ]);

        $response->assertStatus(403);
    }

    public function test_user_de_nivel_3_pode_editar_user_nivel_2(): void
    {
        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 3
        ]);
        
        User::create([
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 2,
        ]);
        
        $this->actingAs($user);

        $response = $this->put('/api/usuarios/2', [
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 1,
        ]);

        $response->assertStatus(200);
    }

    public function test_user_de_nivel_3_pode_editar_user_nivel_1(): void
    {
        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 3
        ]);
        
        User::create([
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 2,
        ]);
        
        $this->actingAs($user);

        $response = $this->put('/api/usuarios/2', [
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 3,
        ]);

        $response->assertStatus(200);
    }

    public function test_user_de_nivel_2_pode_editar_user_nivel_1(): void
    {
        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 2
        ]);
        
        User::create([
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 1,
        ]);
        
        $this->actingAs($user);

        $response = $this->put('/api/usuarios/2', [
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 1,
        ]);

        $response->assertStatus(200);
    }

    public function test_user_de_nivel_2_nao_pode_editar_user_nivel_2(): void
    {
        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 2
        ]);
        
        User::create([
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 2,
        ]);
        
        $this->actingAs($user);

        $response = $this->put('/api/usuarios/2', [
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 1,
        ]);

        $response->assertStatus(403);
    }

    public function test_user_de_nivel_1_nao_pode_editar_usuario(): void
    {
        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 1
        ]);
        
        User::create([
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 1,
        ]);
        
        $this->actingAs($user);

        $response = $this->put('/api/usuarios/2', [
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 1,
        ]);

        $response->assertStatus(403);
    }

    //3 x 3
    public function test_user_de_nivel_3_pode_remover_user_nivel_3(): void
    {
        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 3
        ]);
        
        User::create([
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 3,
        ]);
        
        $this->actingAs($user);

        $response = $this->delete('/api/usuarios/2');

        $response->assertStatus(200);
    }
    
    //3 x 2
    public function test_user_de_nivel_3_pode_remover_user_nivel_2(): void
    {
        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 3
        ]);
        
        User::create([
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 2,
        ]);
        
        $this->actingAs($user);

        $response = $this->delete('/api/usuarios/2');

        $response->assertStatus(200);
    }
    
    //3 x 1
    public function test_user_de_nivel_3_pode_remover_user_nivel_1(): void
    {
        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 3
        ]);
        
        User::create([
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 1,
        ]);
        
        $this->actingAs($user);

        $response = $this->delete('/api/usuarios/2');

        $response->assertStatus(200);
    }
    
    //2 x 3
    public function test_user_de_nivel_2_nao_pode_remover_user_nivel_3(): void
    {
        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 2
        ]);
        
        User::create([
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 3,
        ]);
        
        $this->actingAs($user);

        $response = $this->delete('/api/usuarios/2');

        $response->assertStatus(403);
    }
    
    //2 x 2
    public function test_user_de_nivel_2_nao_pode_remover_user_nivel_2(): void
    {
        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 2
        ]);
        
        User::create([
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 2,
        ]);
        
        $this->actingAs($user);

        $response = $this->delete('/api/usuarios/2');

        $response->assertStatus(403);
    }
    
    //2 x 1
    public function test_user_de_nivel_2_pode_remover_user_nivel_1(): void
    {
        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 2
        ]);
        
        User::create([
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 1,
        ]);
        
        $this->actingAs($user);

        $response = $this->delete('/api/usuarios/2');

        $response->assertStatus(200);
    }
    
    //1 x 1
    public function test_user_de_nivel_1_nao_pode_remover_user_nivel_1(): void
    {
        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 1
        ]);
        
        User::create([
            'nome' => 'José',
            'apelido' => 'jose123',
            'password' => 'password',
            'nivel' => 1,
        ]);
        
        $this->actingAs($user);

        $response = $this->delete('/api/usuarios/2');

        $response->assertStatus(403);
    }
}
