<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Nfe;
use Illuminate\Support\Facades\Schema;

echo "=== Colunas da tabela nves ===\n";
echo implode(', ', Schema::getColumnListing('nves')) . "\n\n";

$nfes = Nfe::where('empresa_id', 2)->get(['id', 'tpNF', 'estado', 'numero', 'numero_sequencial', 'natureza_id', 'cliente_id', 'fornecedor_id', 'total', 'created_at']);

foreach ($nfes->groupBy('estado') as $est => $g) {
    echo $est . ': ' . $g->count() . " NFes\n";
}

echo "\n=== estado NOVO (candidatas a transmissão) ===\n";
foreach ($nfes->where('estado', 'novo') as $n) {
    echo 'NFe ' . $n->id
        . ' | ' . ($n->tpNF == 1 ? 'VENDA' : 'COMPRA')
        . ' | natureza_id=' . var_export($n->natureza_id, true)
        . ' | total=' . number_format((float)$n->total, 2, ',', '.')
        . ' | criada em ' . $n->created_at . "\n";
}
