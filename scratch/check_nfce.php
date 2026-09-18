<?php
define('LARAVEL_START', microtime(true));
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$nfce = App\Models\Nfce::where('empresa_id', 48)->orderBy('id','desc')->first();
if ($nfce) {
    echo "ID: " . $nfce->id . PHP_EOL;
    echo "Estado: " . $nfce->estado . PHP_EOL;
    echo "Motivo Rejeicao: " . $nfce->motivo_rejeicao . PHP_EOL;
    echo "Numero: " . $nfce->numero . PHP_EOL;
    echo "Chave: " . $nfce->chave . PHP_EOL;
    echo "Ambiente: " . $nfce->ambiente . PHP_EOL;
} else {
    echo "Nenhuma NFC-e encontrada para empresa_id=48" . PHP_EOL;
}
