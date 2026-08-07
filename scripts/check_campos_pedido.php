<?php
// Confere se todos os campos/labels do pedido continuam no HTML renderizado
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$id = isset($argv[1]) ? (int)$argv[1] : 57;
$user = \App\Models\User::first();
\Auth::loginUsingId($user->id);

$nfe = \App\Models\Nfe::find($id);
$config = \App\Models\Empresa::where('id', $nfe->empresa_id)->first();
$item = $nfe;

$html = view('nfe.imprimir', compact('config', 'item'))->render();

// busca no HTML-fonte (CSS text-transform: uppercase faz a exibição em caixa alta)
$campos = [
    'Título (Compra ou Venda)' => ['PEDIDO DE COMPRA', 'PEDIDO DE VENDA'],
    'Emissão' => 'Emissão:',
    'Dados da Empresa' => 'Dados da Empresa',
    'Razão Social' => 'Razão Social:',
    'Documento' => 'Documento:',
    'Endereço' => 'Endereço:',
    'Complemento' => 'Complemento:',
    'CEP' => 'CEP:',
    'Telefone' => 'Telefone:',
    'Email' => 'Email:',
    'Fornecedor/Cliente' => 'Dados do Fornecedor',
    'Informações do Documento' => 'Informações do Documento',
    'Nº Doc' => 'Nº Doc:',
    'Forma de Pagamento' => 'Forma de Pagamento:',
    'Frete por Conta' => 'Frete por Conta:',
    'Data da Venda' => 'Data da Venda:',
    'Data de Entrega' => 'Data de Entrega:',
    'Mercadorias' => 'Mercadorias',
    'Cod/Ref' => 'Cod/Ref',
    'Descrição' => 'Descrição',
    'Qtd' => 'Qtd.',
    'Vl Uni' => 'Vl Uni',
    'Vl Liq' => 'Vl Liq',
    'Quantidade Total' => 'Quantidade Total:',
    'Valor Total dos Itens' => 'Valor Total dos Itens:',
    'Desconto' => 'Desconto (-)',
    'Acréscimo' => 'Acréscimo (+)',
    'Frete (+)' => 'Frete (+)',
    'Valor Líquido' => 'Valor Líquido',
    'Fatura (condicional)' => 'Fatura',
    'Vencimento (condicional)' => 'Vencimento',
    'Valor Fatura (condicional)' => 'Valor',
    'Observação (condicional)' => 'Observação:',
    'Identificação Produtos (condicional)' => 'Identificação dos Produtos',
    'Assinatura empresa' => 'COMERCIAL DE ARMARINHO BRASIL LTDA',
];

$faltando = [];
$condicionais = 0;
foreach ($campos as $nome => $texto) {
    $textos = is_array($texto) ? $texto : [$texto];
    $achou = false;
    foreach ($textos as $t) {
        if (stripos($html, $t) !== false) {
            $achou = true;
            break;
        }
    }
    if (!$achou) {
        if (strpos($nome, '(condicional)') !== false) {
            $condicionais++;
            echo "ℹ Bloco condicional sem dados (ok, não renderiza): $nome\n";
        } else {
            $faltando[] = "$nome ('" . implode("', '", $textos) . "')";
        }
    }
}

echo "HTML: " . strlen($html) . " bytes\n";
if (count($faltando) == 0) {
    echo "✔ TODOS os campos obrigatórios estão presentes! ($condicionais blocos condicionais sem dados nesta NFe)\n";
} else {
    echo "✘ FALTANDO (" . count($faltando) . "):\n" . implode("\n", $faltando) . "\n";
}
