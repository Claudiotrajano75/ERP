<?php
/**
 * Script de Teste IBS/CBS em Homologação
 * 
 * Uso: php scripts/testar_ibscbs_homologacao.php [empresa_id] [modelo]
 *   empresa_id: ID da empresa configurada em homologação (default: 2)
 *   modelo: 55 para NF-e, 65 para NFC-e (default: 65)
 * 
 * Exemplos:
 *   php scripts/testar_ibscbs_homologacao.php 2 65   # NFCe em homologação
 *   php scripts/testar_ibscbs_homologacao.php 2 55   # NFe em homologação
 */require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Empresa;
use App\Models\Cliente;
use App\Models\Produto;
use App\Models\Nfe;
use App\Models\Nfce;
use App\Models\ItemNfe;
use App\Models\ItemNfce;
use App\Models\NaturezaOperacao;
use App\Services\NFeService;
use App\Services\NFCeService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use NFePHP\Common\Certificate;
use NFePHP\NFe\Tools;

// Configurações
$empresaId = isset($argv[1]) ? (int)$argv[1] : 2;
$modelo = isset($argv[2]) ? (int)$argv[2] : 65; // 65 = NFCe, 55 = NFe
$force = in_array('--force', $argv);

// Se for Simples e não tiver --force, muda CRT pra Regime Normal (CRT=3) pra testar IBS/CBS
$alterarCRT = in_array('--alterar-crt', $argv);

echo "============================================\n";
echo "  TESTE IBS/CBS EM HOMOLOGAÇÃO\n";
echo "============================================\n\n";

// Mock login
$user = App\Models\User::find(1);
if (!$user) {
    die("Erro: Usuário ID 1 não encontrado.\n");
}
Auth::login($user);

