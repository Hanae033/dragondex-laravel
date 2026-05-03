<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DragonFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->randomElement([
                'Drogon', 'Saphira', 'Desdentado', 'Viserion', 'Thorn'
            ]),
            'tipo' => $this->faker->randomElement([
                'Fuego', 'Hielo', 'Rayo', 'Veneno', 'Sombra'
            ]),
            'poder' => $this->faker->numberBetween(1, 100),
            // Crea automáticamente un Domador si no existe
            'domador_id' => \App\Models\Domador::factory(),
        ];
    }
}
