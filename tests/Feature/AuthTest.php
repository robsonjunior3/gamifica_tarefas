<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_ok_response_para_login_e_senha_validos(): void 
    {
        User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 3
        ]);

        $response = $this->post('/api/login', [
            'apelido' => 'joao123',
            'password' => 'password'
        ]);

        $response->assertStatus(200);
    }

    public function test_error_response_para_login_e_senha_invalidos(): void 
    {
        User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 3
        ]);

        $response = $this->post('/api/login', [
            'apelido' => 'x-joao123',
            'password' => 'x-password'
        ]);

        $response->assertStatus(401);
    }

    public function test_error_response_para_login_invalido(): void 
    {
        User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 3
        ]);

        $response = $this->post('/api/login', [
            'apelido' => 'x-joao123',
            'password' => 'password'
        ]);

        $response->assertStatus(401);
    }

    public function test_error_response_para_senha_invalida(): void 
    {
        User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 3
        ]);

        $response = $this->post('/api/login', [
            'apelido' => 'joao123',
            'password' => 'x-password'
        ]);

        $response->assertStatus(401);
    }

    public function test_logout_retorna_resposta_ok(): void 
    {
        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 3
        ]);
        
        $this->actingAs($user);

        $response = $this->post('/api/logout');

        $response->assertStatus(200);
    }
}
