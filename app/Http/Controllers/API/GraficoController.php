<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ContaPagar;
use App\Models\ContaReceber;
use App\Models\Cte;
use App\Models\Mdfe;
use Illuminate\Http\Request;
use App\Models\Nfe;
use App\Models\Nfce;
use App\Models\Cliente;
use App\Models\Produto;
use App\Models\Localizacao;

class GraficoController extends Controller
{
    public function dadosDards(Request $request){
        $periodo = $request->periodo;
        $empresa_id = $request->empresa_id;
        $usuario_id = $request->usuario_id;
        $local_id = $request->local_id;

        $locais = Localizacao::where('usuario_localizacaos.usuario_id', $usuario_id)
        ->select('localizacaos.*')
        ->join('usuario_localizacaos', 'usuario_localizacaos.localizacao_id', '=', 'localizacaos.id')
        ->where('localizacaos.status', 1)->get();
        $locais = $locais->pluck(['id']);

        $somaVendas = Nfe::
        where('empresa_id', $empresa_id)
        ->when($periodo == 1, function ($query) {
            return $query->whereDate('created_at', date('Y-m-d'));
        })
        ->when($periodo == 7, function ($query) {
            return $query->whereRaw('WEEK(created_at) = ' . (date('W')-1));
        })
        ->when($periodo == 30, function ($query) {
            return $query->whereMonth('created_at', date('m'));
        })
        ->when($periodo == 365, function ($query) {
            return $query->whereYear('created_at', date('Y'));
        })
        ->when($local_id, function ($query) use ($local_id) {
            return $query->where('local_id', $local_id);
        })
        ->when(!$local_id, function ($query) use ($locais) {
            return $query->whereIn('local_id', $locais);
        })
        ->where('tpNF', 1)
        ->where('orcamento', 0)
        ->sum('total');

        $somaVendasPdv = Nfce::
        where('empresa_id', $empresa_id)
        ->when($periodo == 1, function ($query) {
            return $query->whereDate('created_at', date('Y-m-d'));
        })
        ->when($periodo == 7, function ($query) {
            return $query->whereRaw('WEEK(created_at) = ' . (date('W')-1));
        })
        ->when($periodo == 30, function ($query) {
            return $query->whereMonth('created_at', date('m'));
        })
        ->when($periodo == 365, function ($query) {
            return $query->whereYear('created_at', date('Y'));
        })
        ->when($local_id, function ($query) use ($local_id) {
            return $query->where('local_id', $local_id);
        })
        ->when(!$local_id, function ($query) use ($locais) {
            return $query->whereIn('local_id', $locais);
        })
        ->sum('total');

        $totalClientes = Cliente::
        where('empresa_id', $empresa_id)
        ->when($periodo == 1, function ($query) {
            return $query->whereDate('created_at', date('Y-m-d'));
        })
        ->when($periodo == 7, function ($query) {
            return $query->whereRaw('WEEK(created_at) = ' . (date('W')-1));
        })
        ->when($periodo == 30, function ($query) {
            return $query->whereMonth('created_at', date('m'));
        })
        ->when($periodo == 365, function ($query) {
            return $query->whereYear('created_at', date('Y'));
        })
        ->count('id');

        $totalProdutos = Produto::
        where('empresa_id', $empresa_id)
        ->select('produtos.*')
        ->when($periodo == 1, function ($query) {
            return $query->whereDate('produtos.created_at', date('Y-m-d'));
        })
        ->when($periodo == 7, function ($query) {
            return $query->whereRaw('WEEK(produtos.created_at) = ' . (date('W')-1));
        })
        ->when($periodo == 30, function ($query) {
            return $query->whereMonth('produtos.created_at', date('m'));
        })
        ->when($periodo == 365, function ($query) {
            return $query->whereYear('produtos.created_at', date('Y'));
        })
        ->when($local_id, function ($query) use ($local_id) {
            return $query->join('produto_localizacaos', 'produto_localizacaos.produto_id', '=', 'produtos.id')
            ->where('produto_localizacaos.localizacao_id', $local_id);
        })
        ->when(!$local_id, function ($query) use ($locais) {
            return $query->join('produto_localizacaos', 'produto_localizacaos.produto_id', '=', 'produtos.id')
            ->whereIn('produto_localizacaos.localizacao_id', $locais);
        })
        ->count('produtos.id');

        $somaCompras = Nfe::
        where('empresa_id', $empresa_id)
        ->when($periodo == 1, function ($query) {
            return $query->whereDate('created_at', date('Y-m-d'));
        })
        ->when($periodo == 7, function ($query) {
            return $query->whereRaw('WEEK(created_at) = ' . (date('W')-1));
        })
        ->when($periodo == 30, function ($query) {
            return $query->whereMonth('created_at', date('m'));
        })
        ->when($periodo == 365, function ($query) {
            return $query->whereYear('created_at', date('Y'));
        })
        ->when($local_id, function ($query) use ($local_id) {
            return $query->where('local_id', $local_id);
        })
        ->when(!$local_id, function ($query) use ($locais) {
            return $query->whereIn('local_id', $locais);
        })
        ->where('tpNF', 0)
        ->sum('total');

        $somaContaReceber = ContaReceber::
        where('empresa_id', $empresa_id)
        ->where('status', 0)
        ->when($periodo == 1, function ($query) {
            return $query->whereDate('data_vencimento', date('Y-m-d'));
        })
        ->when($periodo == 7, function ($query) {
            return $query->whereRaw('WEEK(data_vencimento) = ' . (date('W')-1));
        })
        ->when($periodo == 30, function ($query) {
            return $query->whereMonth('data_vencimento', date('m'));
        })
        ->when($periodo == 365, function ($query) {
            return $query->whereYear('data_vencimento', date('Y'));
        })
        ->when($local_id, function ($query) use ($local_id) {
            return $query->where('local_id', $local_id);
        })
        ->when(!$local_id, function ($query) use ($locais) {
            return $query->whereIn('local_id', $locais);
        })
        ->sum('valor_integral');

        $somaContaPagar = ContaPagar::
        where('empresa_id', $empresa_id)
        ->where('status', 0)
        ->when($periodo == 1, function ($query) {
            return $query->whereDate('data_vencimento', date('Y-m-d'));
        })
        ->when($periodo == 7, function ($query) {
            return $query->whereRaw('WEEK(data_vencimento) = ' . (date('W')-1));
        })
        ->when($periodo == 30, function ($query) {
            return $query->whereMonth('data_vencimento', date('m'));
        })
        ->when($periodo == 365, function ($query) {
            return $query->whereYear('data_vencimento', date('Y'));
        })
        ->when($local_id, function ($query) use ($local_id) {
            return $query->where('local_id', $local_id);
        })
        ->when(!$local_id, function ($query) use ($locais) {
            return $query->whereIn('local_id', $locais);
        })
        ->sum('valor_integral');

        $data = [
            'vendas' => $somaVendas + $somaVendasPdv,
            'compras' => $somaCompras,
            'clientes' => $totalClientes,
            'produtos' => $totalProdutos,
            'contas_receber' => $somaContaReceber,
            'contas_pagar' => $somaContaPagar
        ];

        return response()->json($data, 200);
    }

