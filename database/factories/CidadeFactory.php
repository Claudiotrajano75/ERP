<?php

namespace Database\Factories;

use App\Models\Cidade;
use Illuminate\Database\Eloquent\Factories\Factory;

class CidadeFactory extends Factory
{
    protected $model = Cidade::class;

    public function definition(): array
    {
        return [
            'nome' => fake()->city(),
            'uf' => fake()->randomElement(['SP', 'RJ', 'MG', 'PR', 'SC', 'RS']),
            'codigo' => fake()->unique()->numerify('#######'),
        ];
    }
}
