<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Database\Seeders\KonexSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProgramaCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_lista_crea_ve_actualiza_y_elimina_un_programa(): void
    {
        $this->seed(KonexSeeder::class);
        Sanctum::actingAs(Usuario::query()->where('correo_institucional', 'carlos.pena@uniespinal.edu.co')->first());

        $this->getJson('/api/programas')
            ->assertOk()
            ->assertJsonFragment(['nombre' => 'Ingeniería de Sistemas']);

        $creado = $this->postJson('/api/programas', [
            'nombre' => 'Ingeniería Industrial',
            'facultad' => 'Ingeniería',
        ])->assertCreated()
            ->assertJsonFragment(['nombre' => 'Ingeniería Industrial']);

        $id = $creado->json('id_programa');

        $this->getJson('/api/programas/'.$id)
            ->assertOk()
            ->assertJsonFragment(['facultad' => 'Ingeniería']);

        $this->putJson('/api/programas/'.$id, [
            'facultad' => 'Ciencias Básicas',
        ])->assertOk()
            ->assertJsonFragment(['facultad' => 'Ciencias Básicas']);

        $this->deleteJson('/api/programas/'.$id)
            ->assertOk()
            ->assertJsonFragment(['message' => 'Programa eliminado']);

        $this->getJson('/api/programas/'.$id)->assertNotFound();
    }

    public function test_post_vacio_devuelve_422(): void
    {
        $this->seed(KonexSeeder::class);
        Sanctum::actingAs(Usuario::query()->where('correo_institucional', 'carlos.pena@uniespinal.edu.co')->first());

        $this->postJson('/api/programas', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['nombre']);
    }
}