    /**
     * Retorna dados avançados do dashboard:
     * - variação % vs mês anterior para cada KPI
     * - Top 5 produtos mais vendidos no período
     * - Alertas de estoque abaixo do mínimo
     * - Contas vencendo hoje/amanhã
     */
    public function dadosCardsAvancado(Request $request)
    {
        $empresa_id  = $request->empresa_id;
        $periodo     = $request->periodo ?? 30;
        $local_id    = $request->local_id;
        $usuario_id  = $request->usuario_id;

        // Locais do usuário
        $locais = Localizacao::where('usuario_localizacaos.usuario_id', $usuario_id)
            ->select('localizacaos.*')
            ->join('usuario_localizacaos', 'usuario_localizacaos.localizacao_id', '=', 'localizacaos.id')
            ->where('localizacaos.status', 1)->get()->pluck('id');

        // ── Helper: soma vendas NFe + NFCe no período ──────────────────────
        $somaVendasPeriodo = function ($mesOffset = 0) use ($empresa_id, $periodo, $local_id, $locais) {
            $mesRef  = date('m') - $mesOffset;
            $anoRef  = date('Y');
            if ($mesRef <= 0) { $mesRef += 12; $anoRef--; }

            $nfe = Nfe::where('empresa_id', $empresa_id)
                ->where('tpNF', 1)->where('orcamento', 0)
                ->where('estado', '!=', 'cancelado')
                ->when($periodo == 1  && $mesOffset == 0, fn($q) => $q->whereDate('created_at', date('Y-m-d')))
                ->when($periodo == 7  && $mesOffset == 0, fn($q) => $q->whereRaw('WEEK(created_at) = ' . (date('W') - 1)))
                ->when($periodo == 30 || $mesOffset > 0,  fn($q) => $q->whereMonth('created_at', $mesRef)->whereYear('created_at', $anoRef))
                ->when($periodo == 365 && $mesOffset == 0, fn($q) => $q->whereYear('created_at', date('Y')))
                ->when($local_id, fn($q) => $q->where('local_id', $local_id))
                ->when(!$local_id, fn($q) => $q->whereIn('local_id', $locais))
                ->sum('total');

            $nfce = Nfce::where('empresa_id', $empresa_id)
                ->where('estado', '!=', 'cancelado')
                ->when($periodo == 1  && $mesOffset == 0, fn($q) => $q->whereDate('created_at', date('Y-m-d')))
                ->when($periodo == 7  && $mesOffset == 0, fn($q) => $q->whereRaw('WEEK(created_at) = ' . (date('W') - 1)))
                ->when($periodo == 30 || $mesOffset > 0,  fn($q) => $q->whereMonth('created_at', $mesRef)->whereYear('created_at', $anoRef))
                ->when($periodo == 365 && $mesOffset == 0, fn($q) => $q->whereYear('created_at', date('Y')))
                ->when($local_id, fn($q) => $q->where('local_id', $local_id))
                ->when(!$local_id, fn($q) => $q->whereIn('local_id', $locais))
                ->sum('total');

            return $nfe + $nfce;
        };

        // ── Vendas atual vs anterior ────────────────────────────────────────
        $vendasAtual    = $somaVendasPeriodo(0);
        $vendasAnterior = $somaVendasPeriodo(1);
        $vendasVariacao = $vendasAnterior > 0
            ? round((($vendasAtual - $vendasAnterior) / $vendasAnterior) * 100, 1)
            : ($vendasAtual > 0 ? 100 : 0);

        // ── Contas a Receber: atual vs anterior ────────────────────────────
        $receberAtual = \App\Models\ContaReceber::where('empresa_id', $empresa_id)
            ->where('status', 0)->whereMonth('data_vencimento', date('m'))->sum('valor_integral');
        $receberAnterior = \App\Models\ContaReceber::where('empresa_id', $empresa_id)
            ->where('status', 0)->whereMonth('data_vencimento', date('m') - 1 ?: 12)->sum('valor_integral');
        $receberVariacao = $receberAnterior > 0
            ? round((($receberAtual - $receberAnterior) / $receberAnterior) * 100, 1)
            : ($receberAtual > 0 ? 100 : 0);

        // ── Contas a Pagar: atual vs anterior ──────────────────────────────
        $pagarAtual = \App\Models\ContaPagar::where('empresa_id', $empresa_id)
            ->where('status', 0)->whereMonth('data_vencimento', date('m'))->sum('valor_integral');
        $pagarAnterior = \App\Models\ContaPagar::where('empresa_id', $empresa_id)
            ->where('status', 0)->whereMonth('data_vencimento', date('m') - 1 ?: 12)->sum('valor_integral');
        $pagarVariacao = $pagarAnterior > 0
            ? round((($pagarAtual - $pagarAnterior) / $pagarAnterior) * 100, 1)
            : ($pagarAtual > 0 ? 100 : 0);

        // ── Clientes: atual vs anterior ────────────────────────────────────
        $clientesAtual    = \App\Models\Cliente::where('empresa_id', $empresa_id)->whereMonth('created_at', date('m'))->count();
        $clientesAnterior = \App\Models\Cliente::where('empresa_id', $empresa_id)->whereMonth('created_at', date('m') - 1 ?: 12)->count();
        $clientesVariacao = $clientesAnterior > 0
            ? round((($clientesAtual - $clientesAnterior) / $clientesAnterior) * 100, 1)
            : ($clientesAtual > 0 ? 100 : 0);

        // ── Top 5 Produtos mais vendidos (NFe + NFCe) ──────────────────────
        $topProdutos = [];
        try {
            $topNfe = \Illuminate\Support\Facades\DB::table('item_nves')
                ->join('nves', 'nves.id', '=', 'item_nves.nfe_id')
                ->join('produtos', 'produtos.id', '=', 'item_nves.produto_id')
                ->where('nves.empresa_id', $empresa_id)
                ->where('nves.tpNF', 1)
                ->where('nves.estado', '!=', 'cancelado')
                ->when($periodo == 1,  fn($q) => $q->whereDate('nves.created_at', date('Y-m-d')))
                ->when($periodo == 7,  fn($q) => $q->whereRaw('WEEK(nves.created_at) = ' . (date('W') - 1)))
                ->when($periodo == 30, fn($q) => $q->whereMonth('nves.created_at', date('m'))->whereYear('nves.created_at', date('Y')))
                ->when($periodo == 365,fn($q) => $q->whereYear('nves.created_at', date('Y')))
                ->select('produtos.nome as descricao', \Illuminate\Support\Facades\DB::raw('SUM(item_nves.quantidade) as total_qtd'), \Illuminate\Support\Facades\DB::raw('SUM(item_nves.sub_total) as total_valor'))
                ->groupBy('produtos.id', 'produtos.nome')
                ->get();

            $topNfce = \Illuminate\Support\Facades\DB::table('item_nfces')
                ->join('nfces', 'nfces.id', '=', 'item_nfces.nfce_id')
                ->join('produtos', 'produtos.id', '=', 'item_nfces.produto_id')
                ->where('nfces.empresa_id', $empresa_id)
                ->where('nfces.estado', '!=', 'cancelado')
                ->when($periodo == 1,  fn($q) => $q->whereDate('nfces.created_at', date('Y-m-d')))
                ->when($periodo == 7,  fn($q) => $q->whereRaw('WEEK(nfces.created_at) = ' . (date('W') - 1)))
                ->when($periodo == 30, fn($q) => $q->whereMonth('nfces.created_at', date('m'))->whereYear('nfces.created_at', date('Y')))
                ->when($periodo == 365,fn($q) => $q->whereYear('nfces.created_at', date('Y')))
                ->select('produtos.nome as descricao', \Illuminate\Support\Facades\DB::raw('SUM(item_nfces.quantidade) as total_qtd'), \Illuminate\Support\Facades\DB::raw('SUM(item_nfces.sub_total) as total_valor'))
                ->groupBy('produtos.id', 'produtos.nome')
                ->get();

            $topMap = [];
            foreach ($topNfe->concat($topNfce) as $item) {
                $desc = $item->descricao;
                if (!isset($topMap[$desc])) {
                    $topMap[$desc] = ['descricao' => $desc, 'total_qtd' => 0, 'total_valor' => 0];
                }
                $topMap[$desc]['total_qtd'] += (float)$item->total_qtd;
                $topMap[$desc]['total_valor'] += (float)$item->total_valor;
            }
            usort($topMap, fn($a, $b) => $b['total_valor'] <=> $a['total_valor']);
            $topProdutos = array_values(array_slice($topMap, 0, 5));
        } catch (\Throwable $e) {
            $topProdutos = [];
        }

        // ── Alertas de Estoque Mínimo ──────────────────────────────────────
        $alertasEstoque = [];
        try {
            $alertasEstoque = Produto::where('empresa_id', $empresa_id)
                ->where('status', 1)
                ->where('estoque_minimo', '>', 0)
                ->with('estoque')
                ->get()
                ->filter(function ($p) {
                    $qtd = $p->estoque ? (float)$p->estoque->quantidade : 0;
                    return $qtd <= (float)$p->estoque_minimo;
                })
                ->take(10)
                ->map(function ($p) {
                    return [
                        'id'             => $p->id,
                        'nome'           => $p->nome,
                        'estoque'        => $p->estoque ? (float)$p->estoque->quantidade : 0,
                        'estoque_minimo' => (float)$p->estoque_minimo,
                    ];
                })
                ->values();
        } catch (\Throwable $e) {
            $alertasEstoque = [];
        }

        // ── Contas vencendo hoje e amanhã ──────────────────────────────────
        $contasVencendoHoje = 0;
        $contasVencidas = 0;
        try {
            $contasVencendoHoje = \App\Models\ContaPagar::where('empresa_id', $empresa_id)
                ->where('status', 0)
                ->whereDate('data_vencimento', date('Y-m-d'))
                ->count();

            $contasVencidas = \App\Models\ContaPagar::where('empresa_id', $empresa_id)
                ->where('status', 0)
                ->whereDate('data_vencimento', '<', date('Y-m-d'))
                ->count();
        } catch (\Throwable $e) {
            $contasVencendoHoje = 0;
            $contasVencidas = 0;
        }

        return response()->json([
            'variacoes' => [
                'vendas'   => ['valor' => $vendasAtual,    'variacao' => $vendasVariacao,    'anterior' => $vendasAnterior],
                'receber'  => ['valor' => $receberAtual,   'variacao' => $receberVariacao,   'anterior' => $receberAnterior],
                'pagar'    => ['valor' => $pagarAtual,     'variacao' => $pagarVariacao,     'anterior' => $pagarAnterior],
                'clientes' => ['valor' => $clientesAtual,  'variacao' => $clientesVariacao,  'anterior' => $clientesAnterior],
            ],
            'top_produtos'        => $topProdutos,
            'alertas_estoque'     => $alertasEstoque,
            'contas_vencendo_hoje'=> $contasVencendoHoje,
            'contas_vencidas'     => $contasVencidas,
        ], 200);
    }

