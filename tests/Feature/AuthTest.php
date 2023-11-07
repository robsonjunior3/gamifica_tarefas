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

    public function test_user_login_returns_ok_response(): void 
    {
        $user = User::create([
            'nome' => 'João',
            'apelido' => 'joao123',
            'password' => 'password',
            'nivel' => 3
        ]);

        $this->assertNotNull($user, 'Usuário não encontrado no banco de dados');

        $request = new Request();
        
        $request->replace([
            'apelido' => 'joao123',
            'password' => 'password'
        ]);

        $response = $this->post('/api/login', ['request' => $request]);
        $response->assertStatus(200);
    }
}
