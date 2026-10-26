<?php
/**
 * ═══════════════════════════════════════════════════════════
 * ERP - Script de Deploy da Fila de Impressão em Nuvem
 * ═══════════════════════════════════════════════════════════
 * 
 * INSTRUÇÃO: Faça upload deste arquivo para a raiz do seu projeto
 * no Hostinger (pasta public_html ou a pasta raiz do ERP).
 * Acesse via browser: https://erp.code360.dev.br/deploy_print_queue.php?key=code360deploy
 * APÓS EXECUTAR, DELETE ESTE ARQUIVO do servidor por segurança!
 */

define('DEPLOY_KEY', 'code360deploy');

if (!isset($_GET['key']) || $_GET['key'] !== DEPLOY_KEY) {
    http_response_code(403);
    echo '<h1>Acesso negado.</h1>';
    exit;
}

echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8">';
echo '<title>Deploy - Fila de Impressão</title>';
echo '<style>body{font-family:monospace;background:#111;color:#0f0;padding:20px;} .ok{color:#0f0;} .err{color:#f00;} .warn{color:#ff0;} h2{color:#0ff;}</style></head><body>';
echo '<h2>🚀 ERP - Deploy da Fila de Impressão em Nuvem</h2>';
echo '<pre>';

$results = [];
$errors  = [];

// Carrega o Laravel
$appBase = dirname(__FILE__);
$vendorPath = $appBase . '/vendor/autoload.php';

if (!file_exists($vendorPath)) {
    echo '<span class="err">❌ vendor/autoload.php não encontrado! Verifique o caminho do script.</span>' . PHP_EOL;
    exit;
}

require $vendorPath;
$app = require_once $appBase . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$db = Illuminate\Support\Facades\DB::connection();
echo '<span class="ok">✅ Laravel carregado com sucesso.</span>' . PHP_EOL;

// ─────────────────────────────────────────────────────────
// 1. Cria tabela fila_impressoes
// ─────────────────────────────────────────────────────────
echo PHP_EOL . '<b>PASSO 1: Criando tabela fila_impressoes...</b>' . PHP_EOL;

