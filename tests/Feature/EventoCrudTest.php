<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Database\Seeders\KonexSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EventoCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_lista_crea_ve_actualiza_y_elimina_un_evento(): void
    {
        $this->seed(KonexSeeder::class);
        Sanctum::actingAs(Usuario::query()->where('correo_institucional', 'carlos.pena@uniespinal.edu.co')->first());

        $this->getJson('/api/eventos')
            ->assertOk()
            ->assertJsonFragment(['titulo' => 'Feria de prácticas']);

        $creado = $this->postJson('/api/eventos', [
            'id_usuario' => 1,
            'titulo' => 'Taller de Laravel',
            'descripcion' => 'CRUD de eventos para Thunder Client',
            'fecha' => '2026-10-20 10:00:00',
            'lugar' => 'Sala 5',
        ])->assertCreated()
            ->assertJsonFragment(['titulo' => 'Taller de Laravel']);

        $id = $creado->json('id_evento');

        $this->getJson('/api/eventos/'.$id)
            ->assertOk()
            ->assertJsonFragment(['lugar' => 'Sala 5']);

        $this->putJson('/api/eventos/'.$id, [
            'lugar' => 'Auditorio',
        ])->assertOk()
            ->assertJsonFragment(['lugar' => 'Auditorio']);

        $this->deleteJson('/api/eventos/'.$id)
            ->assertOk()
            ->assertJsonFragment(['message' => 'Evento eliminado']);

        $this->getJson('/api/eventos/'.$id)->assertNotFound();
    }

    public function test_post_vacio_devuelve_422(): void
    {
        $this->seed(KonexSeeder::class);
        Sanctum::actingAs(Usuario::query()->where('correo_institucional', 'carlos.pena@uniespinal.edu.co')->first());

        $this->postJson('/api/eventos', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['id_usuario', 'titulo', 'fecha']);
    }
}
