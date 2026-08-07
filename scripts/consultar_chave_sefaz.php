<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Nfe;
use App\Models\Empresa;
use App\Services\NFeService;

$id = isset($argv[1]) ? (int)$argv[1] : 59;
$nfe = Nfe::find($id);
if (!$nfe) { echo "NFe $id não encontrada\n"; exit(1); }

echo "=== CONSULTA SEFAZ - NFe $id ===\n";
echo "chave: {$nfe->chave}\n";

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

try {
    $res = $svc->consultar($nfe);
    echo "Resposta: " . json_encode($res) . "\n";
} catch (\Exception $e) {
    echo "EXCEÇÃO: " . $e->getMessage() . "\n";
}
