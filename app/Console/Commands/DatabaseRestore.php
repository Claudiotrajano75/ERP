<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DatabaseRestore extends Command
{
    protected $signature = 'db:restore {file? : Caminho do arquivo .sql a ser restaurado}';
    protected $description = 'Restaura o banco de dados a partir de um arquivo SQL e valida AUTO_INCREMENT';

    public function handle()
    {
        $file = $this->argument('file');

        if (!$file) {
            $defaultFile = 'C:\u544033777_erp.sql';
            if (file_exists($defaultFile)) {
                $file = $defaultFile;
            } else {
                $backups = glob(base_path('backups/*.sql'));
                if (!empty($backups)) {
                    usort($backups, function ($a, $b) {
                        return filemtime($b) - filemtime($a);
                    });
                    $file = $backups[0];
                }
            }
        }

        if (!$file || !file_exists($file)) {
            $file = $this->ask("Informe o caminho completo do arquivo .sql a ser restaurado");
        }

        $file = trim($file, "\"' ");

        if (!file_exists($file)) {
            $this->error("Arquivo não encontrado: {$file}");
            return 1;
        }

        $host = config('database.connections.mysql.host', '127.0.0.1');
        $user = config('database.connections.mysql.username', 'root');
        $pass = config('database.connections.mysql.password', '');
        $database = config('database.connections.mysql.database', 'u544033777_erp');

        if (!$this->confirm("Deseja restaurar o banco '{$database}' com o arquivo '{$file}'? Todos os dados atuais serão substituídos.", true)) {
            $this->info("Operação cancelada.");
            return 0;
        }

        $mysql = 'C:\xampp\mysql\bin\mysql.exe';
        if (!file_exists($mysql)) {
            $mysql = 'mysql';
        }

        $passParam = !empty($pass) ? "--password={$pass}" : '';

        // Cria o database se não existir
        system("cmd /c \"\"{$mysql}\" --host={$host} --user={$user} {$passParam} -e \"CREATE DATABASE IF NOT EXISTS {$database} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\"\"");

        $this->info("Importando banco de dados a partir de: {$file}...");
        $start = microtime(true);
        $result = 0;
        $cmd = "\"{$mysql}\" --host={$host} --user={$user} {$passParam} --default-character-set=utf8mb4 --max_allowed_packet=512M {$database} < \"{$file}\"";
        
        system("cmd /c \"{$cmd}\"", $result);

        if ($result !== 0) {
            $this->error("Falha ao restaurar banco de dados. Código de retorno: {$result}");
            return 1;
        }

        $duration = round(microtime(true) - $start, 2);
        $this->info("Dados importados com sucesso em {$duration} segundos!");

        // Executar correção de AUTO_INCREMENT
        $this->info("Verificando integridade das chaves primárias e AUTO_INCREMENT...");
        $corrigirScript = base_path('scripts/corrigir_autoincrement.php');
        if (file_exists($corrigirScript)) {
            system("php \"{$corrigirScript}\"");
        }

        $this->info("Restauração concluída com total sucesso!");
        return 0;
    }
}
