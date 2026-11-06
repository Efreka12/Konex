<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_lista_crea_consulta_actualiza_y_elimina_productos(): void
    {
        $this->getJson('/api/products')
            ->assertOk()
            ->assertExactJson([]);

        $creado = $this->postJson('/api/products', [
            'name' => 'Teclado',
            'description' => 'Switch verde',
            'price' => 189900,
            'stock' => 15,
        ])->assertCreated()
            ->assertJsonFragment(['name' => 'Teclado', 'stock' => 15]);

        $id = $creado->json('id');

        $this->getJson('/api/products/'.$id)
            ->assertOk()
            ->assertJsonFragment(['description' => 'Switch verde']);

        $this->putJson('/api/products/'.$id, [
            'name' => 'Teclado mecánico',
            'description' => 'Switch rojo',
            'price' => 199900,
            'stock' => 12,
        ])->assertOk()
            ->assertJsonFragment(['name' => 'Teclado mecánico', 'stock' => 12]);

        $this->getJson('/api/products')
            ->assertOk()
            ->assertJsonFragment(['name' => 'Teclado mecánico']);

        $this->deleteJson('/api/products/'.$id)->assertNoContent();
        $this->getJson('/api/products/'.$id)->assertNotFound();
    }

    public function test_producto_requiere_campos_validos(): void
    {
        $this->postJson('/api/products', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'price', 'stock']);
    }
}
