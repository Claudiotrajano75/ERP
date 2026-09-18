<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Empresa;
use App\Models\ContaReceber;
use App\Models\ContaPagar;
use App\Models\Notificacao;
use App\Models\ItemNfe;
use App\Models\Produto;
use App\Models\Estoque;
use App\Models\ConfigGeral;
use App\Models\Agendamento;
use App\Models\ConfiguracaoAgendamento;
use App\Models\Nfe;
use App\Models\Nfce;
use App\Models\PlanoEmpresa;
use App\Utils\WhatsAppUtil;
use NFePHP\Common\Certificate;
use Carbon\Carbon;

class AlertaCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alerta:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cria alertas inteligentes para empresas';

    /**
     * Execute the console command.
     */

    protected $whatsAppUtil;

    public function __construct(WhatsAppUtil $whatsAppUtil){
        parent::__construct();
        $this->whatsAppUtil = $whatsAppUtil;
    }

    public function handle()
    {
        $empresas = Empresa::where('status', 1)->get();

        foreach($empresas as $empresa){

            // Sincroniza e gera todos os alertas operacionais da empresa com deduplicação inteligente
            \App\Services\AlertaService::sincronizarEmpresa($empresa->id);

            // Agendamentos via WhatsApp
            $configuracaoAgendamento = ConfiguracaoAgendamento::where('empresa_id', $empresa->id)
                ->first();

            if($configuracaoAgendamento != null && $configuracaoAgendamento->token_whatsapp){
                $this->criaAlertaAgendamento($configuracaoAgendamento);
            }
        }
    }

    private function criaAlertaAgendamento($config){
        $agendamentos = $this->getAgendamentosHoje($config->empresa_id);

        if($config->msg_wpp_manha && $config->msg_wpp_manha_horario){
            $dataAtual = date('Y-m-d H:i');
            $dataEnvio = date('Y-m-d ') . $config->msg_wpp_manha_horario;
            if(strtotime($dataAtual) >= strtotime($dataEnvio)){
                foreach($agendamentos as $a){

                    if($a->cliente && $a->cliente->telefone && $a->msg_wpp_manha_horario == 0){
                        $msg = $this->criaMensagemAgendamento($a, $config->mensagem_manha);
                        if($msg != ""){
                            $telefone = "55".preg_replace('/[^0-9]/', '', $a->cliente->telefone);
                            $retorno = $this->whatsAppUtil->sendMessageWithToken($telefone, $msg, $config->empresa_id, $config->token_whatsapp);
                            $retorno = json_decode($retorno);
                            if($retorno && isset($retorno->success) && $retorno->success){
                                $a->msg_wpp_manha_horario = 1;
                                $a->save();
                            }
                        }
                    }
                }
            }
        }
        if($config->msg_wpp_alerta){
            $dataAtual = date('Y-m-d H:i');
            foreach($agendamentos as $a){
                $dataEnvio = date('Y-m-d H:i', strtotime($a->data . " " . $a->inicio . "- $config->msg_wpp_alerta_minutos_antecedencia minutes"));

                if(strtotime($dataAtual) >= strtotime($dataEnvio)){
                    foreach($agendamentos as $a){
                        if($a->cliente && $a->cliente->telefone && $a->msg_wpp_alerta_horario == 0){

                            $msg = $this->criaMensagemAgendamento($a, $config->mensagem_alerta);
                            if($msg != ""){
                                $telefone = "55".preg_replace('/[^0-9]/', '', $a->cliente->telefone);
                                $retorno = $this->whatsAppUtil->sendMessageWithToken($telefone, $msg, $config->empresa_id, $config->token_whatsapp);
                                $retorno = json_decode($retorno);
                                if($retorno && isset($retorno->success) && $retorno->success){
                                    $a->msg_wpp_alerta_horario = 1;
                                    $a->save();
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    private function criaMensagemAgendamento($agendamento, $msg){
        if(strlen(trim($msg)) == 0) return "";
        $clienteNome = $agendamento->cliente ? $agendamento->cliente->razao_social : 'Cliente';
        $msg = str_replace("%nome%", $clienteNome, $msg);
        $msg = str_replace("%data%", __data_pt($agendamento->data, 0), $msg);
        $msg = str_replace("%hora%", substr($agendamento->inicio, 0, 5), $msg);
        return $msg;
    }

    private function getAgendamentosHoje($empresa_id){
        return Agendamento::where('empresa_id', $empresa_id)
        ->whereDate('data', date('Y-m-d'))->get();
    }

    private function criaNotificacao($tabela, $referencia, $empresa, $titulo, $descricaoCurta, $objeto, $prioridade = 'baixa'){
        $item = Notificacao::where('empresa_id', $empresa->id)
        ->where('tabela', $tabela)
        ->where('referencia', (string)$referencia)->first();

        if($item == null){
            $descricao = $this->getDescricao($tabela, $objeto);
            Notificacao::create([
                'empresa_id' => $empresa->id,
                'tabela' => $tabela,
                'descricao' => $descricao,
                'descricao_curta' => $descricaoCurta,
                'referencia' => (string)$referencia,
                'status' => 1,
                'por_sistema' => 1,
                'prioridade' => $prioridade, 
                'visualizada' => 0,
                'titulo' => $titulo
            ]);
        }
    }

    private function getDescricao($tabela, $item){
        if($tabela == 'conta_recebers'){
            return view('notificacao.partials.conta_receber', compact('item'))->render();
        }
        if($tabela == 'conta_pagars'){
            return view('notificacao.partials.conta_pagar', compact('item'))->render();
        }
        if($tabela == 'compras'){
            return view('notificacao.partials.compras', compact('item'))->render();
        }
        if($tabela == 'estoques'){
            return view('notificacao.partials.estoques', compact('item'))->render();
        }
        if($tabela == 'empresas'){
            return view('notificacao.partials.certificado', compact('item'))->render();
        }
        if($tabela == 'nfe' || $tabela == 'nfce'){
            return view('notificacao.partials.nfe_rejeitada', compact('item'))->render();
        }
        if($tabela == 'planos'){
            return view('notificacao.partials.plano_expirando', compact('item'))->render();
        }
        return '';
    }
}
