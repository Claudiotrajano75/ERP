<?php
// Renderiza nfe.create diretamente (venda e compra) e confere a natureza pré-selecionada
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = \App\Models\User::first();
\Auth::loginUsingId($user->id);
request()->merge(['empresa_id' => 2]);

$empresa = \App\Models\Empresa::find(2);
$transportadoras = \App\Models\Transportadora::where('empresa_id', 2)->get();
$cidades = \App\Models\Cidade::all();
$naturezas = \App\Models\NaturezaOperacao::where('empresa_id', 2)->get();
$naturezaPadrao = $naturezas->where('padrao', 1)->first();
$numeroNfe = \App\Models\Nfe::lastNumero($empresa);
$produtos = \App\Models\Produto::where('empresa_id', 2)->limit(10)->get();

// caixa temporário
$caixa = \App\Models\Caixa::create([
    'empresa_id' => 2,
    'usuario_id' => $user->id,
    'valor_abertura' => 0,
    'status' => 1,
    'local_id' => 2,
    'observacao' => 'caixa temporario de teste',
]);

function naturezaSelecionada($html)
{
    if (preg_match('/<select[^>]*name="natureza_id"[^>]*>(.*?)<\/select>/s', $html, $m)) {
        $sel = $m[1];
        preg_match_all('/<option([^>]*)>/', $sel, $opts);
        foreach ($opts[1] as $opt) {
            if (strpos($opt, 'selected') !== false) {
                if (preg_match('/value="(\d+)"/', $opt, $v)) {
                    return (int)$v[1];
                }
            }
        }
    }
    return null;
}

$nomes = $naturezas->pluck('descricao', 'id')->all();

// ── VENDA ──
$isOrcamento = 0;
echo "=== VENDA ===\n";
try {
    $html = view('nfe.create', compact('transportadoras', 'cidades', 'naturezas', 'numeroNfe', 'empresa', 'caixa', 'isOrcamento', 'naturezaPadrao'))->render();
    $sel = naturezaSelecionada($html);
    echo 'natureza pré-selecionada: ' . ($sel ? "id $sel ({$nomes[$sel]})" : 'nenhuma') . "\n";
    echo ($sel == 2 ? '✅ Venda Consumidor Final (correto!)' : '❌') . "\n";
} catch (\Throwable $e) {
    echo 'ERRO: ' . $e->getMessage() . "\n";
}

// ── COMPRA ──
$isCompra = 1;
echo "\n=== COMPRA ===\n";
try {
    $html = view('nfe.create', compact('produtos', 'transportadoras', 'cidades', 'naturezas', 'numeroNfe', 'isCompra', 'empresa', 'caixa', 'isOrcamento'))->render();
    $sel = naturezaSelecionada($html);
    echo 'natureza pré-selecionada: ' . ($sel ? "id $sel ({$nomes[$sel]})" : 'nenhuma') . "\n";
    echo ($sel == 5 ? '✅ Compra de Mercadorias (correto!)' : '❌') . "\n";
} catch (\Throwable $e) {
    echo 'ERRO: ' . $e->getMessage() . "\n";
}

// limpa caixa temporário
$caixa->delete();
echo "\nCaixa temporário removido.\n";
