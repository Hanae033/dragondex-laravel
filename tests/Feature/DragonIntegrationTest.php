<?php
namespace Tests\Feature;

use App\Models\Dragon;
use App\Models\Domador;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DragonIntegrationTest extends TestCase
{
    use RefreshDatabase;

    // TEST INT-1 — GET /api/dragons devuelve 200
    public function test_endpoint_get_dragons_devuelve_200(): void
    {
        Dragon::factory()->count(3)->create();

        $respuesta = $this->getJson('/api/dragons');

        $respuesta->assertStatus(200);    // Código correcto
        $respuesta->assertJsonCount(3);   // 3 dragones
    }

    // TEST INT-2 — Estructura JSON correcta
    public function test_estructura_json_correcta(): void
    {
        Dragon::factory()->count(2)->create();

        $respuesta = $this->getJson('/api/dragons');

        $respuesta->assertStatus(200)
                  ->assertJsonStructure([
                      '*' => [   // * = para cada elemento del array
                          'id',
                          'nombre',
                          'tipo',
                          'poder',
                          'domador_id',
                          'created_at',
                          'updated_at',
                      ]
                  ]);
    }

    // TEST INT-3 — Datos correctos en respuesta
    public function test_datos_correctos_en_respuesta(): void
    {
        $domador = Domador::factory()->create();
        Dragon::factory()->create([
            'nombre'     => 'Desdentado',
            'tipo'       => 'Rayo',
            'poder'      => 90,
            'domador_id' => $domador->id,
        ]);

        $respuesta = $this->getJson('/api/dragons');

        $respuesta->assertStatus(200)
                  ->assertJsonFragment([
                      'nombre' => 'Desdentado',
                      'tipo'   => 'Rayo',
                  ]);
    }

    // TEST INT-4 — Lista vacía devuelve 200 con []
    public function test_lista_vacia_devuelve_200(): void
    {
        $respuesta = $this->getJson('/api/dragons');

        $respuesta->assertStatus(200)
                  ->assertJson([]);
    }
}