    public function graficoVendasMes(Request $request)
    {

        $diaHoje = date('d');
        $mes = date('m');
        $data = [];
        for ($i = 1; $i <= $diaHoje; $i++) {
            $totalNfe = Nfe::where('empresa_id', $request->empresa_id)
            ->where('estado', '!=', 'cancelado')
            ->whereMonth('created_at', date('m'))
            ->whereDay('created_at', ($i < 10 ? "0$i" : $i))
            ->where('tpNF', 1)
            ->sum('total');

            $totalNfce = Nfce::where('empresa_id', $request->empresa_id)
            ->where('estado', '!=', 'cancelado')
            ->whereMonth('created_at', date('m'))
            ->whereDay('created_at', ($i < 10 ? "0$i" : $i))
            ->sum('total');

            array_push($data, [
                'dia' => ($i < 10 ? "0$i" : $i) . "/$mes",
                'valor' => $totalNfe + $totalNfce
            ]);
        }
        return response()->json($data, 200);
    }

    public function graficoComprasMes(Request $request)
    {

        $diaHoje = date('d');
        $mes = date('m');
        $data = [];
        for ($i = 1; $i <= $diaHoje; $i++) {
            $totalNfe = Nfe::where('empresa_id', $request->empresa_id)
            ->where('estado', '!=', 'cancelado')
            ->whereMonth('created_at', date('m'))
            ->whereDay('created_at', ($i < 10 ? "0$i" : $i))
            ->where('tpNF', 0)
            ->sum('total');

            array_push($data, [
                'dia' => ($i < 10 ? "0$i" : $i) . "/$mes",
                'valor' => $totalNfe
            ]);
        }
        return response()->json($data, 200);
    }

