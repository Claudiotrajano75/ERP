<?php

/**
 * TESTE COMPLETO: Venda (NFCe) → Saída de Estoque
 * -----------------------------------------------------------
 * Simula exatamente o que a TELA DE VENDAS (PDV/NFCe) do ERP faz:
 *   1. Cria uma NFCe de SAÍDA (venda) com itens
 *   2. Chama EstoqueUtil::reduzEstoque() → DIMINUI a quantidade
 *   3. Registra MovimentacaoProduto (tipo_transacao = 'venda_nfce')
 *   4. Verifica o estoque antes/depois
 *
 * Vende 5 produtos diferentes e valida a redução de estoque.
 * IMPORTANTE: os produtos de teste já têm estoque (do teste de compra).
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Produto;
use App\Models\Estoque;
use App\Models\Nfce;
use App\Models\ItemNfce;
use App\Models\Localizacao;
use App\Models\Empresa;
use App\Models\MovimentacaoProduto;
use App\Utils\EstoqueUtil;
use Illuminate\Support\Facades\Auth;

echo "════════════════════════════════════════════════════════════\n";
echo "  TESTE: VENDA → SAÍDA DE ESTOQUE (5 vendas / 5 produtos)\n";
echo "════════════════════════════════════════════════════════════\n\n";

// ── 1. Preparação ──
$user = App\Models\User::find(1); // CodeTech
Auth::login($user);
$util = new EstoqueUtil();

$empresa = Empresa::find(2);
if (!$empresa) { echo "ERRO: empresa 2 não encontrada\n"; exit(1); }

$local = Localizacao::where('id', 2)->first();
$local_id = $local ? $local->id : null;
echo "Usuário: {$user->name} | Empresa: {$empresa->nome} | Local: {$local->nome}\n\n";

// ── 2. Usar os produtos de teste (criados no teste de compra) ──
$nomes = [
    'TESTE FITILHO ALGODAO 2MM',
    'TESTE AGULHA CROCHE 3MM',
    'TESTE TECIDO TRICOLINE 1M',
    'TESTE BOTÃO METAL 15MM',
    'TESTE TESOURA COSTURA 9P',
];
$vendas = [20, 10, 6, 100, 5]; // quantidades vendidas

echo "── 2. Produtos de teste com estoque ──\n";
$produtos = [];
foreach ($nomes as $i => $nome) {
    $p = Produto::where('empresa_id', 2)->where('nome', $nome)->first();
    if (!$p) { echo "  ! produto '{$nome}' não encontrado (rode o teste de compra antes)\n"; exit(1); }
    $e = Estoque::where('produto_id', $p->id)->where('local_id', $local_id)->first();
    $qtd = $e ? (float)$e->quantidade : 0;
    $produtos[] = ['produto' => $p, 'qtd_venda' => $vendas[$i], 'estoque_atual' => $qtd, 'valor' => $p->valor_unitario];
    echo "  + #{$p->id} {$p->nome} — estoque atual: {$qtd}\n";
}
echo "\n";

// ── 3. Executar as 5 vendas ──
echo "── 3. Executando as 5 vendas (NFCe de saída + estoque) ──\n\n";

$resultados = [];
$numeroBase = Nfce::max('numero') ?? 0;

foreach ($produtos as $idx => $item) {
    $produto = $item['produto'];
    $qtdVenda = $item['qtd_venda'];
    $valor = $item['valor'];

    // Estoque ANTES
    $estoqueAntes = Estoque::where('produto_id', $produto->id)
        ->where('local_id', $local_id)->first();
    $qtdAntes = $estoqueAntes ? (float)$estoqueAntes->quantidade : 0;

    // ── 3.1 Cria a NFCe de SAÍDA (venda) ──
    $numero = $numeroBase + $idx + 1;
    $nfce = Nfce::create([
        'empresa_id' => 2,
        'emissor_nome' => $empresa->nome,
        'emissor_cpf_cnpj' => $empresa->cpf_cnpj,
        'cliente_nome' => 'CONSUMIDOR FINAL',
        'cliente_cpf_cnpj' => '000.000.000-00',
        'chave' => '',
        'numero_serie' => 1,
        'numero' => $numero,
        'estado' => 'novo',
        'total' => $valor * $qtdVenda,
        'desconto' => 0,
        'acrescimo' => 0,
        'ambiente' => $empresa->ambiente,
        'tipo_pagamento' => '01',
        'local_id' => $local_id,
        'user_id' => $user->id,
        'caixa_id' => null,
    ]);

    // ── 3.2 Cria o item da NFCe ──
    ItemNfce::create([
        'nfce_id' => $nfce->id,
        'produto_id' => $produto->id,
        'quantidade' => $qtdVenda,
        'valor_unitario' => $valor,
        'valor_custo' => $produto->valor_compra ?? 0,
        'sub_total' => $valor * $qtdVenda,
        'perc_icms' => 0,
        'perc_pis' => 0,
        'perc_cofins' => 0,
        'perc_ipi' => 0,
        'cst_csosn' => '102',
        'cst_pis' => '49',
        'cst_cofins' => '49',
        'cst_ipi' => '50',
        'cfop' => '5102',
        'ncm' => '5605.00.00',
        'perc_red_bc' => 0,
    ]);

    // ── 3.3 REDUZ O ESTOQUE (é aqui que a quantidade DIMINUI) ──
    if ($produto->gerenciar_estoque) {
        $util->reduzEstoque($produto->id, $qtdVenda, null, $local_id);
    }

    // ── 3.4 Registra a movimentação (tipo_transacao = 'venda_nfce') ──
    $util->movimentacaoProduto($produto->id, $qtdVenda, 'reducao', $nfce->id, 'venda_nfce', $user->id);

    // Estoque DEPOIS
    $estoqueDepois = Estoque::where('produto_id', $produto->id)
        ->where('local_id', $local_id)->first();
    $qtdDepois = $estoqueDepois ? (float)$estoqueDepois->quantidade : 0;

    $resultados[] = [
        'produto' => $produto->nome,
        'qtd_venda' => $qtdVenda,
        'antes' => $qtdAntes,
        'depois' => $qtdDepois,
        'reducao' => $qtdAntes - $qtdDepois,
        'nfce_id' => $nfce->id,
        'nfce_numero' => $nfce->numero,
        'ok' => ($qtdAntes - $qtdDepois) == $qtdVenda,
    ];

    $numVenda = $idx + 1;
    echo "  VENDA #{$numVenda}: {$produto->nome}\n";
    echo "    ├─ NFCe saída nº {$nfce->numero} (id {$nfce->id}) criada\n";
    echo "    ├─ Item: {$qtdVenda} un @ R$ {$valor} = R$ " . number_format($valor * $qtdVenda, 2, ',', '.') . "\n";
    echo "    ├─ reduzEstoque() chamado\n";
    echo "    ├─ Estoque: {$qtdAntes} → {$qtdDepois}  (redução de {$qtdVenda})\n";
    echo "    └─ Movimentação registrada (venda_nfce)\n\n";
}

// ── 4. Resumo e validação ──
echo "════════════════════════════════════════════════════════════\n";
echo "  RESULTADO FINAL\n";
echo "════════════════════════════════════════════════════════════\n";
$todosOk = true;
printf("%-30s %8s %8s %8s %8s %6s\n", 'PRODUTO', 'VENDIDO', 'ANTES', 'DEPOIS', 'REDUÇÃO', 'OK');
printf("%-30s %8s %8s %8s %8s %6s\n", str_repeat('-', 30), str_repeat('-', 8), str_repeat('-', 8), str_repeat('-', 8), str_repeat('-', 8), str_repeat('-', 6));
foreach ($resultados as $r) {
    if (!$r['ok']) $todosOk = false;
    printf("%-30s %8d %8d %8d %8d %6s\n", $r['produto'], $r['qtd_venda'], $r['antes'], $r['depois'], $r['reducao'], $r['ok'] ? '✓' : '✗');
}

echo "\n──────────────────────────────────────────────────────────\n";
if ($todosOk) {
    echo "✅ TESTE PASSOU: todas as 5 vendas reduziram o estoque na quantidade correta.\n";
} else {
    echo "❌ TESTE FALHOU: alguma venda não reduziu o estoque corretamente.\n";
}

echo "\n── Movimentações registradas (tipo_transacao = 'venda_nfce') ──\n";
$movs = MovimentacaoProduto::whereIn('codigo_transacao', array_column($resultados, 'nfce_id'))
->where('tipo_transacao', 'venda_nfce')
->where('tipo', 'reducao')
->orderBy('id')->get();
foreach ($movs as $m) {
    echo "  • Movimentação #{$m->id} | produto #{$m->produto_id} | {$m->quantidade} un | tipo: {$m->tipo} | transação: {$m->tipo_transacao} | NFCe #{$m->codigo_transacao}\n";
}
echo "\nFIM DO TESTE\n";
