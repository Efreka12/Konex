<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_lista_las_tareas_existentes(): void
    {
        Task::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/tasks');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_crea_una_tarea_nueva(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $payload = ['title' => 'Repasar rutas de Laravel'];

        $response = $this->postJson('/api/v1/tasks', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['title' => 'Repasar rutas de Laravel']);

        $this->assertDatabaseHas('tasks', ['title' => 'Repasar rutas de Laravel']);
    }

    public function test_devuelve_404_si_la_tarea_no_existe(): void
    {
        $response = $this->getJson('/api/v1/tasks/999');

        $response->assertStatus(404);
    }

    public function test_no_crea_tarea_sin_autenticacion(): void
    {
        $response = $this->postJson('/api/v1/tasks', [
            'title' => 'Sin token',
        ]);

        $response->assertStatus(401);
    }
}
