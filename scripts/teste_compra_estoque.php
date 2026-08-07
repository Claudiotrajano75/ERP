<?php

/**
 * TESTE COMPLETO: Compra → Entrada no Estoque
 * -----------------------------------------------------------
 * Simula exatamente o que a TELA DE COMPRAS do ERP faz:
 *   1. Cria uma NFe de ENTRADA (compra) com itens
 *   2. Atualiza o valor de compra do produto
 *   3. Chama EstoqueUtil::incrementaEstoque() → AUMENTA a quantidade
 *   4. Registra MovimentacaoProduto (tipo_transacao = 'compra')
 *   5. Verifica o estoque antes/depois
 *
 * Cria 5 compras com 5 produtos diferentes e valida o aumento de estoque.
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Produto;
use App\Models\Estoque;
use App\Models\Nfe;
use App\Models\ItemNfe;
use App\Models\Fornecedor;
use App\Models\Localizacao;
use App\Models\Empresa;
use App\Models\MovimentacaoProduto;
use App\Utils\EstoqueUtil;
use Illuminate\Support\Facades\Auth;

echo "════════════════════════════════════════════════════════════\n";
echo "  TESTE: COMPRA → ENTRADA NO ESTOQUE (5 compras / 5 produtos)\n";
echo "════════════════════════════════════════════════════════════\n\n";

// ── 1. Preparação ──
$user = App\Models\User::find(1); // CodeTech
Auth::login($user);
$util = new EstoqueUtil();

$empresa = Empresa::find(2); // COMERCIAL DE ARMARINHO BRASIL LTDA (empresa da loja de teste)
if (!$empresa) { echo "ERRO: empresa 2 não encontrada\n"; exit(1); }

$fornecedor = Fornecedor::where('empresa_id', 2)->first();
if (!$fornecedor) { echo "ERRO: nenhum fornecedor da empresa 2\n"; exit(1); }

$local = Localizacao::where('id', 2)->first();
$local_id = $local ? $local->id : null;
echo "Usuário: {$user->name} | Empresa: {$empresa->nome} | Fornecedor: {$fornecedor->nome} | Local: {$local->nome}\n\n";

// ── 2. Criar 5 produtos de teste (com gerenciar estoque) ──
$produtos = [];
$dados = [
    ['nome' => 'TESTE FITILHO ALGODAO 2MM', 'valor' => 4.50,  'qtd' => 100],
    ['nome' => 'TESTE AGULHA CROCHE 3MM',   'valor' => 12.90, 'qtd' => 50],
    ['nome' => 'TESTE TECIDO TRICOLINE 1M', 'valor' => 25.00, 'qtd' => 30],
    ['nome' => 'TESTE BOTÃO METAL 15MM',    'valor' => 0.80,  'qtd' => 500],
    ['nome' => 'TESTE TESOURA COSTURA 9P',  'valor' => 18.50, 'qtd' => 25],
];

echo "── 2. Preparando 5 produtos de teste (reutiliza se já existir) ──\n";
foreach ($dados as $i => $d) {
    // Reutiliza o produto de teste se já existir (script idempotente)
    $p = Produto::where('empresa_id', 2)->where('nome', $d['nome'])->first();
    if (!$p) {
        $p = Produto::create([
            'empresa_id' => 2,
            'nome' => $d['nome'],
            'unidade' => 'UN',
            'status' => 1,
            'gerenciar_estoque' => 1,
            'valor_unitario' => $d['valor'],
            'valor_ecommerce' => $d['valor'],
            'ncm' => '5605.00.00',
            'origem' => '0',
            'cst_csosn' => '102',
            'cfop_estadual' => '5102',
            'perc_icms' => 0,
            'perc_pis' => 0,
            'perc_cofins' => 0,
            'perc_ipi' => 0,
        ]);
    }
    $produtos[] = ['produto' => $p, 'qtd' => $d['qtd'], 'valor' => $d['valor']];
    echo "  + #{$p->id} {$p->nome} — compra de {$d['qtd']} un @ R$ {$d['valor']}\n";
}
echo "\n";

// ── 3. Executar as 5 compras ──
echo "── 3. Executando as 5 compras (NFe de entrada + estoque) ──\n\n";

$resultados = [];
$numeroBase = Nfe::max('numero') ?? 0;

foreach ($produtos as $idx => $item) {
    $produto = $item['produto'];
    $qtd = $item['qtd'];
    $valor = $item['valor'];

    // Estoque ANTES
    $estoqueAntes = Estoque::where('produto_id', $produto->id)
        ->where('local_id', $local_id)->first();
    $qtdAntes = $estoqueAntes ? (float)$estoqueAntes->quantidade : 0;

    // ── 3.1 Cria a NFe de ENTRADA (compra) ──
    $numero = $numeroBase + $idx + 1;
    $nfe = Nfe::create([
        'empresa_id' => 2,
        'emissor_nome' => $empresa->nome,
        'emissor_cpf_cnpj' => $empresa->cpf_cnpj,
        'fornecedor_id' => $fornecedor->id,
        'chave' => '',
        'numero_serie' => 1,
        'numero' => $numero,
        'estado' => 'novo',
        'total' => $valor * $qtd,
        'valor_produtos' => $valor * $qtd,
        'desconto' => 0,
        'acrescimo' => 0,
        'valor_frete' => 0,
        'tipo' => 'entrada',
        'tpNF' => 0,
        'ambiente' => $empresa->ambiente,
        'local_id' => $local_id,
        'user_id' => $user->id,
        'caixa_id' => null,
    ]);

    // ── 3.2 Cria o item da NFe ──
    ItemNfe::create([
        'nfe_id' => $nfe->id,
        'produto_id' => $produto->id,
        'quantidade' => $qtd,
        'valor_unitario' => $valor,
        'sub_total' => $valor * $qtd,
        'perc_icms' => 0,
        'perc_pis' => 0,
        'perc_cofins' => 0,
        'perc_ipi' => 0,
        'cst_csosn' => '102',
        'cst_pis' => '49',
        'cst_cofins' => '49',
        'cst_ipi' => '50',
        'cfop' => '1102',
        'ncm' => '5605.00.00',
        'origem' => '0',
        'perc_red_bc' => 0,
    ]);

    // ── 3.3 Atualiza valor de compra (como a tela faz) ──
    $produto->valor_compra = $valor;
    $produto->save();

    // ── 3.4 INCREMENTA O ESTOQUE (é aqui que a quantidade AUMENTA) ──
    if ($produto->gerenciar_estoque) {
        $util->incrementaEstoque($produto->id, $qtd, null, $local_id);
    }

    // ── 3.5 Registra a movimentação (tipo_transacao = 'compra') ──
    $util->movimentacaoProduto($produto->id, $qtd, 'incremento', $nfe->id, 'compra', $user->id);

    // Estoque DEPOIS
    $estoqueDepois = Estoque::where('produto_id', $produto->id)
        ->where('local_id', $local_id)->first();
    $qtdDepois = $estoqueDepois ? (float)$estoqueDepois->quantidade : 0;

    $resultados[] = [
        'produto' => $produto->nome,
        'qtd_comprada' => $qtd,
        'antes' => $qtdAntes,
        'depois' => $qtdDepois,
        'aumento' => $qtdDepois - $qtdAntes,
        'nfe_id' => $nfe->id,
        'nfe_numero' => $nfe->numero,
        'ok' => ($qtdDepois - $qtdAntes) == $qtd,
    ];

    $numCompra = $idx + 1;
    echo "  COMPRA #{$numCompra}: {$produto->nome}\n";
    echo "    ├─ NFe entrada nº {$nfe->numero} (id {$nfe->id}) criada\n";
    echo "    ├─ Item: {$qtd} un @ R$ {$valor} = R$ " . number_format($valor * $qtd, 2, ',', '.') . "\n";
    echo "    ├─ incrementaEstoque() chamado\n";
    echo "    ├─ Estoque: {$qtdAntes} → {$qtdDepois}  (aumento de {$qtd})\n";
    echo "    └─ Movimentação registrada (compra)\n\n";
}

// ── 4. Resumo e validação ──
echo "════════════════════════════════════════════════════════════\n";
echo "  RESULTADO FINAL\n";
echo "════════════════════════════════════════════════════════════\n";
$todosOk = true;
printf("%-30s %8s %8s %8s %8s %6s\n", 'PRODUTO', 'COMPRA', 'ANTES', 'DEPOIS', 'AUMENTO', 'OK');
printf("%-30s %8s %8s %8s %8s %6s\n", str_repeat('-', 30), str_repeat('-', 8), str_repeat('-', 8), str_repeat('-', 8), str_repeat('-', 8), str_repeat('-', 6));
foreach ($resultados as $r) {
    if (!$r['ok']) $todosOk = false;
    printf("%-30s %8d %8d %8d %8d %6s\n", $r['produto'], $r['qtd_comprada'], $r['antes'], $r['depois'], $r['aumento'], $r['ok'] ? '✓' : '✗');
}

echo "\n──────────────────────────────────────────────────────────\n";
if ($todosOk) {
    echo "✅ TESTE PASSOU: todas as 5 compras aumentaram o estoque na quantidade correta.\n";
} else {
    echo "❌ TESTE FALHOU: alguma compra não aumentou o estoque corretamente.\n";
}

echo "\n── Movimentações registradas (tipo_transacao = 'compra') ──\n";
$movs = MovimentacaoProduto::whereIn('codigo_transacao', array_column($resultados, 'nfe_id'))
->where('tipo_transacao', 'compra')
->where('tipo', 'incremento')
->orderBy('id')->get();
foreach ($movs as $m) {
    echo "  • Movimentação #{$m->id} | produto #{$m->produto_id} | {$m->quantidade} un | tipo: {$m->tipo} | transação: {$m->tipo_transacao} | NFe #{$m->codigo_transacao}\n";
}
echo "\nFIM DO TESTE\n";
