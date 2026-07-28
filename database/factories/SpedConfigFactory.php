<?php

namespace Database\Factories;

use App\Models\SpedConfig;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpedConfigFactory extends Factory
{
    protected $model = SpedConfig::class;

    public function definition(): array
    {
        return [
            'empresa_id' => \App\Models\Empresa::factory(),
            'codigo_conta_analitica' => '1.1.01',
            'codigo_receita' => '1001',
            'gerar_bloco_k' => false,
            'layout_bloco_k' => '0',
            'codigo_obrigacao' => '000',
            'data_vencimento' => '20',
        ];
    }
}