    public function graficoMes(Request $request)
    {
        $diaHoje = date('d');
        $mes = date('m');
        $data = [];
        for ($i = 1; $i <= $diaHoje; $i++) {
            $totalNfe = Nfe::where('empresa_id', $request->empresa_id)
            ->where(function ($q) {
                $q->where('estado', 'aprovado')->orWhere('estado', 'cancelado');
            })
            ->whereMonth('created_at', date('m'))
            ->whereDay('created_at', ($i < 10 ? "0$i" : $i))
            ->sum('total');

            $totalNfce = Nfce::where('empresa_id', $request->empresa_id)
            ->where(function ($q) {
                $q->where('estado', 'aprovado')->orWhere('estado', 'cancelado');
            })
            ->whereMonth('created_at', date('m'))
            ->whereDay('created_at', ($i < 10 ? "0$i" : $i))
            ->sum('total');

            array_push($data, [
                'dia' => ($i < 10 ? "0$i" : $i) . "/$mes",
                'valor' => $totalNfe + $totalNfce
            ]);
        }
        return response()->json($data, 200);
    }

    public function graficoMesContador(Request $request)
    {
        $diaHoje = date('d');
        $mes = date('m');
        $data = [];
        for ($i = 1; $i <= $diaHoje; $i++) {
            $totalNfe = Nfe::where('empresa_id', $request->empresa_id)
            ->where(function ($q) {
                $q->where('estado', 'aprovado')->orWhere('estado', 'cancelado');
            })
            ->whereMonth('created_at', date('m'))
            ->whereDay('created_at', ($i < 10 ? "0$i" : $i))
            ->count('id');

            $totalNfce = Nfce::where('empresa_id', $request->empresa_id)
            ->where(function ($q) {
                $q->where('estado', 'aprovado')->orWhere('estado', 'cancelado');
            })
            ->whereMonth('created_at', date('m'))
            ->whereDay('created_at', ($i < 10 ? "0$i" : $i))
            ->count('id');

            array_push($data, [
                'dia' => ($i < 10 ? "0$i" : $i) . "/$mes",
                'valor' => $totalNfe + $totalNfce
            ]);
        }
        return response()->json($data, 200);
    }

