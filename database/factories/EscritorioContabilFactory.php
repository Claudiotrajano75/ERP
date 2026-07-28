<?php

namespace Database\Factories;

use App\Models\EscritorioContabil;
use Illuminate\Database\Eloquent\Factories\Factory;

class EscritorioContabilFactory extends Factory
{
    protected $model = EscritorioContabil::class;

    public function definition(): array
    {
        return [
            'empresa_id' => \App\Models\Empresa::factory(),
            'cidade_id' => \App\Models\Cidade::factory(),
            'razao_social' => fake()->company() . ' Contabilidade',
            'nome_fantasia' => fake()->company(),
            'cnpj' => fake()->numerify('##############'),
            'ie' => fake()->numerify('###########'),
            'rua' => fake()->streetName(),
            'numero' => fake()->buildingNumber(),
            'bairro' => 'Centro',
            'telefone' => fake()->numerify('119########'),
            'email' => fake()->companyEmail(),
            'cep' => fake()->numerify('########'),
            'crc' => 'SP' . fake()->numerify('######'),
            'cpf' => '',
            'envio_xml_automatico' => false,
        ];
    }
}
