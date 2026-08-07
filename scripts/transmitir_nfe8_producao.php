<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\API\NFePainelController;
use App\Models\Nfe;

$id = isset($argv[1]) ? (int)$argv[1] : 8;

$nfe = Nfe::find($id);
if (!$nfe) { echo "NFe $id não encontrada\n"; exit(1); }
if ($nfe->estado != 'novo') {
    echo "ABORTADO: NFe $id está em estado '{$nfe->estado}' (não é 'novo').\n";
    exit(1);
}

echo "=== TRANSMITINDO NFe $id PARA A SEFAZ (PRODUÇÃO) ===\n";
echo "Chave (doc gerado): (gerada no fluxo)\n";

$controller = app(NFePainelController::class);
$request = Request::create('/api/nfe_painel/emitir', 'POST', ['id' => $id]);
// Substitui a instância global para o controlador ler o mesmo request
app()->instance('request', $request);
echo 'id no request: ' . var_export($request->id, true) . PHP_EOL;
echo 'estado NFe antes: ' . $nfe->estado . PHP_EOL;

try {
    $response = $controller->emitir($request);
    $status = $response->getStatusCode();
    $content = $response->getContent();

    echo "HTTP Status: $status\n";
    echo "Resposta: $content\n";

    $nfe->refresh();
    echo "\n=== ESTADO FINAL DA NFe $id ===\n";
    echo "estado: {$nfe->estado}\n";
    echo "numero: " . var_export($nfe->numero, true) . "\n";
    echo "chave: " . var_export($nfe->chave, true) . "\n";
    echo "recibo: " . var_export($nfe->recibo, true) . "\n";
    echo "motivo_rejeicao: " . var_export($nfe->motivo_rejeicao, true) . "\n";
    echo "data_emissao: " . var_export($nfe->data_emissao, true) . "\n";
} catch (\Exception $e) {
    echo "EXCEÇÃO: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