try {
    // 1. Carregar empresa
    $empresa = Empresa::find($empresaId);
    if (!$empresa) {
        die("Erro: Empresa ID $empresaId não encontrada.\n");
    }

    // Forçar ambiente de homologação para o teste
    $ambienteOriginal = $empresa->ambiente;
    $empresa->ambiente = 2; // Homologação

    echo "Empresa: {$empresa->nome} (ID: {$empresa->id})\n";
    echo "UF: {$empresa->cidade->uf}\n";
    echo "CRT: {$empresa->getCRT()} (" . ($empresa->tributacao) . ")\n";
    echo "Modelo: " . ($modelo == 65 ? "NFC-e (65)" : "NF-e (55)") . "\n";
    echo "Ambiente: Homologação (2)\n\n";

    // 2. Verificar se IBS/CBS é aplicável
    $crt = $empresa->getCRT();
    $isExemptIBSCBS = ($crt == 1 || $crt == 4);
    if ($isExemptIBSCBS) {
        echo "⚠️  ATENÇÃO: Empresa é Simples Nacional (CRT=$crt).\n";
        echo "   IBS/CBS são dispensados até 04/01/2027 para Simples.\n";
        echo "   Para testar, mude a tributação para 'Regime Normal' (CRT=3).\n\n";
        if (!$force) {
            echo "   Deseja continuar mesmo assim? (O XML NÃO terá tags IBS/CBS)\n";
            echo "   Use --force para pular esta confirmação.\n";
            echo "   Pressione Ctrl+C para cancelar ou Enter para continuar...\n";
            fgets(STDIN);
        }

        $tributacaoOriginal = $empresa->tributacao;
        if ($alterarCRT) {
            echo "   ⚠️  Alterando tributação para 'Regime Normal' (CRT=3) para teste...\n";
            $empresa->tributacao = 'Regime Normal';
            $empresa->save();
        }
    } else {
        echo "✅ IBS/CBS aplicável (CRT=$crt). Tags serão geradas.\n\n";
        $tributacaoOriginal = $empresa->tributacao;
    }

    // 3. Carregar dados complementares
    // Procurar cliente consumidor final (CPF) para evitar IE do destinatário (cStat=729)
    $cliente = Cliente::where('empresa_id', $empresaId)
        ->where('consumidor_final', 1)
        ->where('contribuinte', 0)
        ->where('cpf_cnpj', 'not like', '%.%')
        ->where(\DB::raw('LENGTH(REPLACE(REPLACE(cpf_cnpj, ".", ""), "-", ""))'), '!=', 14)
        ->first();
    if (!$cliente) {
        // Criar cliente de teste específico para NFC-e (CPF, consumidor final)
        echo "Criando cliente de teste (CPF, consumidor final)...\n";
        $cliente = Cliente::create([
            'empresa_id' => $empresaId,
            'razao_social' => 'CONSUMIDOR TESTE IBS/CBS',
            'cpf_cnpj' => '529.982.247-25',
            'ie' => '',
            'rua' => 'Rua Teste',
            'numero' => '123',
            'bairro' => 'Centro',
            'cidade_id' => $empresa->cidade_id,
            'cep' => '00000-000',
            'contribuinte' => 0,
            'consumidor_final' => 1,
            'status' => 1,
        ]);
    }

    $natureza = NaturezaOperacao::where('empresa_id', $empresaId)->first();
    if (!$natureza) {
        die("Erro: Nenhuma natureza de operação encontrada. Configure primeiro.\n");
    }

    // Verificar se natureza tem IBS/CBS configurado
    echo "Natureza: {$natureza->descricao}\n";
    echo "  perc_ibs: " . ($natureza->perc_ibs ?? 'não configurado') . "\n";
    echo "  perc_cbs: " . ($natureza->perc_cbs ?? 'não configurado') . "\n";
    if (!$natureza->perc_ibs || !$natureza->perc_cbs) {
        echo "  ⚠️  Usando alíquotas padrão de teste 2026: IBS=0.1%, CBS=0.9%\n";
    }

    $produto = Produto::where('empresa_id', $empresaId)->where('status', 1)->first();
    if (!$produto) {
        // Criar produto de teste
        echo "Criando produto de teste...\n";
        $produto = Produto::create([
            'empresa_id' => $empresaId,
            'nome' => 'PRODUTO TESTE IBS/CBS',
            'ncm' => '84713010',
            'unidade' => 'UN',
            'valor_venda' => 100.00,
            'status' => 1,
            'perc_icms' => 18.00,
            'perc_pis' => 1.65,
            'perc_cofins' => 7.60,
            'perc_ipi' => 0.00,
            'perc_ibs' => 0.10,
            'perc_cbs' => 0.90,
            'cst_csosn' => '101',
            'cst_pis' => '01',
            'cst_cofins' => '01',
            'cst_ipi' => '99',
            'cfop_padrao' => '5102',
        ]);
    }

    echo "Produto: {$produto->nome}\n";
    echo "  perc_ibs: " . ($produto->perc_ibs ?? 'não configurado') . "\n";
    echo "  perc_cbs: " . ($produto->perc_cbs ?? 'não configurado') . "\n\n";

    // Define CST/CSOSN apropriado conforme regime tributário
    $cstValue = ($alterarCRT || $crt == 2 || $crt == 3) ? '00' : ($produto->cst_csosn ?: '101');
    $percIcms = ($alterarCRT || $crt == 2 || $crt == 3) ? 18.00 : ($produto->perc_icms ?: 18);

    // 4. Gerar número sequencial para teste
    if ($modelo == 65) {
        $ultimoNumero = $empresa->numero_ultima_nfce_homologacao;
        $serie = $empresa->numero_serie_nfce ?: 1;
    } else {
        $ultimoNumero = $empresa->numero_ultima_nfe_homologacao;
        $serie = $empresa->numero_serie_nfe ?: 1;
    }
    $numero = $ultimoNumero + 1;
    $valorTotal = 100.00;

    echo "Série: $serie | Número: $numero\n";
    echo "Valor: R$ " . number_format($valorTotal, 2, ',', '.') . "\n\n";

    // 5. Criar documento fiscal de teste
    DB::beginTransaction();

    if ($modelo == 65) {
        // NFC-e
        $nfce = Nfce::create([
            'empresa_id' => $empresa->id,
            'cliente_id' => $cliente->id,
            'natureza_id' => $natureza->id,
            'chave' => '',
            'numero' => $numero,
            'numero_serie' => $serie,
            'ambiente' => 2, // Homologação
            'estado' => 'novo',
            'total' => $valorTotal,
            'dinheiro_recebido' => $valorTotal,
            'tipo_pagamento' => '01', // Dinheiro
            'user_id' => $user->id,
            'local_id' => 1,
        ]);

        ItemNfce::create([
            'nfce_id' => $nfce->id,
            'produto_id' => $produto->id,
            'quantidade' => 1,
            'valor_unitario' => $valorTotal,
            'sub_total' => $valorTotal,
            'perc_icms' => $percIcms,
            'perc_pis' => $produto->perc_pis ?: 0,
            'perc_cofins' => $produto->perc_cofins ?: 0,
            'perc_ipi' => $produto->perc_ipi ?: 0,
            'perc_ibs' => $produto->perc_ibs ?: 0.10,
            'perc_cbs' => $produto->perc_cbs ?: 0.90,
            'cst_csosn' => $cstValue,
            'cst_pis' => $produto->cst_pis ?: '01',
            'cst_cofins' => $produto->cst_cofins ?: '01',
            'cst_ipi' => $produto->cst_ipi ?: '99',
            'cfop' => $produto->cfop_padrao ?: '5102',
            'ncm' => $produto->ncm ?: '84713010',
        ]);

        DB::commit();

        echo "✅ NFC-e criada: ID {$nfce->id}, Número {$nfce->numero}\n";

    } else {
        // NF-e
        $nfe = Nfe::create([
            'empresa_id' => $empresa->id,
            'cliente_id' => $cliente->id,
            'natureza_id' => $natureza->id,
            'emissor_nome' => $empresa->nome,
            'emissor_cpf_cnpj' => $empresa->cpf_cnpj,
            'chave' => '',
            'numero' => $numero,
            'numero_serie' => $serie,
            'ambiente' => 2, // Homologação
            'estado' => 'novo',
            'total' => $valorTotal,
            'valor_produtos' => $valorTotal,
            'tpNF' => 1,
            'finNFe' => 1,
            'user_id' => $user->id,
            'tipo_pagamento' => '01', // Dinheiro
            'local_id' => 1,
        ]);

        ItemNfe::create([
            'nfe_id' => $nfe->id,
            'produto_id' => $produto->id,
            'quantidade' => 1,
            'valor_unitario' => $valorTotal,
            'sub_total' => $valorTotal,
            'perc_icms' => $percIcms,
            'perc_pis' => $produto->perc_pis ?: 0,
            'perc_cofins' => $produto->perc_cofins ?: 0,
            'perc_ipi' => $produto->perc_ipi ?: 0,
            'perc_ibs' => $produto->perc_ibs ?: 0.10,
            'perc_cbs' => $produto->perc_cbs ?: 0.90,
            'cst_csosn' => $cstValue,
            'cst_pis' => $produto->cst_pis ?: '01',
            'cst_cofins' => $produto->cst_cofins ?: '01',
            'cst_ipi' => $produto->cst_ipi ?: '99',
            'cfop' => $produto->cfop_padrao ?: '5102',
            'ncm' => $produto->ncm ?: '84713010',
        ]);

        DB::commit();

        echo "✅ NF-e criada: ID {$nfe->id}, Número {$nfe->numero}\n";
    }

    // 6. Gerar e transmitir XML
    echo "\n--- GERANDO XML ---\n";

    $config = [
        "atualizacao" => date('Y-m-d h:i:s'),
        "tpAmb" => 2, // Homologação
        "razaosocial" => $empresa->nome,
        "siglaUF" => $empresa->cidade->uf,
        "cnpj" => preg_replace('/[^0-9]/', '', $empresa->cpf_cnpj),
        "schemes" => "PL_010_V1.30",
        "versao" => "4.00",
        "tokenIBPT" => "teste",
        "CSC" => $empresa->csc,
        "CSCid" => $empresa->csc_id,
    ];

    if ($modelo == 65) {
        $service = new NFCeService($config, $empresa);
        $documento = $nfce;
        $xmlPath = 'xml_nfce/';
    } else {
        $service = new NFeService($config, $empresa);
        $documento = $nfe;
        $xmlPath = 'xml_nfe/';
    }

    try {
        $doc = $service->gerarXml($documento);

        if (isset($doc['erros_xml'])) {
            echo "❌ ERRO ao gerar XML:\n";
            print_r($doc['erros_xml']);
            DB::rollBack();
            exit(1);
        }

        echo "✅ XML gerado com sucesso!\n";
        echo "Chave: {$doc['chave']}\n";

        // Salvar XML gerado (antes da assinatura) para inspeção
        $xmlFile = public_path("{$xmlPath}teste_ibscbs_{$doc['chave']}_gerado.xml");
        file_put_contents($xmlFile, $doc['xml']);
        echo "XML (sem assinatura) salvo em: $xmlFile\n";

        // 7. Assinar XML
        echo "\n--- ASSINANDO XML ---\n";
        $xmlAssinado = $service->sign($doc['xml']);
        echo "✅ XML assinado com sucesso!\n";

        // Salvar XML assinado
        $signedFile = public_path("{$xmlPath}teste_ibscbs_{$doc['chave']}_assinado.xml");
        file_put_contents($signedFile, $xmlAssinado);
        echo "XML assinado salvo em: $signedFile\n";

        // 8. Verificar se o XML contém as tags IBS/CBS
        echo "\n--- VERIFICANDO TAGS IBS/CBS NO XML ---\n";
        $dom = new \DOMDocument();
        $dom->loadXML($doc['xml']);
        
        $ibscbsTags = $dom->getElementsByTagName('IBSCBS');
        $ibscbsTotTags = $dom->getElementsByTagName('IBSCBSTot');
        
        echo "Tags IBSCBS (por item): " . $ibscbsTags->length . "\n";
        echo "Tags IBSCBSTot (totalizador): " . $ibscbsTotTags->length . "\n";

        if ($ibscbsTags->length > 0) {
            echo "✅ Tags IBS/CBS encontradas no XML!\n";
            
            // Exibir detalhes da primeira tag IBSCBS
            $firstIBSCBS = $ibscbsTags->item(0);
            $cst = $firstIBSCBS->getElementsByTagName('CST')->item(0)->nodeValue ?? 'N/A';
            $vBC = $firstIBSCBS->getElementsByTagName('vBC')->item(0)->nodeValue ?? 'N/A';
            
            $gIBSUF = $firstIBSCBS->getElementsByTagName('gIBSUF')->item(0);
            $pIBSUF = $gIBSUF ? ($gIBSUF->getElementsByTagName('pIBSUF')->item(0)->nodeValue ?? 'N/A') : 'N/A';
            $vIBSUF = $gIBSUF ? ($gIBSUF->getElementsByTagName('vIBSUF')->item(0)->nodeValue ?? 'N/A') : 'N/A';
            
            $gCBS = $firstIBSCBS->getElementsByTagName('gCBS')->item(0);
            $pCBS = $gCBS ? ($gCBS->getElementsByTagName('pCBS')->item(0)->nodeValue ?? 'N/A') : 'N/A';
            $vCBS = $gCBS ? ($gCBS->getElementsByTagName('vCBS')->item(0)->nodeValue ?? 'N/A') : 'N/A';
            
            $vIBS = $firstIBSCBS->getElementsByTagName('vIBS')->item(0)->nodeValue ?? 'N/A';
            $vCBSTag = $firstIBSCBS->getElementsByTagName('vCBS')->item(0)->nodeValue ?? 'N/A';
            
            echo "\nDetalhes do 1º item:\n";
            echo "  CST: $cst\n";
            echo "  vBC: $vBC\n";
            echo "  IBS UF: {$pIBSUF}% = R$ {$vIBSUF}\n";
            echo "  CBS: {$pCBS}% = R$ {$vCBS}\n";
        } else {
            echo "⚠️  Nenhuma tag IBS/CBS encontrada (Simples Nacional CRT=1/4?)\n";
        }

        if ($ibscbsTotTags->length > 0) {
            echo "\n✅ Totalizador IBSCBSTot (W03) encontrado!\n";
        } else {
            echo "\n❌ Totalizador IBSCBSTot (W03) NÃO encontrado!\n";
        }

        // 9. Transmitir para SEFAZ
        echo "\n--- TRANSMITINDO PARA SEFAZ (Homologação) ---\n";
        
        $resultado = $service->transmitir($xmlAssinado, $doc['chave']);
        
        // Salvar resultado
        $resultFile = public_path("{$xmlPath}teste_ibscbs_resultado_{$doc['chave']}.json");
        file_put_contents($resultFile, json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        echo "\nResultado salvo em: $resultFile\n";

        if (isset($resultado['erro']) && $resultado['erro'] == 0) {
            echo "\n============================================\n";
            echo "  ✅ SUCESSO! Documento transmitido!\n";
            echo "============================================\n";
            echo "Protocolo: {$resultado['success']}\n";
            echo "Chave: {$doc['chave']}\n\n";

            // Atualizar documento no banco
            $documento->estado = 'aprovado';
            $documento->chave = $doc['chave'];
            $documento->recibo = $resultado['success'];
            $documento->save();

            // Atualizar numeração da empresa
            if ($modelo == 65) {
                $empresa->numero_ultima_nfce_homologacao = $numero;
            } else {
                $empresa->numero_ultima_nfe_homologacao = $numero;
            }
            $empresa->save();

        } else {
            echo "\n============================================\n";
            echo "  ❌ FALHA NA TRANSMISSÃO\n";
            echo "============================================\n";
            if (isset($resultado['error'])) {
                if (is_array($resultado['error'])) {
                    echo "Erro (array):\n";
                    print_r($resultado['error']);
                } else {
                    echo "Erro: {$resultado['error']}\n";
                }
            }
            if (isset($resultado['cStat'])) {
                echo "cStat: {$resultado['cStat']}\n";
            }
            echo "\nDetalhes completos:\n";
            print_r($resultado);
        }

    } catch (\Exception $e) {
        echo "\n❌ EXCEÇÃO: " . $e->getMessage() . "\n";
        echo $e->getTraceAsString() . "\n";
        if (DB::transactionLevel() > 0) {
            DB::rollBack();
        }
    }

    // Restaurar ambiente e tributação original
    $empresa->ambiente = $ambienteOriginal;
    if ($alterarCRT && $empresa->tributacao != $tributacaoOriginal) {
        $empresa->tributacao = $tributacaoOriginal;
        $empresa->save();
        echo "\n✅ Tributação restaurada para: $tributacaoOriginal\n";
    }

    echo "\n============================================\n";
    echo "  TESTE FINALIZADO\n";
    echo "============================================\n";

} catch (\Exception $e) {
    if (DB::transactionLevel() > 0) {
        DB::rollBack();
    }
    echo "\n❌ ERRO FATAL: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
