<?php
// Teste E2E: /nfe/xml-temp/{id} — deve redirecionar com mensagem amigável quando falta natureza (nunca 500)
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$id = isset($argv[1]) ? (int)$argv[1] : 57;

$request = Illuminate\Http\Request::create('/nfe/xml-temp/' . $id, 'GET');
$app->instance('request', $request);
$kernel->bootstrap();

$user = \App\Models\User::first();
$session = $app['session']->driver();
$session->start();
\Auth::shouldUse('web');
\Auth::loginUsingId($user->id);

$response = $kernel->handle($request);

echo 'STATUS: ' . $response->getStatusCode() . "\n";
if ($response->getStatusCode() == 302) {
    echo 'Redirect para: ' . $response->headers->get('location') . "\n";
    echo 'Mensagem flash: ' . (session('flash_error') ?? '(nenhuma)') . "\n";
} else {
    echo 'Content-Type: ' . $response->headers->get('content-type') . "\n";
    $content = $response->getContent();
    echo 'Tamanho: ' . strlen($content) . " bytes\n";
    echo 'Inicia com %PDF ou XML: ' . (strncmp($content, '%PDF', 4) === 0 || strncmp(trim($content), '<', 1) === 0 ? 'SIM' : 'NAO') . "\n";
    if (strpos($content, 'Whoops') !== false || strpos($content, 'Exception') !== false || strpos($content, 'SQLSTATE') !== false) {
        echo "!!! ERRO 500 DETECTADO NA PAGINA\n";
    }
}
