<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Database\Seeders\KonexSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ReactWorkshopApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_registro_login_perfil_y_edicion_con_usuarios_konex(): void
    {
        $this->seed(KonexSeeder::class);

        $this->postJson('/api/auth/register', [
            'name' => 'Luis Pérez',
            'email' => 'luis.perez@uniespinal.edu.co',
            'password' => 'password12',
        ])->assertCreated()
            ->assertJsonStructure(['message', 'userId']);

        $login = $this->postJson('/api/auth/login', [
            'email' => 'ana.torres@uniespinal.edu.co',
            'password' => 'password',
        ])->assertOk()
            ->assertJsonStructure(['token']);

        $this->withToken($login->json('token'))
            ->getJson('/api/users/profile')
            ->assertOk()
            ->assertJsonFragment([
                'name' => 'Ana Torres',
                'email' => 'ana.torres@uniespinal.edu.co',
                'rol' => 'Estudiante',
            ]);

        Sanctum::actingAs(Usuario::query()->where('correo_institucional', 'ana.torres@uniespinal.edu.co')->first());

        $this->putJson('/api/users/update', [
            'name' => 'Ana Torres Gómez',
            'email' => 'ana.torres@uniespinal.edu.co',
        ])->assertOk()
            ->assertJsonFragment(['name' => 'Ana Torres Gómez']);
    }

    public function test_login_invalido_devuelve_401(): void
    {
        $this->seed(KonexSeeder::class);

        $this->postJson('/api/auth/login', [
            'email' => 'ana.torres@uniespinal.edu.co',
            'password' => 'incorrecta',
        ])->assertUnauthorized()
            ->assertJsonFragment(['message' => 'Credenciales inválidas']);
    }
}
