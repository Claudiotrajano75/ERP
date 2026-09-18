<?php
define('LARAVEL_START', microtime(true));
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$tables = ['nfces', 'pre_vendas'];

foreach ($tables as $table) {
    echo "=== Corrigindo: $table ===" . PHP_EOL;

    try {
        // 1. Verifica se já tem PRIMARY KEY
        $pk = DB::select(
            "SELECT COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND CONSTRAINT_NAME = 'PRIMARY'",
            [$table]
        );

        if (empty($pk)) {
            // Sem PRIMARY KEY — precisa adicionar
            echo "  ⚠️  Sem PRIMARY KEY. Adicionando PK + AUTO_INCREMENT..." . PHP_EOL;
            DB::statement("ALTER TABLE `$table` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, ADD PRIMARY KEY (`id`)");
            echo "  ✅  PRIMARY KEY e AUTO_INCREMENT adicionados!" . PHP_EOL;
        } else {
            // Tem PK, só falta AUTO_INCREMENT
            echo "  ℹ️  PRIMARY KEY encontrada. Adicionando AUTO_INCREMENT..." . PHP_EOL;
            DB::statement("ALTER TABLE `$table` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT");
            echo "  ✅  AUTO_INCREMENT adicionado!" . PHP_EOL;
        }

        // Verifica o MAX id atual e ajusta o próximo AUTO_INCREMENT
        $maxId = DB::table($table)->max('id') ?? 0;
        $nextId = $maxId + 1;
        DB::statement("ALTER TABLE `$table` AUTO_INCREMENT = $nextId");
        echo "  ✅  AUTO_INCREMENT iniciará em: $nextId" . PHP_EOL;

    } catch (\Exception $e) {
        echo "  ❌  ERRO: " . $e->getMessage() . PHP_EOL;
    }

    echo PHP_EOL;
}

echo "Concluído!" . PHP_EOL;
