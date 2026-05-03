<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DomadorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre'      => $this->faker->name(),
            'ciudad'      => $this->faker->city(),
            'experiencia' => $this->faker->numberBetween(1, 20),
        ];
    }
}