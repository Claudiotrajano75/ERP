<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Nfe;
use App\Models\Empresa;

$empresa = Empresa::find(2);
$empresa = __objetoParaEmissao($empresa, 1);

echo "=== Correção do campo Número NFe ===\n";
echo "Antes (bug): lastNumero retornava numero_ultima_nfe_producao + 1\n";
echo "Contador da empresa: {$empresa->numero_ultima_nfe_producao}\n";
echo "Agora (corrigido): lastNumero() = " . Nfe::lastNumero($empresa) . "\n\n";

echo "=== O que a SEFAZ já tem registrado (números transmitidos) ===\n";
$chaves = Nfe::where('empresa_id', 2)->whereNotNull('chave')->where('chave', '!=', '')->get(['id', 'estado', 'chave']);
foreach ($chaves as $c) {
    $n = (int) ltrim(substr($c->chave, 25, 9), '0');
    $s = (int) ltrim(substr($c->chave, 22, 3), '0');
    echo "  NFe id {$c->id}: número {$n} série {$s} -> {$c->estado}\n";
}

echo "\n=== Por que o campo mostrava 8 ===\n";
echo "O formulário usa Nfe::lastNumero(\$empresa). Como numero_ultima_nfe_producao era 7, sugeria 8.\n";
echo "Mas 8 (e 9, 10, 26...) já foram usados na SEFAZ em transmissões reais de produção.\n";
echo "Correção: lastNumero agora analisa as chaves das NFes transmitidas para sugerir o próximo livre.\n";
