<?php

namespace Database\Factories;

use App\Models\Kategoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class JardueraFactory extends Factory
{
    public function definition(): array
    {
        return [
            'izenburua' => ucfirst($this->faker->words(2, true)),
            'deskribapena' => $this->faker->sentence(),
            'data' => $this->faker->dateTimeBetween('+1 day', '+1 month'),
            'edukiera' => $this->faker->numberBetween(5, 20),
            'prezioa' => $this->faker->randomFloat(2, 0, 30),
            'kategoria_id' => Kategoria::inRandomOrder()->value('id'),
        ];
    }
}
