<?php
// Gera e ASSINA localmente o XML da NFe — NÃO transmite à SEFAZ (seguro)
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$id = isset($argv[1]) ? (int)$argv[1] : 57;
$user = \App\Models\User::first();
\Auth::loginUsingId($user->id);

$nfe = \App\Models\Nfe::find($id);
echo "NFe $id: estado={$nfe->estado} tpNF={$nfe->tpNF} natureza=" . ($nfe->natureza ? $nfe->natureza->descricao : 'NULL') . "\n";

$empresa = \App\Models\Empresa::find($nfe->empresa_id);
$empresa = __objetoParaEmissao($empresa, $nfe->local_id);
echo "Certificado: " . ($empresa->arquivo ? 'presente (' . $empresa->arquivo . ')' : 'FALTANDO!') . "\n";

$nfe_service = new \App\Services\NFeService([
    "atualizacao" => date('Y-m-d h:i:s'),
    "tpAmb" => (int)$empresa->ambiente,
    "razaosocial" => $empresa->nome,
    "siglaUF" => $empresa->cidade->uf,
    "cnpj" => preg_replace('/[^0-9]/', '', $empresa->cpf_cnpj),
    "schemes" => "PL_010",
    "versao" => "4.00",
], $empresa);

// 1) GERAÇÃO
$doc = $nfe_service->gerarXml($nfe);
if (isset($doc['erros_xml'])) {
    echo "❌ ERROS NA GERAÇÃO: " . json_encode($doc['erros_xml']) . "\n";
    exit(1);
}
echo "✅ XML gerado: " . strlen($doc['xml']) . " bytes | chave: {$doc['chave']}\n";

// 2) ASSINATURA (100% local, usa o certificado)
try {
    $signed = $nfe_service->sign($doc['xml']);
    $assinado = simplexml_load_string($signed);
    $assinado->registerXPathNamespace('ds', 'http://www.w3.org/2000/09/xmldsig#');
    $assinaturas = $assinado->xpath('//ds:Signature');
    echo "✅ XML assinado: " . strlen($signed) . " bytes | assinaturas: " . count($assinaturas) . "\n";
    echo ($nfe->natureza->descricao ?? '') . " pronto para transmissão.\n";
} catch (\Throwable $e) {
    echo "❌ ERRO NA ASSINATURA: " . get_class($e) . ': ' . $e->getMessage() . "\n";
    echo '  linha ' . $e->getLine() . "\n";
    exit(1);
}
