<?php
// Marca "Compra de Mercadorias" como natureza padrão da empresa 2
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$empresa_id = 2;

// desmarca as outras (para haver apenas uma padrão)
\App\Models\NaturezaOperacao::where('empresa_id', $empresa_id)->update(['padrao' => 0]);

$natureza = \App\Models\NaturezaOperacao::where('empresa_id', $empresa_id)
    ->where('descricao', 'Compra de Mercadorias')->first();

if ($natureza) {
    $natureza->padrao = 1;
    $natureza->save();
    echo "Natureza padrão definida: id {$natureza->id} - {$natureza->descricao} (padrao=1)\n";
} else {
    echo "ERRO: natureza 'Compra de Mercadorias' não encontrada!\n";
}

foreach (\App\Models\NaturezaOperacao::where('empresa_id', $empresa_id)->get(['id', 'descricao', 'padrao']) as $n) {
    echo "  nat {$n->id}: {$n->descricao} | padrao=" . var_export($n->padrao, true) . "\n";
}
