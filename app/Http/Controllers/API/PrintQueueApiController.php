<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FilaImpressao;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PrintQueueApiController extends Controller
{
    /**
     * Retorna os trabalhos de impressão pendentes para a empresa
     * GET /api/print-queue/pendentes?empresa_id=48
     *
     * Inclui retry de trabalhos marcados como 'erro' (falha de rede/impressora)
     * até 5 tentativas, e evita devolver de novo um trabalho entregue nos
     * últimos 60s (proteção contra re-impressão quando o agente cai antes
     * de marcar o trabalho como concluído).
     */
    public function pendentes(Request $request)
    {
        $empresa_id = $request->input('empresa_id');
        
        // Janela de retomada: trabalhos pendentes das últimas 24h
        // (antes eram descartados após 30min sem impressão)
        $limiteTempo = Carbon::now()->subHours(24);
        $agora       = Carbon::now();

        $query = FilaImpressao::where(function ($q) use ($limiteTempo) {
                $q->where(function ($qq) use ($limiteTempo) {
                    // Pendentes das últimas 24h
                    $qq->where('status', 'pendente')
                       ->where('created_at', '>=', $limiteTempo);
                })->orWhere(function ($qq) {
                    // Erros: busca reprocessar ate 5 tentativas
                    $qq->where('status', 'erro')
                       ->where('tentativas', '<', 5);
                });
            })
            // Nao entregue nas ultimas 60s (agente pode estar imprimindo agora)
            ->where(function ($q) use ($agora) {
                $q->whereNull('tentativa_em')
                  ->orWhere('tentativa_em', '<=', Carbon::now()->subSeconds(60));
            });

        if ($empresa_id) {
            $empresas = array_map('trim', explode(',', $empresa_id));
            $query->whereIn('empresa_id', $empresas);
        }

        $trabalhos = $query->orderBy('id', 'asc')
            ->limit(10)
            ->get();

        // Registra a entrega ao agente (retry tracking + anti-duplicado)
        foreach ($trabalhos as $job) {
            $job->tentativas   = $job->tentativas + 1;
            $job->tentativa_em = $agora;
            $job->save();
        }

        return response()->json([
            'success' => true,
            'total'   => count($trabalhos),
            'data'    => $trabalhos
        ]);
    }

    /**
     * Marca o trabalho de impressão como concluído ('impresso') ou 'erro'
     * POST /api/print-queue/concluir
     */
    public function concluir(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status', 'impresso');
        $erro = $request->input('erro');

        if (!$id) {
            return response()->json(['success' => false, 'message' => 'ID do trabalho é obrigatório'], 400);
        }

        $job = FilaImpressao::find($id);
        if (!$job) {
            return response()->json(['success' => false, 'message' => 'Trabalho de impressão não encontrado'], 404);
        }

        $job->status = ($status === 'erro') ? 'erro' : 'impresso';
        if ($erro) {
            $job->erro_mensagem = substr((string)$erro, 0, 1000);
        }
        $job->impresso_em = Carbon::now();
        $job->save();

        Log::info("PrintQueue: Trabalho #{$id} ({$job->tipo}) marcado como {$job->status}");

        return response()->json([
            'success' => true,
            'message' => "Trabalho #{$id} atualizado com sucesso"
        ]);
    }

    /**
     * Limpa trabalhos antigos da fila (> 24 horas)
     * POST /api/print-queue/limpar
     */
    public function limpar(Request $request)
    {
        $limite = Carbon::now()->subHours(24);
        $totalDeletados = FilaImpressao::where('created_at', '<', $limite)->delete();

        return response()->json([
            'success' => true,
            'deletados' => $totalDeletados
        ]);
    }
}
