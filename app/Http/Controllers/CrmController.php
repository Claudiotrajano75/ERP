<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Nfe;
use App\Models\Nfce;
use App\Models\ContaReceber;
use App\Models\PedidoEcommerce;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CrmController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $empresa_id = request()->empresa_id ?? (auth()->user() ? auth()->user()->empresa_id : null);
        $pesquisa = $request->get('pesquisa');
        $segmentoFiltro = $request->get('segmento');

        // Total clientes cadastrados
        $totalClientes = Cliente::where('empresa_id', $empresa_id)->count();

        // 1. Obter consolidação de vendas por cliente (NF-e aprovadas)
        $vendasPorCliente = Nfe::select(
                'cliente_id',
                DB::raw('COUNT(id) as total_compras'),
                DB::raw('SUM(total) as valor_total'),
                DB::raw('MAX(created_at) as ultima_compra')
            )
            ->where('empresa_id', $empresa_id)
            ->where('estado', 'aprovado')
            ->whereNotNull('cliente_id')
            ->groupBy('cliente_id')
            ->get()
            ->keyBy('cliente_id');

        // Buscar clientes com paginação ou lista
        $clientes = Cliente::where('empresa_id', $empresa_id)
            ->when($pesquisa, function($q) use ($pesquisa) {
                return $q->where(function($sub) use ($pesquisa) {
                    $sub->where('razao_social', 'like', "%{$pesquisa}%")
                        ->orWhere('nome_fantasia', 'like', "%{$pesquisa}%")
                        ->orWhere('cpf_cnpj', 'like', "%{$pesquisa}%")
                        ->orWhere('telefone', 'like', "%{$pesquisa}%");
                });
            })
            ->with('cidade')
            ->get();

        $agora = Carbon::now();
        $totalFaturamentoGeral = 0;
        $contagemSegmentos = [
            'campeoes' => 0,
            'fieis' => 0,
            'novos' => 0,
            'em_risco' => 0,
            'inativos' => 0,
            'sem_compras' => 0
        ];

        // Processar métricas RFM para cada cliente
        $clientesProcessados = $clientes->map(function($cliente) use ($vendasPorCliente, $agora, &$totalFaturamentoGeral, &$contagemSegmentos) {
            $dadosVenda = $vendasPorCliente->get($cliente->id);

            $totalCompras = $dadosVenda ? (int)$dadosVenda->total_compras : 0;
            $valorTotal = $dadosVenda ? (float)$dadosVenda->valor_total : 0;
            $ultimaCompra = $dadosVenda ? Carbon::parse($dadosVenda->ultima_compra) : null;
            $diasSemComprar = $ultimaCompra ? $agora->diffInDays($ultimaCompra) : null;
            $ticketMedio = $totalCompras > 0 ? ($valorTotal / $totalCompras) : 0;

            $totalFaturamentoGeral += $valorTotal;

            // Classificação RFM
            if ($totalCompras == 0) {
                $segmento = 'sem_compras';
                $badge = ['label' => 'Sem Compras', 'class' => 'bg-secondary text-white'];
            } elseif ($diasSemComprar <= 30 && $valorTotal >= 1000) {
                $segmento = 'campeoes';
                $badge = ['label' => '★ Campeão / VIP', 'class' => 'bg-success text-white'];
            } elseif ($diasSemComprar <= 60 && $totalCompras >= 3) {
                $segmento = 'fieis';
                $badge = ['label' => 'Cliente Fiel', 'class' => 'bg-primary text-white'];
            } elseif ($diasSemComprar <= 30 && $totalCompras < 3) {
                $segmento = 'novos';
                $badge = ['label' => 'Novo Promissor', 'class' => 'bg-info text-white'];
            } elseif ($diasSemComprar > 60 && $diasSemComprar <= 120) {
                $segmento = 'em_risco';
                $badge = ['label' => '⚠️ Em Risco (60d+)', 'class' => 'bg-warning text-dark'];
            } else {
                $segmento = 'inativos';
                $badge = ['label' => '⛔ Inativo (+120d)', 'class' => 'bg-danger text-white'];
            }

            $contagemSegmentos[$segmento]++;

            $cliente->total_compras = $totalCompras;
            $cliente->valor_total = $valorTotal;
            $cliente->ticket_medio = $ticketMedio;
            $cliente->ultima_compra = $ultimaCompra;
            $cliente->dias_sem_comprar = $diasSemComprar;
            $cliente->segmento = $segmento;
            $cliente->badge = $badge;

            return $cliente;
        });

        // Filtrar por segmento se requisitado
        if ($segmentoFiltro && isset($contagemSegmentos[$segmentoFiltro])) {
            $clientesProcessados = $clientesProcessados->filter(function($c) use ($segmentoFiltro) {
                return $c->segmento == $segmentoFiltro;
            });
        }

        // Ordenar por maior valor total de compras
        $topClientes = $clientesProcessados->sortByDesc('valor_total')->take(10)->values();
        $clientesLista = $clientesProcessados->sortByDesc('valor_total')->values();

        $ticketMedioGeral = $clientesProcessados->where('total_compras', '>', 0)->count() > 0 
            ? $totalFaturamentoGeral / $clientesProcessados->where('total_compras', '>', 0)->count() 
            : 0;

        return view('crm.index', compact(
            'totalClientes',
            'totalFaturamentoGeral',
            'ticketMedioGeral',
            'contagemSegmentos',
            'topClientes',
            'clientesLista',
            'pesquisa',
            'segmentoFiltro'
        ));
    }

    public function show($id)
    {
        $empresa_id = request()->empresa_id ?? (auth()->user() ? auth()->user()->empresa_id : null);
        $cliente = Cliente::where('empresa_id', $empresa_id)->with('cidade')->findOrFail($id);

        // Histórico de NF-e (Vendas)
        $nfes = Nfe::where('empresa_id', $empresa_id)
            ->where('cliente_id', $cliente->id)
            ->where('tpNF', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        // Histórico de Contas a Receber
        $contas = ContaReceber::where('empresa_id', $empresa_id)
            ->where('cliente_id', $cliente->id)
            ->orderBy('data_vencimento', 'desc')
            ->get();

        // Totalizador
        $totalComprado = $nfes->where('estado', 'aprovado')->sum('total');
        $totalAberto = $contas->where('status', 0)->sum('valor_integral');
        $totalRecebido = $contas->where('status', 1)->sum('valor_recebido');

        return view('crm.show', compact('cliente', 'nfes', 'contas', 'totalComprado', 'totalAberto', 'totalRecebido'));
    }
}
