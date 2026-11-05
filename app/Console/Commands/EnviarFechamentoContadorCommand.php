<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Empresa;
use App\Models\EscritorioContabil;
use App\Http\Controllers\ContadorAdminFechamentoController;
use App\Utils\EmailUtil;
use Carbon\Carbon;

class EnviarFechamentoContadorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fiscal:enviar-fechamento-contador 
                            {--mes= : Mês de referência (1-12)} 
                            {--ano= : Ano de referência (ex: 2026)} 
                            {--empresa_id= : ID da empresa específica (opcional)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera e envia automaticamente o pacote mensal de documentos fiscais e SPED (.ZIP) para os escritórios contábeis.';

    /**
     * Execute the console command.
     */
    public function handle(EmailUtil $emailUtil, ContadorAdminFechamentoController $fechamentoController)
    {
        $this->info("Iniciando rotina de fechamento fiscal mensal para os contadores...");

        // Se não informado, calcula o mês anterior
        $dataBase = Carbon::now()->subMonth();
        $mes = (int)($this->option('mes') ?: $dataBase->format('m'));
        $ano = (int)($this->option('ano') ?: $dataBase->format('Y'));

        $mesStr = str_pad($mes, 2, '0', STR_PAD_LEFT);
        $dataInicial = Carbon::createFromDate($ano, $mes, 1)->startOfMonth()->format('Y-m-d');
        $dataFinal = Carbon::createFromDate($ano, $mes, 1)->endOfMonth()->format('Y-m-d');

        $this->line("Período de referência: {$mesStr}/{$ano} ({$dataInicial} até {$dataFinal})");

        $empresaId = $this->option('empresa_id');
        $query = Empresa::where('status', 1);
        if ($empresaId) {
            $query->where('id', $empresaId);
        }

        $empresas = $query->get();
        $enviados = 0;
        $erros = 0;

        foreach ($empresas as $empresa) {
            $escritorio = EscritorioContabil::where('empresa_id', $empresa->id)->first();
            if (!$escritorio || empty($escritorio->email)) {
                $this->line("<comment>[IGNORADO]</comment> Empresa #{$empresa->id} ({$empresa->nome}): Sem escritório contábil ou e-mail cadastrado.");
                continue;
            }

            $this->line("Processando #{$empresa->id} - {$empresa->nome}...");

            $zipPath = $fechamentoController->gerarPacoteZip($empresa, $dataInicial, $dataFinal, $mes, $ano);

            if (!$zipPath || !file_exists($zipPath)) {
                $this->line("<comment>[VAZIO]</comment> Nenhum XML ou documento fiscal encontrado para a empresa no período.");
                continue;
            }

            $body = "Prezado(a) Contador(a),<br><br>";
            $body .= "Segue em anexo o fechamento fiscal consolidado do mês <strong>{$mesStr}/{$ano}</strong> da sua empresa cliente: <strong>{$empresa->nome}</strong> (CNPJ: {$empresa->cpf_cnpj}).<br><br>";
            $body .= "O pacote compactado (.ZIP) contém os arquivos XMLs de Saídas, Entradas (Compras), NFC-e e o SPED Fiscal EFD gerado.<br><br>";
            $body .= "Atenciosamente,<br><strong>" . config('app.name', 'ERP') . "</strong>";

            $sucesso = $emailUtil->enviarXmlContadorZip($empresa->id, $zipPath, "Fechamento Fiscal {$mesStr}/{$ano} - {$empresa->nome}", $body);

            if ($sucesso) {
                $this->info("<info>[ENVIADO]</info> Pacote enviado para: {$escritorio->email}");
                $enviados++;
            } else {
                $this->error("[FALHA] Erro ao enviar e-mail para: {$escritorio->email}");
                $erros++;
            }
        }

        $this->info("Rotina concluída! Enviados com sucesso: {$enviados} | Falhas: {$erros}");
        return Command::SUCCESS;
    }
}

