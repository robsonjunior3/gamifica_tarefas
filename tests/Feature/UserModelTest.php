<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserModelTest extends TestCase
{
    public function test_user_index_returns_ok_response(): void
    {
        $response = $this->get('/api/usuarios');
        $response->assertStatus(200);
    }

    public function test_user_get_returns_ok_response(): void
    {
        $usuario = User::first();
        $response = $this->get('/api/usuarios/' . $usuario->id);
        $response->assertStatus(200);
    }
}
