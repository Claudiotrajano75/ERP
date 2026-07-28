<?php

namespace Database\Factories;

use App\Models\Empresa;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmpresaFactory extends Factory
{
    protected $model = Empresa::class;

    public function definition(): array
    {
        return [
            'nome' => fake()->company(),
            'nome_fantasia' => fake()->company(),
            'cpf_cnpj' => fake()->numerify('##############'),
            'ie' => fake()->numerify('###########'),
            'email' => fake()->companyEmail(),
            'celular' => fake()->numerify('119########'),
            'status' => 1,
            'cep' => fake()->numerify('########'),
            'rua' => fake()->streetName(),
            'numero' => fake()->buildingNumber(),
            'bairro' => 'Centro',
            'cidade_id' => \App\Models\Cidade::factory(),
            'tributacao' => 'Simples Nacional',
            'ambiente' => 2,
        ];
    }
}
