<?php
// Exibe o XML temporário da NFe para inspeção
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$id = isset($argv[1]) ? (int)$argv[1] : 57;
$user = \App\Models\User::first();
\Auth::loginUsingId($user->id);

$item = \App\Models\Nfe::find($id);
$empresa = \App\Models\Empresa::find($item->empresa_id);
$empresa = __objetoParaEmissao($empresa, $item->local_id);

$nfe_service = new \App\Services\NFeService([
    "atualizacao" => date('Y-m-d h:i:s'),
    "tpAmb" => (int)$empresa->ambiente,
    "razaosocial" => $empresa->nome,
    "siglaUF" => $empresa->cidade->uf,
    "cnpj" => preg_replace('/[^0-9]/', '', $empresa->cpf_cnpj),
    "schemes" => "PL_010",
    "versao" => "4.00",
], $empresa);

$doc = $nfe_service->gerarXml($item);

if (!isset($doc['erros_xml'])) {
    $xml = simplexml_load_string($doc['xml']);
    $xml->registerXPathNamespace('n', 'http://www.portalfiscal.inf.br/nfe');
    $inf = $xml->xpath('//n:infNFe');
    if (empty($inf)) {
        echo "XML gerado, mas infNFe não encontrada. Primeiros 400 chars:\n" . substr($doc['xml'], 0, 400) . "\n";
    } else {
        $nfe = $inf[0];
        echo 'natOp: ' . (string)$nfe->ide->natOp . "\n";
        echo 'tpNF: ' . (string)$nfe->ide->tpNF . "\n";
        foreach ($nfe->det as $det) {
            $icms = $det->imposto->ICMS;
            $tagIcms = $icms ? $icms->children()[0]->getName() : '?';
            echo 'Item ' . (string)$det['nItem'] . ': ' .
                'xProd=' . (string)$det->prod->xProd . ' | ' .
                'CFOP=' . (string)$det->prod->CFOP . ' | ' .
                'NCM=' . (string)$det->prod->NCM . ' | ' .
                'ICMS=' . $tagIcms . "\n";
        }
    }
} else {
    echo "ERROS XML: " . json_encode($doc['erros_xml']) . "\n";
}
