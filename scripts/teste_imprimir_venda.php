<?php
// Teste de diagnóstico: por que /nfe/imprimirVenda/{id} não imprime?
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$id = isset($argv[1]) ? (int)$argv[1] : 57;

// autenticar um usuário para simular sessão
$user = \App\Models\User::first();
\Auth::loginUsingId($user->id);

$nfe = \App\Models\Nfe::find($id);
if (!$nfe) {
    echo "NFe $id NAO EXISTE\n";
    exit(1);
}
echo "NFe $id: empresa={$nfe->empresa_id} tpNF={$nfe->tpNF} estado={$nfe->estado} " .
     "fornecedor={$nfe->fornecedor_id} cliente={$nfe->cliente_id}\n";

request()->merge(['empresa_id' => $nfe->empresa_id]);
request()->setUserResolver(function () use ($user) { return $user; });

// 1) validar objeto empresa (helper usado no método)
try {
    \App\Http\Controllers\NfeController::class; // só para garantir autoload
    echo "passo 1 (find) ok\n";
} catch (\Throwable $e) {
    echo "ERRO find: " . $e->getMessage() . "\n";
}

// 2) renderizar a view
$config = \App\Models\Empresa::where('id', $nfe->empresa_id)->first();
if (!$config) {
    echo "ERRO: Empresa {$nfe->empresa_id} nao encontrada\n";
    exit(1);
}
echo "config empresa: {$config->nome} | logo: " . var_export($config->logo, true) . "\n";

$item = $nfe;
try {
    $p = view('nfe.imprimir', compact('config', 'item'))->render();
    echo "VIEW RENDERIZADA: " . strlen($p) . " bytes\n";
} catch (\Throwable $e) {
    echo "ERRO NA VIEW: " . get_class($e) . ": " . $e->getMessage() . " (linha " . $e->getLine() . ")\n";
    echo "TRACE: " . $e->getTraceAsString() . "\n";
    exit(1);
}

// 3) testar o ob_get_clean() sem buffer ativo (suspeita principal)
echo "buffers ativos: " . ob_get_level() . "\n";
try {
    $pdf = ob_get_clean();
    echo "ob_get_clean() retornou: " . var_export($pdf, true) . " | buffers restantes: " . ob_get_level() . "\n";
} catch (\Throwable $e) {
    echo "ERRO ob_get_clean: " . get_class($e) . ": " . $e->getMessage() . "\n";
}

// 4) testar dompdf direto
try {
    $domPdf = new Dompdf\Dompdf(["enable_remote" => true]);
    $domPdf->loadHtml($p);
    $domPdf->setPaper("A4");
    $domPdf->render();
    $out = $domPdf->output();
    echo "DOMPDF OK: " . strlen($out) . " bytes de PDF gerados\n";
    if (strncmp($out, "%PDF", 4) === 0) {
        echo "PDF VALIDO (inicia com %PDF)\n";
    } else {
        echo "PDF INVALIDO (nao inicia com %PDF)\n";
    }
} catch (\Throwable $e) {
    echo "ERRO DOMPDF: " . get_class($e) . ": " . $e->getMessage() . " (linha " . $e->getLine() . ")\n";
}
