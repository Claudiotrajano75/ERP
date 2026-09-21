<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$mdfes = App\Models\Mdfe::with(['veiculoTracao', 'infoDescarga.cidade', 'percurso', 'empresa'])->orderBy('id', 'desc')->take(10)->get();

foreach ($mdfes as $mdfe) {
    echo "--------------------------------------------------\n";
    echo "ID: " . $mdfe->id . " | MDF-e Nº: " . $mdfe->mdfe_numero . " | Estado: " . $mdfe->estado_emissao . "\n";
    echo "Empresa ID: " . $mdfe->empresa_id . " - " . ($mdfe->empresa->nome ?? '--') . "\n";
    echo "Chave: " . $mdfe->chave . "\n";
    echo "Protocolo: " . $mdfe->protocolo . "\n";
    echo "UF: " . $mdfe->uf_inicio . " -> " . $mdfe->uf_fim . "\n";
    echo "Valor Carga: R$ " . number_format($mdfe->valor_carga, 2, ',', '.') . " | Peso: " . number_format($mdfe->quantidade_carga, 2, ',', '.') . " " . ($mdfe->unidade_medida ?? 'KG') . "\n";
    echo "Produto: " . $mdfe->produto_pred_nome . " (NCM: " . $mdfe->produto_pred_ncm . ")\n";
    echo "Motorista: " . $mdfe->condutor_nome . " | Placa: " . ($mdfe->veiculoTracao ? $mdfe->veiculoTracao->placa : '--') . "\n";
    foreach ($mdfe->infoDescarga as $idx => $desc) {
        echo "  Doc [" . ($idx+1) . "] Cidade: " . ($desc->cidade ? $desc->cidade->nome . '/' . $desc->cidade->uf : $desc->cidade_id) . " | Chave NFe: " . ($desc->nfe ? $desc->nfe->chave : $desc->chave) . "\n";
    }
    if ($mdfe->percurso && count($mdfe->percurso) > 0) {
        $ufs = $mdfe->percurso->pluck('uf')->implode(', ');
        echo "  Percurso intermediário: " . $ufs . "\n";
    }
}