    public function graficoUltMeses(Request $request)
    {
        $mes = (int)date('m');
        $ano = date('Y');
        $data = [];
        for ($i = 0; $i < 4; $i++) {
            $totalNfe = Nfe::where('empresa_id', $request->empresa_id)
            ->where(function ($q) {
                $q->where('estado', 'aprovado')->orWhere('estado', 'cancelado');
            })
            ->whereMonth('created_at', $mes)
            ->whereYear('created_at', $ano)
            ->sum('total');

            $totalNfce = Nfce::where('empresa_id', $request->empresa_id)
            ->where(function ($q) {
                $q->where('estado', 'aprovado')->orWhere('estado', 'cancelado');
            })
            ->whereMonth('created_at', $mes)
            ->whereYear('created_at', $ano)
            ->sum('total');

            array_push($data, [
                'dia' => $this->getMes($mes - 1) . "/$ano",
                'valor' => $totalNfe + $totalNfce
            ]);

            if ($mes == 1) {
                $mes = 12;
                $ano--;
            } else {
                $mes--;
            }
        }
        return response()->json($data, 200);
    }

    private function getMes($m)
    {
        $meses = [
            'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set',
            'Out', 'Nov', 'Dez'
        ];
        return $meses[$m];
    }

    public function graficoContaReceber(Request $request)
    {
        $mes = (int)date('m');
        $ano = date('Y');
        $data = [];
        for ($i = 0; $i < 4; $i++) {
            $receber = ContaReceber::where('empresa_id', $request->empresa_id)
            ->whereMonth('created_at', $mes)
            ->whereYear('created_at', $ano)
            ->sum('valor_integral');

            $pendente = ContaReceber::where('empresa_id', $request->empresa_id)
            ->where(function ($q) {
                $q->where('status', false);
            })
            ->whereMonth('created_at', $mes)
            ->whereYear('created_at', $ano)
            ->sum('valor_integral');

            $quitado = ContaReceber::where('empresa_id', $request->empresa_id)
            ->where(function ($q) {
                $q->where('status', true);
            })
            ->whereMonth('created_at', $mes)
            ->whereYear('created_at', $ano)
            ->sum('valor_integral');

            array_push($data, [
                'dia' => $this->getMes($mes - 1) . "/$ano",
                'valor' => $receber,
                'valorPendente' => $pendente,
                'valorQuitado' => $quitado
            ]);

            if ($mes == 1) {
                $mes = 12;
                $ano--;
            } else {
                $mes--;
            }
        }
        return response()->json($data, 200);
    }

