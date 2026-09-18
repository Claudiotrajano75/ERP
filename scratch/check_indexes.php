<?php
define('LARAVEL_START', microtime(true));
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Verifica índices das tabelas problemáticas
foreach (['nfces', 'pre_vendas'] as $table) {
    echo "=== $table ===" . PHP_EOL;
    $indexes = DB::select("SHOW INDEX FROM `$table`");
    foreach ($indexes as $idx) {
        echo "  Key: {$idx->Key_name} | Column: {$idx->Column_name} | Unique: {$idx->Non_unique}" . PHP_EOL;
    }
    $cols = DB::select("SELECT COLUMN_NAME, COLUMN_KEY, EXTRA FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?", [$table]);
    foreach ($cols as $col) {
        echo "  Col: {$col->COLUMN_NAME} | Key: {$col->COLUMN_KEY} | Extra: {$col->EXTRA}" . PHP_EOL;
    }
    echo PHP_EOL;
}
