<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nfe;
use App\Models\Cliente;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FaturamentoNfeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $empresa_id = request()->empresa_id ?? auth()->user()->empresa->empresa_id ?? null;

        // Base Query por Empresa
        $baseQuery = Nfe::query();
        if ($empresa_id) {
            $baseQuery->where('empresa_id', $empresa_id);
        }
        // Excluir orçamentos puros se aplicável
        $baseQuery->where('orcamento', 0);

        $hoje = Carbon::today()->format('Y-m-d');

        // ==========================================
        // KPIS (Tempo Real)
        // ==========================================
        $kpis = [
            'pedidos_pendentes' => [
                'qtd'   => (clone $baseQuery)->whereIn('estado', ['novo', 'rejeitado'])->count(),
                'valor' => (clone $baseQuery)->whereIn('estado', ['novo', 'rejeitado'])->sum('total') ?? 0,
            ],
            'prontos_faturar' => [
                'qtd'   => (clone $baseQuery)->where('estado', 'novo')->count(),
                'valor' => (clone $baseQuery)->where('estado', 'novo')->sum('total') ?? 0,
            ],
            'nfes_emitidas_hoje' => [
                'qtd'   => (clone $baseQuery)->where('estado', 'aprovado')->whereDate('created_at', $hoje)->count(),
                'valor' => (clone $baseQuery)->where('estado', 'aprovado')->whereDate('created_at', $hoje)->sum('total') ?? 0,
            ],
            'nfes_canceladas' => [
                'qtd'   => (clone $baseQuery)->where('estado', 'cancelado')->count(),
                'valor' => (clone $baseQuery)->where('estado', 'cancelado')->sum('total') ?? 0,
            ],
            'valor_faturado_hoje' => [
                'qtd'   => null,
                'valor' => (clone $baseQuery)->where('estado', 'aprovado')->whereDate('created_at', $hoje)->sum('total') ?? 0,
            ],
            'aguardando_sefaz' => [
                'qtd'   => 0,
                'valor' => 0.00,
            ],
        ];

        // ==========================================
        // CONTADORES DAS ABAS
        // ==========================================
        $tabs = [
            'pendentes'  => (clone $baseQuery)->whereIn('estado', ['novo', 'rejeitado'])->count(),
            'emitidas'   => (clone $baseQuery)->where('estado', 'aprovado')->count(),
            'rejeicoes'  => (clone $baseQuery)->where('estado', 'rejeitado')->count(),
            'canceladas' => (clone $baseQuery)->where('estado', 'cancelado')->count(),
            'correcao'   => (clone $baseQuery)->where('sequencia_cce', '>', 0)->count(),
        ];

        // ==========================================
        // FILTRAGEM DINÂMICA
        // ==========================================
        $activeTab = $request->get('tab', 'pendentes');
        $query = clone $baseQuery;

        // Filtro por Aba Ativa
        switch ($activeTab) {
            case 'emitidas':
                $query->where('estado', 'aprovado');
                break;
            case 'rejeicoes':
                $query->where('estado', 'rejeitado');
                break;
            case 'canceladas':
                $query->where('estado', 'cancelado');
                break;
            case 'correcao':
                $query->where('sequencia_cce', '>', 0);
                break;
            case 'pendentes':
            default:
                $query->whereIn('estado', ['novo', 'rejeitado']);
                break;
        }

        // Filtro por Cliente (Razão Social, Nome Fantasia ou CNPJ)
        if ($request->filled('cliente')) {
            $clienteTerm = trim($request->get('cliente'));
            $query->where(function ($q) use ($clienteTerm) {
                $q->whereHas('cliente', function ($q2) use ($clienteTerm) {
                    $q2->where('razao_social', 'like', "%{$clienteTerm}%")
                       ->orWhere('nome_fantasia', 'like', "%{$clienteTerm}%")
                       ->orWhere('cpf_cnpj', 'like', "%{$clienteTerm}%");
                })->orWhere('emissor_nome', 'like', "%{$clienteTerm}%")
                  ->orWhere('emissor_cpf_cnpj', 'like', "%{$clienteTerm}%");
            });
        }

        // Filtro por Data Inicial
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->get('start_date'));
        }

        // Filtro por Data Final
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->get('end_date'));
        }

        // Filtro por Estado Faturamento
        if ($request->filled('estado_faturamento')) {
            if ($request->get('estado_faturamento') === 'pendente') {
                $query->whereIn('estado', ['novo', 'rejeitado']);
            } elseif ($request->get('estado_faturamento') === 'faturado') {
                $query->where('estado', 'aprovado');
            }
        }

        // Filtro por Situação Fiscal
        if ($request->filled('situacao_fiscal')) {
            $st = $request->get('situacao_fiscal');
            if ($st === 'pendente') {
                $query->where('estado', 'novo');
            } elseif ($st === 'autorizada') {
                $query->where('estado', 'aprovado');
            } elseif ($st === 'rejeitada') {
                $query->where('estado', 'rejeitado');
            } elseif ($st === 'cancelada') {
                $query->where('estado', 'cancelado');
            }
        }

        // Carregar relacionamentos e paginar
        $pedidos = $query->with(['cliente', 'natureza', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('nfe.central_faturamento', compact('kpis', 'tabs', 'pedidos', 'activeTab'));
    }
}