    public function graficoContaPagar(Request $request)
    {
        $mes = (int)date('m');
        $ano = date('Y');
        $data = [];
        for ($i = 0; $i < 4; $i++) {
            $pagar = ContaPagar::where('empresa_id', $request->empresa_id)
            ->whereMonth('created_at', $mes)
            ->whereYear('created_at', $ano)
            ->sum('valor_integral');

            $pendentes = ContaPagar::where('empresa_id', $request->empresa_id)
            ->where(function ($q) {
                $q->where('status', false);
            })
            ->whereMonth('created_at', $mes)
            ->whereYear('created_at', $ano)
            ->sum('valor_integral');

            $quitadas = ContaPagar::where('empresa_id', $request->empresa_id)
            ->where(function ($q) {
                $q->where('status', true);
            })
            ->whereMonth('created_at', $mes)
            ->whereYear('created_at', $ano)
            ->sum('valor_integral');

            array_push($data, [
                'dia' => $this->getMes($mes - 1) . "/$ano",
                'valor' => $pagar,
                'valorPendente' => $pendentes,
                'valorQuitado' => $quitadas
            ]);

            if ($mes == 1) {
                $mes = 12;
                $ano--;
            } else {
                $mes--;
            }
        }
        return response()->json($data, 200);
    }

    public function graficoMesCte(Request $request)
    {
        $mes = (int)date('m');
        $ano = date('Y');
        $data = [];
        for ($i = 0; $i < 4; $i++) {
            $totalNfe = Cte::where('empresa_id', $request->empresa_id)
            ->where(function ($q) {
                $q->where('estado', 'aprovado')->orWhere('estado', 'cancelado');
            })
            ->whereMonth('created_at', $mes)
            ->whereYear('created_at', $ano)
            ->count('id');

            array_push($data, [
                'dia' => $this->getMes($mes - 1) . "/$ano",
                'valor' => $totalNfe
            ]);

            if ($mes == 1) {
                $mes = 12;
                $ano--;
            } else {
                $mes--;
            }
        }
        return response()->json($data, 200);
    }

    public function graficoMesMdfe(Request $request)
    {
        $mes = (int)date('m');
        $ano = date('Y');
        $data = [];
        for ($i = 0; $i < 4; $i++) {
            $totalNfe = Mdfe::where('empresa_id', $request->empresa_id)
            ->where(function ($q) {
                $q->where('estado_emissao', 'aprovado')->orWhere('estado_emissao', 'cancelado');
            })
            ->whereMonth('created_at', $mes)
            ->whereYear('created_at', $ano)
            ->count('id');

            array_push($data, [
                'dia' => $this->getMes($mes - 1) . "/$ano",
                'valor' => $totalNfe
            ]);

            if ($mes == 1) {
                $mes = 12;
                $ano--;
            } else {
                $mes--;
            }
        }
        return response()->json($data, 200);
    }

}
