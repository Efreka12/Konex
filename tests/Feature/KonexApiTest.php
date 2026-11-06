<?php

namespace Tests\Feature;

use Database\Seeders\KonexSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KonexApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_lista_roles_y_usuarios_de_konex(): void
    {
        $this->seed(KonexSeeder::class);

        $this->getJson('/api/v1/roles')
            ->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonFragment(['nombre' => 'Estudiante']);

        $this->getJson('/api/v1/usuarios')
            ->assertStatus(200)
            ->assertJsonFragment(['correo_institucional' => 'ana.torres@uniespinal.edu.co'])
            ->assertJsonMissing(['contrasena']);
    }

    public function test_lista_publicaciones_y_grupos(): void
    {
        $this->seed(KonexSeeder::class);

        $this->getJson('/api/v1/publicaciones')
            ->assertStatus(200)
            ->assertJsonStructure(['data' => [['titulo', 'contenido']]]);
        $this->getJson('/api/v1/grupos_estudio')
            ->assertStatus(200)
            ->assertJsonFragment(['codigo_union' => 'CALCII26']);
    }
}
