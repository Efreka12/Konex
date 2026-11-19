<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\KonexSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ReactWorkshopApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_registro_login_perfil_y_edicion_con_tabla_users(): void
    {
        $this->seed(KonexSeeder::class);

        $this->postJson('/api/auth/register', [
            'name' => 'Luis Pérez',
            'email' => 'luis.perez@uniespinal.edu.co',
            'password' => 'password12',
        ])->assertCreated()
            ->assertJsonStructure(['message', 'userId']);

        $this->assertDatabaseHas('users', [
            'name' => 'Luis Pérez',
            'email' => 'luis.perez@uniespinal.edu.co',
        ]);

        $login = $this->postJson('/api/auth/login', [
            'email' => 'ana.torres@uniespinal.edu.co',
            'password' => 'password',
        ])->assertOk()
            ->assertJsonStructure(['token']);

        $perfil = $this->withToken($login->json('token'))
            ->getJson('/api/users/profile')
            ->assertOk()
            ->assertJsonFragment([
                'name' => 'Ana Torres',
                'email' => 'ana.torres@uniespinal.edu.co',
            ]);

        $this->assertEqualsCanonicalizing(['id', 'name', 'email'], array_keys($perfil->json()));

        Sanctum::actingAs(User::query()->where('email', 'ana.torres@uniespinal.edu.co')->first());

        $this->putJson('/api/users/update', [
            'name' => 'Ana Torres Gómez',
            'email' => 'ana.torres@uniespinal.edu.co',
        ])->assertOk()
            ->assertJsonFragment(['name' => 'Ana Torres Gómez'])
            ->assertJsonMissingPath('rol');
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

    public function test_perfil_sin_token_devuelve_401_token_invalido(): void
    {
        $this->getJson('/api/users/profile')
            ->assertUnauthorized()
            ->assertJson(['message' => 'Token inválido']);
    }

    public function test_email_duplicado_en_registro(): void
    {
        $this->seed(KonexSeeder::class);

        $this->postJson('/api/auth/register', [
            'name' => 'Ana',
            'email' => 'ana.torres@uniespinal.edu.co',
            'password' => 'password',
        ])->assertUnprocessable()
            ->assertJsonFragment(['email' => ['El email ya está registrado']]);
    }
}
