<?php
// Diagnóstico: por que /nfe/xml-temp/57 dá erro 500?
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$id = isset($argv[1]) ? (int)$argv[1] : 57;
$nfe = \App\Models\Nfe::find($id);

echo "NFe $id:\n";
echo '  natureza_id: ' . var_export($nfe->natureza_id, true) . "\n";
echo '  natureza relação: ' . ($nfe->natureza ? 'EXISTE (id ' . $nfe->natureza->id . ' - ' . $nfe->natureza->descricao . ')' : 'NULL (não existe!)') . "\n";
echo '  cliente_id: ' . var_export($nfe->cliente_id, true) . "\n";
echo '  fornecedor_id: ' . var_export($nfe->fornecedor_id, true) . "\n";
echo '  transportadora_id: ' . var_export($nfe->transportadora_id, true) . "\n";
echo '  tpNF: ' . $nfe->tpNF . "\n";
echo '  empresa_id: ' . $nfe->empresa_id . "\n";
echo '  itens: ' . $nfe->itens()->count() . "\n";
echo '  local_id: ' . var_export($nfe->local_id, true) . "\n";
echo "\nNaturezas de operação da empresa " . $nfe->empresa_id . ":\n";
foreach (\App\Models\NaturezaOperacao::where('empresa_id', $nfe->empresa_id)->get(['id', 'descricao']) as $n) {
    echo '  id ' . $n->id . ': ' . $n->descricao . "\n";
}
