<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Empresa;
use App\Models\Nfe;

$emp = Empresa::find(2);

// Maior nNF já transmitido (decodifica chaves)
$maior = Nfe::where('empresa_id', 2)
    ->whereNotNull('chave')
    ->where('chave', '!=', '')
    ->get()
    ->map(function ($n) {
        return (int) ltrim(substr($n->chave, 25, 9), '0');
    })
    ->max();

echo "Maior nNF transmitido: " . var_export($maior, true) . "\n";
echo "Contador atual (producao): " . var_export($emp->numero_ultima_nfe_producao, true) . "\n";
echo "Contador atual (homologacao): " . var_export($emp->numero_ultima_nfe_homologacao, true) . "\n";

if ($emp->ambiente == 2) {
    if ((int)$maior >= (int)$emp->numero_ultima_nfe_homologacao) {
        $emp->numero_ultima_nfe_homologacao = $maior;
    }
} else {
    if ((int)$maior >= (int)$emp->numero_ultima_nfe_producao) {
        $emp->numero_ultima_nfe_producao = $maior;
    }
}
$emp->save();

echo "Contador ATUALIZADO: " . $emp->numero_ultima_nfe_producao . "\n";
echo "lastNumero() sugere: " . Nfe::lastNumero($emp) . "\n";
