<?php

namespace Tests\Feature;

use Database\Seeders\KonexSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginWebTest extends TestCase
{
    use RefreshDatabase;

    public function test_inicia_sesion_con_usuario_de_konex(): void
    {
        $this->seed(KonexSeeder::class);

        $response = $this->post('/login', [
            'correo_institucional' => 'ana.torres@uniespinal.edu.co',
            'contrasena' => 'password',
        ]);

        $response->assertRedirect(route('inicio'));
        $this->assertAuthenticated();
    }

    public function test_inicio_muestra_publicaciones_de_la_base(): void
    {
        $this->seed(KonexSeeder::class);

        $this->post('/login', [
            'correo_institucional' => 'ana.torres@uniespinal.edu.co',
            'contrasena' => 'password',
        ]);

        $this->get('/inicio')
            ->assertOk()
            ->assertSee('Ana Torres')
            ->assertSee('Foro de Cálculo II');
    }
}
