<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class DatabaseBackup extends Command
{
    protected $signature = 'db:backup {--file= : Caminho customizado para salvar o arquivo de backup}';
    protected $description = 'Gera backup completo do banco de dados MySQL com integridade de chaves e UTF-8';

    public function handle()
    {
        $this->info("Iniciando processo de backup do banco de dados...");

        $host = config('database.connections.mysql.host', '127.0.0.1');
        $user = config('database.connections.mysql.username', 'root');
        $pass = config('database.connections.mysql.password', '');
        $database = config('database.connections.mysql.database', 'u544033777_erp');

        $backupDir = base_path('backups');
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $file = $this->option('file');
        if (!$file) {
            $timestamp = date('Y-m-d_H-i-s');
            $file = $backupDir . DIRECTORY_SEPARATOR . "backup_{$database}_{$timestamp}.sql";
        }

        $mysqldump = 'C:\xampp\mysql\bin\mysqldump.exe';
        if (!file_exists($mysqldump)) {
            $mysqldump = 'mysqldump';
        }

        $passParam = !empty($pass) ? "--password={$pass}" : '';
        $cmd = "\"{$mysqldump}\" --host={$host} --user={$user} {$passParam} --default-character-set=utf8mb4 --single-transaction --quick --routines --triggers --max_allowed_packet=512M {$database} > \"{$file}\"";

        $this->line("Comando: {$cmd}");
        $this->info("Exportando dados para: {$file}");

        $start = microtime(true);
        $result = 0;
        system("cmd /c \"{$cmd}\"", $result);

        if ($result === 0 && file_exists($file)) {
            $size = round(filesize($file) / (1024 * 1024), 2);
            $duration = round(microtime(true) - $start, 2);
            $this->info("Backup concluído com sucesso em {$duration} segundos!");
            $this->info("Arquivo: {$file} ({$size} MB)");
            return 0;
        }

        $this->error("Falha ao gerar o backup. Código de retorno: {$result}");
        return 1;
    }
}
