<?php
namespace Tests\Unit;

use App\Models\Dragon;
use App\Models\Domador;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DragonModelTest extends TestCase
{
    use RefreshDatabase; // Resetea la BD :memory: antes de cada test

    // TEST 1 — Crear con datos directos
    public function test_puede_crear_un_dragon(): void
    {
        $domador = Domador::factory()->create();

        $dragon = Dragon::create([
            'nombre'     => 'Drogon',
            'tipo'       => 'Fuego',
            'poder'      => 95,
            'domador_id' => $domador->id,
        ]);

        $this->assertNotNull($dragon->id);
        $this->assertEquals('Drogon', $dragon->nombre);
        $this->assertDatabaseHas('dragons', ['nombre' => 'Drogon']);
    }

    // TEST 2 — Crear con factory
    public function test_puede_crear_dragon_con_factory(): void
    {
        $dragon = Dragon::factory()->create();

        $this->assertNotNull($dragon->id);
        $this->assertNotEmpty($dragon->nombre);
        $this->assertGreaterThan(0, $dragon->poder);
        $this->assertDatabaseHas('dragons', ['id' => $dragon->id]);
    }

    // TEST 3 — Actualizar
    public function test_puede_actualizar_un_dragon(): void
    {
        $dragon = Dragon::factory()->create(['poder' => 30]);

        $dragon->update(['poder' => 99]);

        // fresh() recarga desde BD (evita caché)
        $this->assertEquals(99, $dragon->fresh()->poder);
        $this->assertDatabaseHas('dragons', ['id' => $dragon->id, 'poder' => 99]);
        $this->assertDatabaseMissing('dragons', ['id' => $dragon->id, 'poder' => 30]);
    }

    // TEST 4 — Eliminar
    public function test_puede_eliminar_un_dragon(): void
    {
        $dragon = Dragon::factory()->create();
        $id = $dragon->id;

        $dragon->delete();

        $this->assertDatabaseMissing('dragons', ['id' => $id]);
    }

    // TEST 5 — Verificar $fillable
    public function test_tiene_los_fillable_correctos(): void
    {
        $dragon   = new Dragon();
        $fillable = $dragon->getFillable();

        $this->assertContains('nombre',     $fillable);
        $this->assertContains('tipo',       $fillable);
        $this->assertContains('poder',      $fillable);
        $this->assertContains('domador_id', $fillable);
    }

    // TEST 6 — Relación con Domador
    public function test_pertenece_a_un_domador(): void
    {
        $dragon = Dragon::factory()->create();

        $this->assertNotNull($dragon->domador);
        $this->assertInstanceOf(Domador::class, $dragon->domador);
    }

    // TEST 7 — Múltiples dragones
    public function test_puede_crear_multiples_dragons(): void
    {
        Dragon::factory()->count(4)->create();
        $this->assertDatabaseCount('dragons', 4);
    }
}