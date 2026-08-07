<?php
// Cria a natureza "Compra de Mercadorias" e aplica nas NFes de compra sem natureza
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$empresa_id = 2;

// 1) Cria a natureza (idempotente — só se não existir)
$natureza = \App\Models\NaturezaOperacao::where('empresa_id', $empresa_id)
    ->where('descricao', 'Compra de Mercadorias')->first();

if ($natureza == null) {
    $natureza = \App\Models\NaturezaOperacao::create([
        'empresa_id' => $empresa_id,
        'descricao' => 'Compra de Mercadorias',
        'cst_csosn' => '102',
        'cst_pis' => '01',
        'cst_cofins' => '01',
        'cst_ipi' => '53',
        'cfop_estadual' => '5102',
        'cfop_outro_estado' => '6102',
        'cfop_entrada_estadual' => '1102',
        'cfop_entrada_outro_estado' => '2102',
        'perc_icms' => 0,
        'perc_pis' => 0,
        'perc_cofins' => 0,
        'perc_ipi' => 0,
        'perc_ibs' => 0.05,
        'perc_cbs' => 0.10,
        'padrao' => 0,
        'sobrescrever_cfop' => 1,
    ]);
    echo "Natureza criada: id {$natureza->id} - {$natureza->descricao}\n";
} else {
    echo "Natureza já existia: id {$natureza->id} - {$natureza->descricao}\n";
}

// 2) Aplica nas NFes de compra (tpNF=0) sem natureza
$nfes = \App\Models\Nfe::where('empresa_id', $empresa_id)
    ->whereNull('natureza_id')
    ->where('tpNF', 0)
    ->get();

$cont = 0;
foreach ($nfes as $nfe) {
    $nfe->natureza_id = $natureza->id;
    $nfe->save();
    $cont++;
}

echo "NFes atualizadas: $cont\n";

// 3) Verifica quantas ainda estão sem natureza
$restantes = \App\Models\Nfe::whereNull('natureza_id')->count();
echo "NFes ainda sem natureza: $restantes\n";
