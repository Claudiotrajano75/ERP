<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Nfe;
use App\Models\ItemNfe;
use App\Models\Inutilizacao;
use App\Models\Empresa;

// 1) Estrutura completa da NFe 8
$n8 = Nfe::find(8);
echo "=== NFe 8 (modelo) ===\n";
print_r($n8->toArray());

// 2) Itens da NFe 8
echo "\n=== Itens da NFe 8 ===\n";
foreach (ItemNfe::where('nfe_id', 8)->get() as $i) {
    print_r($i->toArray());
}

// 3) Números já utilizados (aprovado/cancelado/rejeitado) para esta empresa
echo "\n=== Números já utilizados (empresa 2) ===\n";
$usados = Nfe::where('empresa_id', 2)
    ->whereIn('estado', ['aprovado', 'cancelado', 'rejeitado'])
    ->pluck('numero')
    ->filter()
    ->unique()
    ->sort()
    ->values();
echo 'Números usados: ' . $usados->implode(', ') . "\n";
echo 'Máximo usado: ' . ($usados->max() ?? 0) . "\n";

// 4) Inutilizações registradas
echo "\n=== Inutilizações ===\n";
$cols = \Illuminate\Support\Facades\Schema::getColumnListing('inutilizacaos');
echo 'colunas: ' . implode(', ', $cols) . "\n";
foreach (Inutilizacao::where('empresa_id', 2)->get() as $in) {
    print_r($in->toArray());
}

// 5) Contadores da empresa
$emp = Empresa::find(2);
echo "\n=== Empresa 2 ===\n";
echo 'numero_ultima_nfe_producao: ' . var_export($emp->numero_ultima_nfe_producao, true) . "\n";
echo 'serie: ' . var_export($emp->serie, true) . "\n";
echo 'ambiente: ' . $emp->ambiente . "\n";
