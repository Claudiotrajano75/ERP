<?php
// Testa a rota de emissão com ID inválido (não transmite) e a conectividade SEFAZ (status)
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$app->instance('request', Illuminate\Http\Request::create('/', 'GET'));
$kernel->bootstrap();

$user = \App\Models\User::first();

// abre caixa temporário (consulta status precisa de caixa)
$caixa = \App\Models\Caixa::create([
    'empresa_id' => 2,
    'usuario_id' => $user->id,
    'valor_abertura' => 0,
    'status' => 1,
    'local_id' => 2,
    'observacao' => 'caixa temporario de teste sefaz',
]);

function rodaPost($app, $kernel, $user, $url, $dados)
{
    $request = Illuminate\Http\Request::create($url, 'POST', $dados);
    $app->instance('request', $request);
    $kernel->bootstrap();

    $session = $app['session']->driver();
    $session->start();
    \Auth::shouldUse('web');
    \Auth::loginUsingId($user->id);

    return $kernel->handle($request);
}

// ── 1) Rota de emissão com ID INEXISTENTE (não chega a transmitir nada) ──
echo "=== emitir com id inexistente (999999) ===\n";
$resp = rodaPost($app, $kernel, $user, '/api/nfe_painel/emitir', ['id' => 999999]);
echo 'status: ' . $resp->getStatusCode() . " (esperado 404 ou 401)\n";
$c = $resp->getContent();
echo 'resposta: ' . substr(strip_tags($c), 0, 120) . "\n";

// ── 2) Conectividade SEFAZ (consulta de status — seguro, sem evento fiscal) ──
echo "\n=== consulta status SEFAZ (conectividade) ===\n";
try {
    $resp2 = rodaPost($app, $kernel, $user, '/api/nfe_painel/consulta-status-sefaz', [
        'empresa_id' => 2,
        'usuario_id' => $user->id,
    ]);
    echo 'status: ' . $resp2->getStatusCode() . "\n";
    $c2 = $resp2->getContent();
    echo 'resposta SEFAZ: ' . substr(strip_tags($c2), 0, 300) . "\n";
} catch (\Throwable $e) {
    echo 'ERRO na consulta: ' . get_class($e) . ': ' . substr($e->getMessage(), 0, 300) . "\n";
}

// limpa caixa temporário
$caixa->delete();
echo "\nCaixa temporário removido.\n";
