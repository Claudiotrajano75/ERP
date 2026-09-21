<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$mdfe = App\Models\Mdfe::with(['veiculoTracao', 'infoDescarga.cidade', 'percurso', 'empresa'])->orderBy('id', 'desc')->first();

if (!$mdfe) {
    echo "Nenhum MDF-e encontrado.\n";
    exit;
}

echo "=== DADOS DO ÚLTIMO MDF-E ===\n";
echo "ID: " . $mdfe->id . "\n";
echo "Número MDF-e: " . $mdfe->mdfe_numero . " (Série: " . ($mdfe->serie ?? '1') . ")\n";
echo "Estado Emissão: " . $mdfe->estado_emissao . "\n";
echo "Chave: " . $mdfe->chave . "\n";
echo "Protocolo: " . $mdfe->protocolo . "\n";
echo "Data Início Viagem: " . $mdfe->data_inicio_viagem . "\n";
echo "UF Início: " . $mdfe->uf_inicio . " -> UF Fim: " . $mdfe->uf_fim . "\n";
echo "Valor da Carga: R$ " . number_format($mdfe->valor_carga, 2, ',', '.') . "\n";
echo "Quantidade da Carga (Peso): " . number_format($mdfe->quantidade_carga, 2, ',', '.') . " " . ($mdfe->unidade_medida ?? 'KG') . "\n";
echo "Produto Predominante: " . $mdfe->produto_pred_nome . " (NCM: " . $mdfe->produto_pred_ncm . ")\n";
echo "Tipo de Carga: " . $mdfe->tp_carga . "\n";

echo "\n--- VEÍCULO E MOTORISTA ---\n";
if ($mdfe->veiculoTracao) {
    echo "Veículo Tração Placa: " . $mdfe->veiculoTracao->placa . " (UF: " . $mdfe->veiculoTracao->uf . ", RNTrc: " . ($mdfe->veiculoTracao->rntrc ?? '--') . ")\n";
    echo "Tara Veículo: " . $mdfe->veiculoTracao->tara . " KG\n";
    echo "Capacidade Carga: " . $mdfe->veiculoTracao->capacidade . " KG\n";
} else {
    echo "Veículo Tração: Não vinculado no relacionamento\n";
}
echo "Motorista: " . $mdfe->condutor_nome . " (CPF: " . $mdfe->condutor_cpf . ")\n";

echo "\n--- DESCARREGAMENTOS (NOTAS FISCAIS) ---\n";
foreach ($mdfe->infoDescarga as $idx => $desc) {
    echo "[" . ($idx + 1) . "] Chave NFe: " . ($desc->nfe ? $desc->nfe->chave : ($desc->chave ?? '--')) . "\n";
    echo "    Cidade Descarregamento: " . ($desc->cidade ? $desc->cidade->nome . '/' . $desc->cidade->uf : 'ID: ' . $desc->cidade_id) . "\n";
    echo "    Qtd Rateio: " . $desc->quantidade_rateio . "\n";
}

echo "\n--- PERCURSO ---\n";
if ($mdfe->percurso && count($mdfe->percurso) > 0) {
    foreach ($mdfe->percurso as $p) {
        echo "UF Percurso: " . $p->uf . "\n";
    }
} else {
    echo "Nenhum estado intermediário de percurso (Estados limítrofes/diretos ou dentro da mesma rota).\n";
}
