<?php
// Teste E2E: dispara a rota real /nfe/imprimirVenda/{id} pelo kernel HTTP com usuário autenticado
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$id = isset($argv[1]) ? (int)$argv[1] : 57;

// vincula o request ANTES do bootstrap (UrlGenerator precisa dele)
$request = Illuminate\Http\Request::create('/nfe/imprimirVenda/' . $id, 'GET');
$app->instance('request', $request);
$kernel->bootstrap();

// autentica um usuário na sessão
$user = \App\Models\User::first();
$session = $app['session']->driver();
$session->start();
\Auth::shouldUse('web');
\Auth::loginUsingId($user->id);

$response = $kernel->handle($request);

echo 'STATUS: ' . $response->getStatusCode() . "\n";
echo 'Content-Type: ' . $response->headers->get('content-type') . "\n";
echo 'Content-Disposition: ' . $response->headers->get('content-disposition') . "\n";
$content = $response->getContent();
echo 'Tamanho: ' . strlen($content) . " bytes\n";
echo 'Inicia com %PDF: ' . (strncmp($content, '%PDF', 4) === 0 ? 'SIM' : 'NAO') . "\n";
if ($response->getStatusCode() == 302) {
    echo 'Redirect para: ' . $response->headers->get('location') . "\n";
} elseif (strncmp($content, '%PDF', 4) !== 0) {
    echo 'Conteúdo (primeiros 500 chars): ' . substr(strip_tags($content), 0, 500) . "\n";
}
