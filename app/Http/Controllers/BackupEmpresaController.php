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
     * Obtém o ID da empresa atual através da requisição ou do usuário autenticado.
     */
    private function getEmpresaId()
    {
        $empresaId = request()->empresa_id;
        if (!$empresaId && Auth::check() && Auth::user()->empresa) {
            $empresaId = Auth::user()->empresa->empresa_id;
        }
        return $empresaId;
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
