<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\API\NFePainelController;
use App\Models\Nfe;

$id = isset($argv[1]) ? (int)$argv[1] : 63;
$motivo = isset($argv[2]) ? $argv[2] : 'Teste de emissao realizado, cancelamento apos verificacao';

$nfe = Nfe::find($id);
if (!$nfe) { echo "NFe $id não encontrada\n"; exit(1); }

if (strlen($motivo) < 15) {
    echo "Justificativa muito curta (mínimo 15 caracteres)\n";
    exit(1);
}

echo "=== CANCELANDO NFe $id (número {$nfe->numero}, série {$nfe->numero_serie}) NA SEFAZ (PRODUÇÃO) ===\n";
echo "chave: {$nfe->chave}\n";
echo "estado atual: {$nfe->estado}\n";
echo "justificativa: $motivo\n";

$controller = app(NFePainelController::class);
$request = Request::create('/api/nfe_painel/cancelar', 'POST', ['id' => $id, 'motivo' => $motivo]);
app()->instance('request', $request);

try {
    $response = $controller->cancelar($request);
    $status = $response->getStatusCode();
    $content = $response->getContent();
    echo "HTTP Status: $status\n";
    echo "Resposta: $content\n";

    $nfe->refresh();
    echo "\n=== ESTADO FINAL DA NFe $id ===\n";
    echo "estado: {$nfe->estado}\n";
    echo "numero: " . var_export($nfe->numero, true) . "\n";
    echo "chave: " . var_export($nfe->chave, true) . "\n";
    echo "motivo_rejeicao: " . var_export($nfe->motivo_rejeicao, true) . "\n";
} catch (\Exception $e) {
    echo "EXCEÇÃO: " . $e->getMessage() . "\n";
    exit(1);
}
