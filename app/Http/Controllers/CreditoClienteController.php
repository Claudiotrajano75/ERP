<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\CreditoCliente;
use Illuminate\Support\Facades\Auth;

class CreditoClienteController extends Controller
{
    public function index(Request $request)
    {
        $pesquisa = $request->get('pesquisa');
        $somente_com_saldo = $request->has('somente_com_saldo') ? $request->get('somente_com_saldo') : '1';
        $empresa_id = $request->empresa_id ?: (Auth::check() ? Auth::user()->empresa_id : null);

        // Query principal de clientes
        $data = Cliente::when($empresa_id, function ($q) use ($empresa_id) {
                return $q->where('empresa_id', $empresa_id);
            })
            ->when(!empty($pesquisa), function ($q) use ($pesquisa) {
                return $q->where(function ($sub) use ($pesquisa) {
                    $sub->where('razao_social', 'like', "%{$pesquisa}%")
                        ->orWhere('nome_fantasia', 'like', "%{$pesquisa}%")
                        ->orWhere('cpf_cnpj', 'like', "%{$pesquisa}%");
                });
            })
            ->when($somente_com_saldo === '1', function ($q) {
                return $q->where('valor_credito', '>', 0);
            })
            ->orderBy('valor_credito', 'desc')
            ->orderBy('razao_social', 'asc')
            ->paginate(env("PAGINACAO", 20));

        // KPIs
        $totalCreditoAberto = Cliente::when($empresa_id, function ($q) use ($empresa_id) {
                return $q->where('empresa_id', $empresa_id);
            })
            ->where('valor_credito', '>', 0)
            ->sum('valor_credito');

        $totalClientesComSaldo = Cliente::when($empresa_id, function ($q) use ($empresa_id) {
                return $q->where('empresa_id', $empresa_id);
            })
            ->where('valor_credito', '>', 0)
            ->count();

        $clienteIds = Cliente::when($empresa_id, function ($q) use ($empresa_id) {
                return $q->where('empresa_id', $empresa_id);
            })->pluck('id');

        $totalEntradasCredito = CreditoCliente::whereIn('cliente_id', $clienteIds)
            ->where('valor', '>', 0)
            ->sum('valor');

        $totalUtilizadoCredito = abs(CreditoCliente::whereIn('cliente_id', $clienteIds)
            ->where('valor', '<', 0)
            ->sum('valor'));

        return view('creditos_cliente.index', compact(
            'data',
            'totalCreditoAberto',
            'totalClientesComSaldo',
            'totalEntradasCredito',
            'totalUtilizadoCredito',
            'pesquisa',
            'somente_com_saldo'
        ));
    }

    /**
     * Retorna o extrato das movimentações de crédito do cliente para exibir na modal
     */
    public function extrato($id)
    {
        $cliente = Cliente::with('cidade')->findOrFail($id);
        $movimentacoes = CreditoCliente::where('cliente_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'cliente' => [
                'id' => $cliente->id,
                'razao_social' => $cliente->razao_social,
                'cpf_cnpj' => $cliente->cpf_cnpj,
                'telefone' => $cliente->telefone,
                'saldo_atual' => (float)$cliente->valor_credito,
                'saldo_formatado' => __moeda($cliente->valor_credito)
            ],
            'movimentacoes' => $movimentacoes->map(function ($item) {
                $isEntrada = $item->valor > 0;
                return [
                    'id' => $item->id,
                    'tipo' => $isEntrada ? 'ENTRADA' : 'SAÍDA',
                    'tipo_badge' => $isEntrada ? 'success' : 'danger',
                    'descricao' => $isEntrada ? 'Crédito Gerado (Troca / Devolução)' : 'Utilização em Compra no PDV',
                    'valor' => abs((float)$item->valor),
                    'valor_formatado' => ($isEntrada ? '+ R$ ' : '- R$ ') . __moeda(abs($item->valor)),
                    'data_hora' => date('d/m/Y H:i', strtotime($item->created_at)),
                ];
            })
        ]);
    }

    /**
     * Ajuste manual de saldo de crédito (adicionar ou subtrair)
     */
    public function ajustarSaldo(Request $request, $id)
    {
        $request->validate([
            'tipo_ajuste' => 'required|in:adicionar,remover',
            'valor' => 'required',
            'motivo' => 'required|string|min:3',
        ]);

        $cliente = Cliente::findOrFail($id);
        $valor = __convert_value_bd($request->valor);

        if ($valor <= 0) {
            session()->flash('flash_error', 'Informe um valor maior que zero!');
            return redirect()->back();
        }

        if ($request->tipo_ajuste == 'remover') {
            if ($cliente->valor_credito < $valor) {
                session()->flash('flash_error', 'O valor a remover não pode ser maior que o saldo atual do cliente!');
                return redirect()->back();
            }
            $cliente->valor_credito -= $valor;
            $valorMovimentacao = -$valor;
            $tipoLog = 'débito_manual';
        } else {
            $cliente->valor_credito += $valor;
            $valorMovimentacao = $valor;
            $tipoLog = 'crédito_manual';
        }

        $cliente->save();

        CreditoCliente::create([
            'cliente_id' => $cliente->id,
            'valor' => $valorMovimentacao
        ]);

        if (function_exists('__createLog')) {
            __createLog($request->empresa_id, 'Crédito Cliente', $tipoLog, 
                "Cliente: {$cliente->razao_social} | Valor: R$ " . __moeda($valor) . " | Motivo: {$request->motivo}");
        }

        session()->flash('flash_success', 'Saldo de crédito ajustado com sucesso!');
        return redirect()->back();
    }
}
