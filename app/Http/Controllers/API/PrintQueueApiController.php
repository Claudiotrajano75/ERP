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
     */
    public function pendentes(Request $request)
    {
        $empresa_id = $request->input('empresa_id');
        
        // Pega os pendentes criados nos últimos 30 minutos
        $limiteTempo = Carbon::now()->subMinutes(30);

        $query = FilaImpressao::where('status', 'pendente')
            ->where('created_at', '>=', $limiteTempo);

        if ($empresa_id) {
            $empresas = array_map('trim', explode(',', $empresa_id));
            $query->whereIn('empresa_id', $empresas);
        }

        $trabalhos = $query->orderBy('id', 'asc')
            ->limit(10)
            ->get();

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
