<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BackupEmpresaController extends Controller
{
    /**
     * Gera o dump SQL somente dos dados da empresa atual (filtrados por empresa_id).
     * Suporta: download direto ou salvar no servidor (Hostinger).
     */
    public function gerar(Request $request)
    {
        ini_set('max_execution_time', '300');
        set_time_limit(300);
        ini_set('memory_limit', '512M');

        $empresaId = $this->getEmpresaId();

        if (!$empresaId) {
            session()->flash('flash_error', 'Empresa não identificada.');
            return redirect()->back();
        }

        $acao = $request->input('acao', 'download'); // 'download' ou 'servidor'

        // Busca dados da própria empresa
        $empresa = DB::table('empresas')->where('id', $empresaId)->first();
        $nomeEmpresa = $empresa ? ($empresa->nome_fantasia ?: $empresa->nome) : "Empresa {$empresaId}";

        // Arquivo temporário para economia de memória
        $tempFile = tempnam(sys_get_temp_dir(), 'backup_empresa_');
        $handle = fopen($tempFile, 'w+');

        if (!$handle) {
            session()->flash('flash_error', 'Falha ao inicializar arquivo temporário para backup.');
            return redirect()->back();
        }

        // Cabeçalho do arquivo SQL
        fwrite($handle, "-- ============================================================\n");
        fwrite($handle, "-- BACKUP DE DADOS DA EMPRESA\n");
        fwrite($handle, "-- Empresa ID: {$empresaId} - " . addslashes($nomeEmpresa) . "\n");
        fwrite($handle, "-- Gerado em: " . now()->format('d/m/Y H:i:s') . "\n");
        fwrite($handle, "-- Sistema: " . config('app.name') . "\n");
        fwrite($handle, "-- ============================================================\n\n");
        fwrite($handle, "SET NAMES utf8mb4;\n");
        fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n\n");

        // 1. Registro da própria empresa na tabela `empresas`
        if ($empresa) {
            fwrite($handle, "-- ── Tabela: empresas (Registro Principal da Empresa) ──────────\n");
            $dadosEmp = (array) $empresa;
            $colsEmp = implode('`, `', array_keys($dadosEmp));
            $valsEmp = implode(', ', array_map(function ($val) {
                if ($val === null) return 'NULL';
                return "'" . addslashes($val) . "'";
            }, array_values($dadosEmp)));

            fwrite($handle, "INSERT INTO `empresas` (`{$colsEmp}`) VALUES ({$valsEmp}) ON DUPLICATE KEY UPDATE `id` = `id`;\n\n");
        }

        // 2. Tabelas do sistema que possuem a coluna `empresa_id`
        $tabelas = $this->getTabelasComEmpresaId();

        foreach ($tabelas as $tabela) {
            if ($tabela === 'empresas') {
                continue; // já tratada acima
            }

            try {
                // Checa se a tabela tem registros desta empresa
                $count = DB::table($tabela)->where('empresa_id', $empresaId)->count();
                if ($count === 0) {
                    continue;
                }

                fwrite($handle, "-- ── Tabela: `{$tabela}` ({$count} registros) ──────────────────\n");

                // Utiliza cursor() para manter consumo de memória estável mesmo em milhares de linhas
                foreach (DB::table($tabela)->where('empresa_id', $empresaId)->cursor() as $registro) {
                    $dados = (array) $registro;
                    $colunasSql = implode('`, `', array_keys($dados));
                    $valoresSql = implode(', ', array_map(function ($val) {
                        if ($val === null) return 'NULL';
                        return "'" . addslashes($val) . "'";
                    }, array_values($dados)));

                    fwrite($handle, "INSERT INTO `{$tabela}` (`{$colunasSql}`) VALUES ({$valoresSql});\n");
                }

                fwrite($handle, "\n");
            } catch (\Exception $e) {
                // Se alguma tabela der erro de leitura, prossegue com as demais
                continue;
            }
        }

        fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
        fclose($handle);

        $nomeArquivo = 'backup_empresa_' . $empresaId . '_' . now()->format('Ymd_His') . '.sql';

        // ── OPÇÃO: Salvar no servidor (Hostinger / storage) ───────────────────
        if ($acao === 'servidor') {
            $pastaBackup = storage_path('app/backups/empresa_' . $empresaId);

            if (!file_exists($pastaBackup)) {
                mkdir($pastaBackup, 0755, true);
            }

            $destino = $pastaBackup . '/' . $nomeArquivo;
            copy($tempFile, $destino);
            @unlink($tempFile);

            session()->flash('flash_success', "Backup gerado e salvo com sucesso no servidor: backups/empresa_{$empresaId}/{$nomeArquivo}");
            return redirect()->back();
        }

        // ── OPÇÃO: Download direto ────────────────────────────────────────────
        return response()->download($tempFile, $nomeArquivo, [
            'Content-Type' => 'application/sql'
        ])->deleteFileAfterSend(true);
    }

    /**
     * Lista os backups salvos no servidor para esta empresa.
     */
    public function listar()
    {
        $empresaId = $this->getEmpresaId();
        $pastaBackup = storage_path('app/backups/empresa_' . $empresaId);
        $arquivos = [];

        if (file_exists($pastaBackup)) {
            $lista = glob($pastaBackup . '/*.sql');
            if ($lista) {
                rsort($lista); // mais recente primeiro

                foreach ($lista as $arquivo) {
                    $arquivos[] = [
                        'nome'    => basename($arquivo),
                        'tamanho' => $this->formatarTamanho(filesize($arquivo)),
                        'data'    => date('d/m/Y H:i:s', filemtime($arquivo)),
                    ];
                }
            }
        }

        return response()->json(['arquivos' => $arquivos]);
    }

    /**
     * Faz download de um backup que foi previamente salvo no servidor.
     */
    public function downloadServidor(Request $request)
    {
        $empresaId = $this->getEmpresaId();
        $nome      = basename($request->input('nome')); // sanitiza para evitar path traversal
        $caminho   = storage_path('app/backups/empresa_' . $empresaId . '/' . $nome);

        if (!file_exists($caminho)) {
            session()->flash('flash_error', 'Arquivo de backup não encontrado.');
            return redirect()->back();
        }

        return response()->download($caminho);
    }

    /**
     * Remove um arquivo de backup do servidor.
     */
    public function removerServidor(Request $request)
    {
        $empresaId = $this->getEmpresaId();
        $nome      = basename($request->input('nome')); // sanitiza
        $caminho   = storage_path('app/backups/empresa_' . $empresaId . '/' . $nome);

        if (file_exists($caminho)) {
            @unlink($caminho);
            return response()->json(['success' => true, 'message' => 'Backup removido com sucesso.']);
        }

        return response()->json(['success' => false, 'message' => 'Arquivo não encontrado.']);
    }

    /**
     * Restaura os dados da empresa a partir do upload de um arquivo .sql.
     */
    public function restaurarUpload(Request $request)
    {
        ini_set('max_execution_time', '300');
        set_time_limit(300);
        ini_set('memory_limit', '512M');

        $empresaId = $this->getEmpresaId($request);
        if (!$empresaId) {
            return response()->json(['success' => false, 'message' => 'Empresa não identificada.'], 400);
        }

        if (!$request->hasFile('arquivo_backup') || !$request->file('arquivo_backup')->isValid()) {
            return response()->json(['success' => false, 'message' => 'Arquivo de backup inválido ou não enviado.'], 422);
        }

        $file = $request->file('arquivo_backup');
        $ext = strtolower($file->getClientOriginalExtension());
        if ($ext !== 'sql') {
            return response()->json(['success' => false, 'message' => 'O arquivo enviado deve possuir a extensão .sql.'], 422);
        }

        $limparAntes = $request->boolean('limpar_antes', true);

        try {
            $resultado = $this->executarRestauracao($file->getRealPath(), $empresaId, $limparAntes);

            $msg = "Backup restaurado com sucesso! {$resultado['total_statements']} comandos processados em {$resultado['total_tabelas']} tabelas para a sua empresa.";

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $msg]);
            }

            session()->flash('flash_success', $msg);
            return redirect()->back();
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
            session()->flash('flash_error', 'Falha na restauração: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Restaura os dados da empresa a partir de um backup já existente no servidor.
     */
    public function restaurarServidor(Request $request)
    {
        ini_set('max_execution_time', '300');
        set_time_limit(300);
        ini_set('memory_limit', '512M');

        $empresaId = $this->getEmpresaId($request);
        if (!$empresaId) {
            return response()->json(['success' => false, 'message' => 'Empresa não identificada.'], 400);
        }

        $nome = basename($request->input('nome', ''));
        if (empty($nome)) {
            return response()->json(['success' => false, 'message' => 'Nome do arquivo não informado.'], 422);
        }

        $caminho = storage_path('app/backups/empresa_' . $empresaId . '/' . $nome);
        if (!file_exists($caminho)) {
            return response()->json(['success' => false, 'message' => 'Arquivo de backup não encontrado no servidor.'], 404);
        }

        $limparAntes = $request->boolean('limpar_antes', true);

        try {
            $resultado = $this->executarRestauracao($caminho, $empresaId, $limparAntes);

            $msg = "Backup restaurado com sucesso a partir do servidor! {$resultado['total_statements']} comandos processados em {$resultado['total_tabelas']} tabelas para a sua empresa.";

            return response()->json(['success' => true, 'message' => $msg]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    /**
     * Executa com segurança o processo de restauração SQL validando isolamento estrito por empresa_id.
     */
    private function executarRestauracao(string $caminhoArquivo, int $empresaId, bool $limparAntes = true): array
    {
        if (!file_exists($caminhoArquivo) || filesize($caminhoArquivo) === 0) {
            throw new \Exception('O arquivo de backup está vazio ou inacessível.');
        }

        // 1. Validação do cabeçalho do arquivo
        $handle = fopen($caminhoArquivo, 'r');
        if (!$handle) {
            throw new \Exception('Não foi possível ler o arquivo de backup.');
        }

        $headerChunk = fread($handle, 4096);
        fclose($handle);

        $idDetectado = null;
        if (preg_match('/-- Empresa ID:\s*([0-9]+)/i', $headerChunk, $matches)) {
            $idDetectado = (int) $matches[1];
        }

        if ($idDetectado !== null && $idDetectado !== (int)$empresaId) {
            throw new \Exception("Incompatibilidade de segurança: Este arquivo pertence à Empresa ID {$idDetectado}, enquanto a sua sessão atual é da Empresa ID {$empresaId}. A restauração entre empresas diferentes foi bloqueada para proteger seus dados.");
        }

        // 2. Coleta das tabelas do banco que possuem a coluna `empresa_id`
        $tabelasPermitidas = $this->getTabelasComEmpresaId();
        $tabelasPermitidasMap = array_flip($tabelasPermitidas);

        // 3. Comandos perigosos que jamais devem ser executados em um restore multitenant
        $proibidos = [
            'DROP ', 'TRUNCATE ', 'ALTER ', 'CREATE DATABASE', 'CREATE TABLE',
            'GRANT ', 'REVOKE ', 'FLUSH ', 'SHUTDOWN'
        ];

        // 4. Tokenização e validação dos statements SQL em streaming
        $handle = fopen($caminhoArquivo, 'r');
        $current = '';
        $inString = false;
        $stringChar = '';
        $escaped = false;
        $statements = [];
        $tabelasNoBackup = [];

        while (!feof($handle)) {
            $chunk = fread($handle, 65536);
            $len = strlen($chunk);

            for ($i = 0; $i < $len; $i++) {
                $char = $chunk[$i];

                if ($inString) {
                    $current .= $char;
                    if ($escaped) {
                        $escaped = false;
                    } elseif ($char === '\\') {
                        $escaped = true;
                    } elseif ($char === $stringChar) {
                        $inString = false;
                    }
                } else {
                    if ($char === "'" || $char === '"') {
                        $inString = true;
                        $stringChar = $char;
                        $current .= $char;
                    } elseif ($char === ';') {
                        $stmt = $this->trimLeadingSqlComments($current);
                        if (!empty($stmt)) {
                            // Bloqueio de comandos perigosos
                            foreach ($proibidos as $p) {
                                if (stripos($stmt, $p) !== false) {
                                    fclose($handle);
                                    throw new \Exception("Comando não autorizado detectado no arquivo: '{$p}'. Restauração cancelada por segurança.");
                                }
                            }

                            // Validação da tabela e isolamento
                            if (preg_match('/^INSERT\s+INTO\s+[`]?([a-zA-Z0-9_]+)[`]?/i', $stmt, $mTab)) {
                                $tabelaNome = $mTab[1];
                                if ($tabelaNome !== 'empresas' && !isset($tabelasPermitidasMap[$tabelaNome])) {
                                    fclose($handle);
                                    throw new \Exception("A tabela '{$tabelaNome}' não pertence ao escopo desta empresa. Restauração cancelada.");
                                }
                                $tabelasNoBackup[$tabelaNome] = true;
                            }

                            $statements[] = $stmt;
                        }
                        $current = '';
                    } else {
                        $current .= $char;
                    }
                }
            }
        }
        fclose($handle);

        $stmtFinal = $this->trimLeadingSqlComments($current);
        if (!empty($stmtFinal)) {
            $statements[] = $stmtFinal;
        }

        if (empty($statements)) {
            throw new \Exception('Nenhum comando SQL válido foi encontrado no arquivo de backup.');
        }

        // 5. Execução atômica e segura
        DB::beginTransaction();
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Se solicitado, remove os registros prévios das tabelas pertencentes a esta empresa
            if ($limparAntes) {
                foreach (array_keys($tabelasNoBackup) as $tabela) {
                    if ($tabela === 'empresas') {
                        continue; // a tabela empresas é atualizada via ON DUPLICATE KEY UPDATE
                    }
                    DB::table($tabela)->where('empresa_id', $empresaId)->delete();
                }
            }

            $executados = 0;
            foreach ($statements as $stmt) {
                // Pula comandos de controle redundantes
                if (stripos($stmt, 'SET NAMES') === 0 || stripos($stmt, 'SET FOREIGN_KEY_CHECKS') === 0) {
                    continue;
                }
                DB::statement($stmt);
                $executados++;
            }

            DB::commit();

            return [
                'total_statements' => $executados,
                'total_tabelas'    => count($tabelasNoBackup)
            ];
        } catch (\Exception $ex) {
            DB::rollBack();
            throw new \Exception('Erro ao processar as instruções no banco de dados: ' . $ex->getMessage());
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    /**
     * Remove comentários iniciais mantendo as queries SQL limpas.
     */
    private function trimLeadingSqlComments(string $sql): string
    {
        $lines = explode("\n", $sql);
        $cleaned = [];
        $foundSql = false;
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if (!$foundSql && (str_starts_with($trimmed, '--') || str_starts_with($trimmed, '/*') || empty($trimmed))) {
                continue;
            }
            $foundSql = true;
            $cleaned[] = $line;
        }
        return trim(implode("\n", $cleaned));
    }

    /**
     * Obtém o ID da empresa atual através da requisição ou do usuário autenticado.
     */
    private function getEmpresaId(?Request $request = null)
    {
        $empresaId = null;
        if ($request && $request->has('empresa_id')) {
            $empresaId = $request->empresa_id;
        } elseif (app()->bound('request') && request()->has('empresa_id')) {
            $empresaId = request()->empresa_id;
        }

        if (!$empresaId && Auth::check() && Auth::user()->empresa) {
            $empresaId = Auth::user()->empresa->empresa_id;
        }
        return $empresaId ? (int)$empresaId : null;
    }

    /**
     * Retorna a lista de tabelas que possuem a coluna empresa_id no banco de dados.
     * Detecta dinamicamente via INFORMATION_SCHEMA.
     */
    private function getTabelasComEmpresaId(): array
    {
        try {
            $banco = config('database.connections.mysql.database');
            $tabelas = DB::select("
                SELECT TABLE_NAME
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_SCHEMA = ?
                  AND COLUMN_NAME = 'empresa_id'
                ORDER BY TABLE_NAME
            ", [$banco]);

            return array_map(fn($t) => $t->TABLE_NAME, $tabelas);
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Formata tamanho em bytes para exibição legível (KB, MB, GB).
     */
    private function formatarTamanho(int $bytes): string
    {
        if ($bytes >= 1073741824) return round($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576)    return round($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024)       return round($bytes / 1024, 2) . ' KB';
        return $bytes . ' bytes';
    }
}
