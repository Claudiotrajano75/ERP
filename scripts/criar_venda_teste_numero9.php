<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Nfe;
use App\Models\ItemNfe;

// Confere se o número 9 está livre para a empresa 2
$existe = Nfe::where('empresa_id', 2)->where('numero', 9)->exists();
if ($existe) {
    echo "ABORTADO: já existe NFe com número 9\n";
    exit(1);
}

$nfe = new Nfe();
$nfe->empresa_id = 2;
$nfe->natureza_id = 2;                       // Venda Consumidor Final
$nfe->emissor_nome = 'COMERCIAL DE ARMARINHO BRASIL LTDA';
$nfe->emissor_cpf_cnpj = '41556663000174';
$nfe->ambiente = 1;                          // produção
$nfe->cliente_id = 1;                        // MSG INDUSTRIA E COMERCIO DE ALIMENTOS LTDA
$nfe->numero_serie = 1;
$nfe->numero = 9;                            // próximo número livre
$nfe->numero_sequencial = 29;                // próximo sequencial (maior atual: 28)
$nfe->estado = 'novo';
$nfe->total = 1.00;
$nfe->valor_produtos = 1.00;
$nfe->tpNF = 1;                              // saída (venda)
$nfe->tpEmis = 1;
$nfe->finNFe = 1;
$nfe->tipo_pagamento = '01';
$nfe->local_id = 1;
$nfe->user_id = 1;
$nfe->api = 0;
$nfe->gerar_conta_receber = 0;
$nfe->gerar_conta_pagar = 0;
$nfe->orcamento = 0;
$nfe->contigencia = 0;
$nfe->sequencia_cce = 0;
$nfe->save();

$item = new ItemNfe();
$item->nfe_id = $nfe->id;
$item->produto_id = 4;                       // Copo Descartável (mesmo da NFe 8)
$item->quantidade = 1.00;
$item->valor_unitario = 1.00;
$item->sub_total = 1.00;
$item->perc_icms = 0.00;
$item->perc_pis = 0.00;
$item->perc_cofins = 0.00;
$item->perc_ipi = 0.00;
$item->perc_ibs = 0.05;
$item->perc_cbs = 0.10;
$item->cst_csosn = 102;
$item->cst_pis = 07;
$item->cst_cofins = 07;
$item->cst_ipi = 50;
$item->vbc_icms = 0.00;
$item->vbc_pis = 0.00;
$item->vbc_cofins = 0.00;
$item->vbc_ipi = 0.00;
$item->cfop = 5102;
$item->ncm = '3924.10.00';
$item->origem = 0;
$item->save();

echo "NFe de teste criada: id={$nfe->id} numero=9 serie=1 estado={$nfe->estado}\n";
echo "Item criado: produto 4, qtd 1, cfop 5102, csosn 102\n";
