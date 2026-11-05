<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Nfe;
use App\Models\Empresa;
use App\Services\NFeService;

$id = isset($argv[1]) ? (int)$argv[1] : 8;
$nfe = Nfe::find($id);
if (!$nfe) { echo "NFe $id não encontrada\n"; exit(1); }

$empresa = Empresa::findOrFail($nfe->empresa_id);
$empresa = __objetoParaEmissao($empresa, $nfe->local_id);

$svc = new NFeService([
    "atualizacao" => date('Y-m-d h:i:s'),
    "tpAmb" => (int)$empresa->ambiente,
    "razaosocial" => $empresa->nome,
    "siglaUF" => $empresa->cidade->uf,
    "cnpj" => preg_replace('/[^0-9]/', '', $empresa->cpf_cnpj),
    "schemes" => "PL_010",
    "versao" => "4.00",
], $empresa);

echo "=== NFe $id - dados para o XML ===\n";
echo "numero: " . var_export($nfe->numero, true) . "\n";
echo "numero_sequencial: " . var_export($nfe->numero_sequencial, true) . "\n";
echo "numero_serie: " . var_export($nfe->numero_serie, true) . "\n";
echo "ambiente empresa: " . $empresa->ambiente . "\n";
echo "ultima nfe producao: " . var_export($empresa->numero_ultima_nfe_producao, true) . "\n";

$doc = $svc->gerarXml($nfe);
if (isset($doc['erros_xml'])) {
    echo "ERROS_XML: " . json_encode($doc['erros_xml']) . "\n";
    exit(1);
}

$xml = simplexml_load_string($doc['xml']);
$xml->registerXPathNamespace('nfe', 'http://www.portalfiscal.inf.br/nfe');
$inf = $xml->xpath('//nfe:infNFe')[0] ?? $xml->xpath('//infNFe')[0] ?? null;
if ($inf) {
    echo "--- Campos que irão na NFe ---\n";
    echo "nNF: [" . (string)$inf->ide->nNF . "]\n";
    echo "serie: [" . (string)$inf->ide->serie . "]\n";
    echo "natOp: [" . (string)$inf->ide->natOp . "]\n";
    echo "tpNF: [" . (string)$inf->ide->tpNF . "]\n";
    echo "tpAmb: [" . (string)$inf->ide->tpAmb . "]\n";
    echo "dhEmi: [" . (string)$inf->ide->dhEmi . "]\n";
    echo "dest xNome: [" . (string)$inf->dest->xNome . "]\n";
    echo "dest CNPJ/CPF: [" . (string)$inf->dest->CNPJ . (string)$inf->dest->CPF . "]\n";
    echo "chave doc: " . $doc['chave'] . "\n";
    echo "numero doc: " . var_export($doc['numero'] ?? null, true) . "\n";
}
echo "xml bytes: " . strlen($doc['xml']) . "\n";