$tabelaExiste = Illuminate\Support\Facades\Schema::hasTable('fila_impressoes');
if ($tabelaExiste) {
    echo '<span class="warn">⚠️  Tabela fila_impressoes JÁ EXISTE — nenhuma ação necessária.</span>' . PHP_EOL;
} else {
    try {
        $db->statement("
            CREATE TABLE IF NOT EXISTS `fila_impressoes` (
              `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `empresa_id` bigint(20) unsigned NOT NULL,
              `tipo` varchar(50) NOT NULL DEFAULT 'nfce',
              `documento_id` bigint(20) unsigned DEFAULT NULL,
              `printer_ip` varchar(45) NOT NULL,
              `printer_porta` int(11) NOT NULL DEFAULT 9100,
              `payload_base64` longtext NOT NULL,
              `status` enum('pendente','impresso','erro') NOT NULL DEFAULT 'pendente',
              `erro_mensagem` text DEFAULT NULL,
              `impresso_em` timestamp NULL DEFAULT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `fila_impressoes_empresa_id_index` (`empresa_id`),
              KEY `fila_impressoes_status_index` (`status`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
        echo '<span class="ok">✅ Tabela fila_impressoes criada com sucesso!</span>' . PHP_EOL;
    } catch (Exception $e) {
        echo '<span class="err">❌ ERRO ao criar fila_impressoes: ' . htmlspecialchars($e->getMessage()) . '</span>' . PHP_EOL;
        $errors[] = $e->getMessage();
    }
}

// ─────────────────────────────────────────────────────────
// 2. Adiciona coluna printer_tipo à config_gerals (se não existir)
// ─────────────────────────────────────────────────────────
echo PHP_EOL . '<b>PASSO 2: Verificando coluna printer_tipo em config_gerals...</b>' . PHP_EOL;

$colunaExiste = Illuminate\Support\Facades\Schema::hasColumn('config_gerals', 'printer_tipo');
if ($colunaExiste) {
    echo '<span class="warn">⚠️  Coluna printer_tipo JÁ EXISTE — nenhuma ação necessária.</span>' . PHP_EOL;
} else {
    try {
        $db->statement("
            ALTER TABLE `config_gerals` 
            ADD COLUMN `printer_tipo` ENUM('rede', 'usb') NOT NULL DEFAULT 'rede' 
            COMMENT 'Tipo de impressao termica: rede (IP) ou usb (driver local)'
            AFTER `printer_status`;
        ");
        echo '<span class="ok">✅ Coluna printer_tipo adicionada com sucesso!</span>' . PHP_EOL;
    } catch (Exception $e) {
        echo '<span class="err">❌ ERRO ao adicionar printer_tipo: ' . htmlspecialchars($e->getMessage()) . '</span>' . PHP_EOL;
        $errors[] = $e->getMessage();
    }
}

// ─────────────────────────────────────────────────────────
// 3. Registra as migrations na tabela migrations do Laravel
// ─────────────────────────────────────────────────────────
echo PHP_EOL . '<b>PASSO 3: Registrando migrations no Laravel...</b>' . PHP_EOL;

$migrations = [
    '2026_10_05_110000_add_printer_tipo_to_config_gerals_table',
    '2026_10_26_101333_create_fila_impressoes_table',
];

foreach ($migrations as $mig) {
    $existe = $db->table('migrations')->where('migration', $mig)->exists();
    if ($existe) {
        echo '<span class="warn">⚠️  Migration ' . $mig . ' já registrada.</span>' . PHP_EOL;
    } else {
        try {
            $db->table('migrations')->insert([
                'migration' => $mig,
                'batch'     => $db->table('migrations')->max('batch') + 1,
            ]);
            echo '<span class="ok">✅ Migration ' . $mig . ' registrada.</span>' . PHP_EOL;
        } catch (Exception $e) {
            echo '<span class="err">❌ ERRO ao registrar migration: ' . htmlspecialchars($e->getMessage()) . '</span>' . PHP_EOL;
        }
    }
}

// ─────────────────────────────────────────────────────────
// 4. Limpa o cache do Laravel
// ─────────────────────────────────────────────────────────
echo PHP_EOL . '<b>PASSO 4: Limpando cache do Laravel...</b>' . PHP_EOL;

try {
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    echo '<span class="ok">✅ Cache limpo com sucesso!</span>' . PHP_EOL;
} catch (Exception $e) {
    echo '<span class="warn">⚠️  Erro ao limpar cache (pode ser normal em produção): ' . htmlspecialchars($e->getMessage()) . '</span>' . PHP_EOL;
}

// ─────────────────────────────────────────────────────────
// 5. Testa a API de fila
// ─────────────────────────="────────────────────────────
echo PHP_EOL . '<b>PASSO 5: Testando endpoint da fila...</b>' . PHP_EOL;

try {
    $total = \App\Models\FilaImpressao::count();
    echo '<span class="ok">✅ API da fila OK — registros: ' . $total . '</span>' . PHP_EOL;
    
    // Cria um job de teste
    $job = \App\Models\FilaImpressao::create([
        'empresa_id'     => 48,
        'tipo'           => 'deploy_teste',
        'documento_id'   => 0,
        'printer_ip'     => '192.168.88.87',
        'printer_porta'  => 9100,
        'payload_base64' => base64_encode("\x1B\x40\x1B\x61\x01\n  TESTE DEPLOY NUVEM\n" . date('d/m/Y H:i:s') . "\n\n\n\x1D\x56\x41\x10"),
        'status'         => 'pendente',
    ]);
    echo '<span class="ok">✅ Job de teste criado (ID #' . $job->id . ') — o Agente Local deve imprimir em até 2 segundos!</span>' . PHP_EOL;
} catch (Exception $e) {
    echo '<span class="err">❌ ERRO ao testar fila: ' . htmlspecialchars($e->getMessage()) . '</span>' . PHP_EOL;
    $errors[] = $e->getMessage();
}

echo PHP_EOL;
if (count($errors) === 0) {
    echo '<span class="ok" style="font-size:18px;font-weight:bold;">🎉 DEPLOY CONCLUÍDO COM SUCESSO!</span>' . PHP_EOL;
    echo '<span class="ok">Acesse https://erp.code360.dev.br/api/print-queue/pendentes?empresa_id=48 para confirmar.</span>' . PHP_EOL;
} else {
    echo '<span class="err" style="font-size:18px;">⚠️ Deploy concluído com ERROS. Verifique os itens acima.</span>' . PHP_EOL;
}

echo PHP_EOL;
echo '<span class="warn">⚠️  IMPORTANTE: DELETE este arquivo do servidor após confirmar que funcionou!</span>' . PHP_EOL;
echo '<span class="warn">   Arquivo: deploy_print_queue.php</span>' . PHP_EOL;
echo '</pre></body></html>';
