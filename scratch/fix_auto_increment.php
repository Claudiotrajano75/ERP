<?php
define('LARAVEL_START', microtime(true));
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Verifica e corrige AUTO_INCREMENT em todas as tabelas principais
$tables = [
    'nfces', 'acao_logs', 'item_nfces', 'fatura_nfces',
    'nfes', 'item_nfes', 'ctes', 'mdfes', 'pre_vendas',
];

foreach ($tables as $table) {
    try {
        $col = DB::select(
            "SELECT EXTRA FROM information_schema.COLUMNS 
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = 'id'",
            [$table]
        );

        if (empty($col)) {
            echo "⚠️  $table: tabela não encontrada ou sem coluna 'id'" . PHP_EOL;
            continue;
        }

        $extra = $col[0]->EXTRA ?? '';

        if (strpos($extra, 'auto_increment') === false) {
            DB::statement("ALTER TABLE `$table` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT");
            echo "✅  $table: AUTO_INCREMENT adicionado!" . PHP_EOL;
        } else {
            echo "ℹ️  $table: já tem AUTO_INCREMENT" . PHP_EOL;
        }
    } catch (\Exception $e) {
        echo "❌  $table: ERRO - " . $e->getMessage() . PHP_EOL;
    }
}

echo PHP_EOL . "Concluído!" . PHP_EOL;
