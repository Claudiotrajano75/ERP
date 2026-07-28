<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Empresa;
use App\Models\Cliente;
use App\Models\Produto;
use App\Models\Nfe;
use App\Models\ItemNfe;
use App\Models\NaturezaOperacao;
use App\Services\NFeService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

// Mock login for User ID 1
$user = App\Models\User::find(1);
Auth::login($user);

try {
    $empresaId = 2; // COMERCIAL DE ARMARINHO BRASIL LTDA
    $empresa = Empresa::find($empresaId);
    $cliente = Cliente::where('empresa_id', $empresaId)->first();
    $produto = Produto::where('empresa_id', $empresaId)->where('status', 1)->first();
    $natureza = NaturezaOperacao::where('empresa_id', $empresaId)->first();

    if (!$empresa || !$cliente || !$produto || !$natureza) {
        die("Erro: Dados base incompletos para a Empresa $empresaId.\n");
    }

    echo "Usando:\n";
    echo "Empresa: " . $empresa->nome . "\n";
    echo "Cliente: " . $cliente->razao_social . "\n";
    echo "Produto: " . $produto->nome . "\n";

    DB::beginTransaction();

    $numero = 9704;
    $serie = 1;

    $nfe = Nfe::create([
        'empresa_id' => $empresa->id,
        'cliente_id' => $cliente->id,
        'natureza_id' => $natureza->id,
        'emissor_nome' => $empresa->nome,
        'emissor_cpf_cnpj' => $empresa->cpf_cnpj,
        'chave' => '',
        'numero' => $numero,
        'numero_serie' => $serie,
        'ambiente' => $empresa->ambiente,
        'estado' => 'novo',
        'total' => 100.00, // Valor mais alto para evitar arredondamento a zero nos impostos
        'valor_produtos' => 100.00,
        'tpNF' => 1,
        'finNFe' => 1,
        'user_id' => $user->id,
        'tipo_pagamento' => '01', // Dinheiro
        'local_id' => 1
    ]);

    ItemNfe::create([
        'nfe_id' => $nfe->id,
        'produto_id' => $produto->id,
        'quantidade' => 1,
        'valor_unitario' => 100.00,
        'sub_total' => 100.00,
        'perc_icms' => $produto->perc_icms,
        'perc_pis' => $produto->perc_pis,
        'perc_cofins' => $produto->perc_cofins,
        'perc_ipi' => $produto->perc_ipi,
        'perc_ibs' => 0.1, // Alíquota de transição 2026
        'perc_cbs' => 0.9, // Alíquota de transição 2026
        'cst_csosn' => $produto->cst_csosn ?? '101',
        'cst_pis' => $produto->cst_pis ?? '01',
        'cst_cofins' => $produto->cst_cofins ?? '01',
        'cst_ipi' => $produto->cst_ipi ?? '99',
        'cfop' => '5102',
        'ncm' => $produto->ncm,
    ]);

    DB::commit();

    echo "NF-e Criada com ID: " . $nfe->id . " e Número: " . $nfe->numero . "\n";
    echo "Iniciando Transmissão...\n";

    // Agora vamos chamar a lógica de emissão (simulando o controlador)
    $config = [
        "atualizacao" => date('Y-m-d h:i:s'),
        "tpAmb" => (int)$empresa->ambiente,
        "razaosocial" => $empresa->nome,
        "siglaUF" => $empresa->cidade->uf,
        "cnpj" => preg_replace('/[^0-9]/', '', $empresa->cpf_cnpj),
        "schemes" => "PL_010_V1", // Forçado PL_010
        "versao" => "4.00",
        "tokenIBPT" => "teste",
        "CSC" => $empresa->csc,
        "CSCid" => $empresa->csc_id,
    ];

    $nfe_service = new NFeService($config, $empresa);
    $doc = $nfe_service->gerarXml($nfe);

    if (isset($doc['xml'])) {
        echo "XML Gerado. Assinando...\n";
        
        // Acessar tools para assinar
        $reflectionService = new \ReflectionClass($nfe_service);
        $propertyTools = $reflectionService->getProperty('tools');
        $propertyTools->setAccessible(true);
        $tools = $propertyTools->getValue($nfe_service);
        
        $xmlAssinado = $tools->signNFe($doc['xml']);
        
        echo "Transmitindo...\n";
        $res = $nfe_service->transmitir($xmlAssinado, $nfe->chave);
        file_put_contents('emission_result.json', json_encode($res, JSON_PRETTY_PRINT));
        
        if (isset($res['sucesso']) && $res['sucesso']) {
            echo "SUCESSO! NF-e Emitida.\n";
            echo "Recibo: " . $res['recibo'] . "\n";
            echo "Protocolo: " . $res['protocolo'] . "\n";
            echo "Chave: " . $nfe->chave . "\n";
            
            // Atualizar banco
            $nfe->estado = 'aprovado';
            $nfe->recibo = $res['recibo'];
            $nfe->save();
            
            // Atualizar numero da empresa
            $empresa->numero_ultima_nfe_producao = $nfe->numero;
            $empresa->save();
            
        } else {
            echo "ERRO na transmissão (veja emission_result.json)\n";
        }
    } else {
        echo "ERRO ao gerar XML:\n";
        print_r($doc['erros_xml']);
    }

} catch (\Exception $e) {
    if (DB::transactionLevel() > 0) DB::rollBack();
    echo "ERRO FATAL: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
