<?php

/**
 * Auditoria (SOMENTE LEITURA) dos registros de estoque.
 *
 * Detecta os sintomas do bug em que o local_id era gravado no campo
 * produto_variacao_id (ex.: API/NFeController):
 *   1. produto_variacao_id apontando para variação inexistente (órfão)
 *   2. produto_variacao_id apontando para variação de OUTRO produto
 *   3. produto_variacao_id que coincide com o id de uma localização
 *   4. local_id apontando para localização inexistente (órfão)
 *   5. mais de uma linha de estoque para o mesmo produto + local + variação
 *
 * Uso:
 *   php scratch/check_estoques_inconsistentes.php
 *   php scratch/check_estoques_inconsistentes.php <empresa_id>
 */

use Illuminate\Support\Facades\DB;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$empresa_id = $argv[1] ?? null;

if ($empresa_id) {
    echo "=== AUDITORIA DE ESTOQUE (empresa_id = {$empresa_id}) ===\n\n";
} else {
    echo "=== AUDITORIA DE ESTOQUE (todas as empresas) ===\n\n";
}

function filtrarEmpresa($query, $empresa_id)
{
    if ($empresa_id) {
        return $query->where('p.empresa_id', $empresa_id);
    }
    return $query;
}

function imprimirLinhas($titulo, $linhas)
{
    echo "--- {$titulo}: " . $linhas->count() . " registro(s) ---\n";
    if ($linhas->isEmpty()) {
        echo "  OK\n\n";
        return;
    }
    foreach ($linhas as $l) {
        echo "  estoque #{$l->id} | produto #{$l->produto_id} ({$l->produto})"
            . " | produto_variacao_id={$l->produto_variacao_id}"
            . " | local_id={$l->local_id}"
            . " | qtd={$l->quantidade}\n";
    }
    echo "\n";
}

// 1) produto_variacao_id órfão
$orfaos = filtrarEmpresa(
    DB::table('estoques as e')
        ->leftJoin('produto_variacaos as v', 'v.id', '=', 'e.produto_variacao_id')
        ->join('produtos as p', 'p.id', '=', 'e.produto_id')
        ->whereNotNull('e.produto_variacao_id')
        ->whereNull('v.id'),
    $empresa_id
)->select('e.id', 'e.produto_id', 'p.nome as produto', 'e.produto_variacao_id', 'e.local_id', 'e.quantidade')->get();
imprimirLinhas('1. produto_variacao_id inexistente (órfão)', $orfaos);

// 2) variação pertencente a outro produto
$variacaoOutroProduto = filtrarEmpresa(
    DB::table('estoques as e')
        ->join('produto_variacaos as v', 'v.id', '=', 'e.produto_variacao_id')
        ->join('produtos as p', 'p.id', '=', 'e.produto_id')
        ->whereNotNull('e.produto_variacao_id')
        ->whereNotNull('v.produto_id')
        ->whereColumn('v.produto_id', '!=', 'e.produto_id'),
    $empresa_id
)->select('e.id', 'e.produto_id', 'p.nome as produto', 'e.produto_variacao_id', 'e.local_id', 'e.quantidade')->get();
imprimirLinhas('2. produto_variacao_id pertence a outro produto', $variacaoOutroProduto);

// 3) produto_variacao_id coincide com um id de localização
$variacaoPareceLocal = filtrarEmpresa(
    DB::table('estoques as e')
        ->join('localizacaos as l', 'l.id', '=', 'e.produto_variacao_id')
        ->join('produtos as p', 'p.id', '=', 'e.produto_id')
        ->whereNotNull('e.produto_variacao_id'),
    $empresa_id
)->select('e.id', 'e.produto_id', 'p.nome as produto', 'e.produto_variacao_id', 'e.local_id', 'e.quantidade')->get();
imprimirLinhas('3. produto_variacao_id igual ao id de uma localização (suspeito)', $variacaoPareceLocal);

// 4) local_id órfão
$localOrfao = filtrarEmpresa(
    DB::table('estoques as e')
        ->leftJoin('localizacaos as l', 'l.id', '=', 'e.local_id')
        ->join('produtos as p', 'p.id', '=', 'e.produto_id')
        ->whereNotNull('e.local_id')
        ->whereNull('l.id'),
    $empresa_id
)->select('e.id', 'e.produto_id', 'p.nome as produto', 'e.produto_variacao_id', 'e.local_id', 'e.quantidade')->get();
imprimirLinhas('4. local_id inexistente (órfão)', $localOrfao);

// 5) duplicidade por produto + local + variação
$duplicados = filtrarEmpresa(
    DB::table('estoques as e')
        ->join('produtos as p', 'p.id', '=', 'e.produto_id'),
    $empresa_id
)
    ->select(
        'e.produto_id',
        'p.nome as produto',
        'e.local_id',
        'e.produto_variacao_id',
        DB::raw('COUNT(*) as linhas'),
        DB::raw('SUM(e.quantidade) as soma')
    )
    ->groupBy('e.produto_id', 'p.nome', 'e.local_id', 'e.produto_variacao_id')
    ->havingRaw('COUNT(*) > 1')
    ->get();

echo "--- 5. duplicidade (mesmo produto + local + variação): " . $duplicados->count() . " grupo(s) ---\n";
if ($duplicados->isEmpty()) {
    echo "  OK\n\n";
} else {
    foreach ($duplicados as $d) {
        echo "  produto #{$d->produto_id} ({$d->produto}) | local_id={$d->local_id}"
            . " | produto_variacao_id={$d->produto_variacao_id}"
            . " | linhas={$d->linhas} | soma_qtd={$d->soma}\n";
    }
    echo "\n";
}

echo "=== FIM DA AUDITORIA (nenhuma alteração foi feita) ===\n";
