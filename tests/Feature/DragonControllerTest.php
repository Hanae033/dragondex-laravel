<?php
namespace Tests\Feature;

use App\Models\Dragon;
use App\Models\Domador;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DragonControllerTest extends TestCase
{
    use RefreshDatabase;

    // TEST 1 — Crear dragón via API (POST)
    // ⚠️ postJson() porque es API REST (devuelve JSON, no HTML)
    public function test_puede_crear_dragon_via_api(): void
    {
        $domador = Domador::factory()->create();

        $respuesta = $this->postJson('/api/dragons', [
            'nombre'     => 'Saphira',
            'tipo'       => 'Hielo',
            'poder'      => 92,
            'domador_id' => $domador->id,
        ]);

        $respuesta->assertStatus(201)                         // 201 = Created
                  ->assertJsonFragment(['nombre' => 'Saphira']);
        $this->assertDatabaseHas('dragons', ['nombre' => 'Saphira']);
    }

    // TEST 2 — Actualizar (PUT)
    public function test_puede_actualizar_dragon_via_api(): void
    {
        $dragon = Dragon::factory()->create(['poder' => 20]);

        $respuesta = $this->putJson("/api/dragons/{$dragon->id}", ['poder' => 80]);

        $respuesta->assertStatus(200)
                  ->assertJsonFragment(['poder' => 80]);
        $this->assertDatabaseHas('dragons', ['id' => $dragon->id, 'poder' => 80]);
    }

    // TEST 3 — Eliminar (DELETE)
    public function test_puede_eliminar_dragon_via_api(): void
    {
        $dragon = Dragon::factory()->create();
        $id = $dragon->id;

        $respuesta = $this->deleteJson("/api/dragons/{$id}");

        $respuesta->assertStatus(200);
        $this->assertDatabaseMissing('dragons', ['id' => $id]);
    }

    // TEST 4 — Validación: nombre obligatorio
    // ⚠️ assertJsonValidationErrors porque es API (no assertSessionHasErrors)
    public function test_validacion_nombre_obligatorio(): void
    {
        $domador = Domador::factory()->create();

        $respuesta = $this->postJson('/api/dragons', [
            // 'nombre' ausente → debe dar 422
            'tipo'       => 'Fuego',
            'poder'      => 50,
            'domador_id' => $domador->id,
        ]);

        $respuesta->assertStatus(422)
                  ->assertJsonValidationErrors(['nombre']);
        $this->assertDatabaseCount('dragons', 0);
    }

    // TEST 5 — Validación: poder debe ser numérico
    public function test_validacion_poder_numerico(): void
    {
        $domador = Domador::factory()->create();

        $respuesta = $this->postJson('/api/dragons', [
            'nombre'     => 'Thorn',
            'tipo'       => 'Fuego',
            'poder'      => 'mucho', // String → falla
            'domador_id' => $domador->id,
        ]);

        $respuesta->assertStatus(422)
                  ->assertJsonValidationErrors(['poder']);
    }

    // TEST 6 — Validación: domador debe existir en BD
    public function test_validacion_domador_debe_existir(): void
    {
        $respuesta = $this->postJson('/api/dragons', [
            'nombre'     => 'Drogon',
            'tipo'       => 'Fuego',
            'poder'      => 95,
            'domador_id' => 9999, // No existe → falla
        ]);

        $respuesta->assertStatus(422)
                  ->assertJsonValidationErrors(['domador_id']);
    }
}