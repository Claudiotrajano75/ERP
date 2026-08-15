<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\PedidoEcommerce;
use App\Models\Cidade;
use App\Models\Transportadora;
use App\Models\NaturezaOperacao;
use App\Models\EcommerceConfig;
use App\Models\Empresa;
use App\Models\Nfe;

class PedidoEcommerceController extends Controller
{
    public function index(Request $request){

        $pagamentosAlterados = $this->pagamentosCheck($request);
        $estado = $request->estado;
        $cliente_id = $request->cliente_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $codigo = $request->codigo;
        $cliente = null;

        $query = PedidoEcommerce::where('empresa_id', $request->empresa_id)
            ->with(['cliente', 'itens'])
            ->when(!empty($cliente_id), function ($q) use ($cliente_id) {
                return $q->where('cliente_id', $cliente_id);
            })
            ->when(!empty($estado), function ($q) use ($estado) {
                return $q->where('estado', $estado);
            })
            ->when(!empty($codigo), function ($q) use ($codigo) {
                return $q->where(function($sub) use ($codigo) {
                    $sub->where('hash_pedido', 'LIKE', "%$codigo%")
                        ->orWhere('id', $codigo);
                });
            })
            ->when(!empty($start_date), function ($q) use ($start_date) {
                return $q->whereDate('created_at', '>=', $start_date);
            })
            ->when(!empty($end_date), function ($q) use ($end_date) {
                return $q->whereDate('created_at', '<=', $end_date);
            });

        $data = (clone $query)->orderBy('created_at', 'desc')->paginate(env("PAGINACAO"));

        $statsQuery = PedidoEcommerce::where('empresa_id', $request->empresa_id);

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'aprovados' => (clone $statsQuery)->whereIn('estado', ['aprovado', 'finalizado'])->count(),
            'pendentes' => (clone $statsQuery)->whereIn('estado', ['novo', 'preparando', 'em_trasporte'])->count(),
            'faturamento' => (clone $statsQuery)->whereNotIn('estado', ['recusado', 'cancelado'])->sum('valor_total'),
        ];

        if($cliente_id){
            $cliente = Cliente::find($cliente_id);
        }

        return view('pedido_ecommerce.index', compact('data', 'cliente', 'pagamentosAlterados', 'stats'));
    }

    private function pagamentosCheck($request){
        $data = PedidoEcommerce::
        where('empresa_id', $request->empresa_id)
        ->whereDate('created_at', '>=', date('Y-m-d', strtotime('-7 days')))
        ->get();


        $config = EcommerceConfig::where('empresa_id', $request->empresa_id)
        ->first();

        $alterados = [];
        if($config == null){
            return $alterados;
        }
        try{
            \MercadoPago\SDK::setAccessToken($config->mercadopago_access_token);
            foreach($data as $p){
                if($p->transacao_id){
                    $payStatus = \MercadoPago\Payment::find_by_id($p->transacao_id);
                    if($payStatus){
                    // $payStatus->status = 'apprsoved';
                        if($payStatus->status != $p->status_pagamento){
                            array_push($alterados, [
                                'hash_pedido' => $p->hash_pedido,
                                'status' => $payStatus->status
                            ]);

                            $p->status_pagamento = $payStatus->status;
                            $p->save();
                        }
                    }
                }
            }
        }catch(\Exception $e){

        }
        return $alterados;
    }

    public function show($id)
    {
        $item = PedidoEcommerce::findOrFail($id);
        $item->pedido_lido = 1;
        $item->save();
        return view('pedido_ecommerce.show', compact('item'));
    }

    public function alterarEstado($id)
    {
        $item = PedidoEcommerce::findOrFail($id);
        return view('pedido_ecommerce.alterar_estado', compact('item'));
    }

    public function update(Request $request, $id)
    {

        $item = PedidoEcommerce::findOrFail($id);
        $item->fill($request->all())->save();
        session()->flash("flash_success", "Pedido atualizado!");
        return redirect()->route('pedidos-ecommerce.show', $item->id);
    }

    public function destroy($id)
    {
        $item = PedidoEcommerce::findOrFail($id);
        try {
            $item->itens()->delete();
            $item->delete();

            session()->flash("flash_success", "Pedido removido!");
        } catch (\Exception $e) {
            session()->flash("flash_error", "Algo deu Errado: " . $e->getMessage());
        }
        return redirect()->back();
    }

    public function gerarNfe($id)
    {
        $item = PedidoEcommerce::findOrFail($id);

        $cliente = $item->cliente;
        if($cliente->rua == null){

            if($item->rua_entrega != null){
                $cliente->rua = $item->rua_entrega;
                $cliente->numero = $item->numero_entrega;
                $cliente->bairro = $item->bairro_entrega;
                $cliente->cep = $item->cep_entrega;

                $cliente->save();

                $item = PedidoEcommerce::findOrFail($id);
            }
        }

        $cidades = Cidade::all();
        $transportadoras = Transportadora::where('empresa_id', request()->empresa_id)->get();

        $naturezas = NaturezaOperacao::where('empresa_id', request()->empresa_id)->get();
        if (sizeof($naturezas) == 0) {
            session()->flash("flash_warning", "Primeiro cadastre um natureza de operação!");
            return redirect()->route('natureza-operacao.create');
        } 
        // $produtos = Produto::where('empresa_id', request()->empresa_id)->get();
        $empresa = Empresa::findOrFail(request()->empresa_id);

        $caixa = __isCaixaAberto();
        $empresa = __objetoParaEmissao($empresa, $caixa->local_id);
        
        $numeroNfe = Nfe::lastNumero($empresa);

        $isPedidoEcommerce = 1;
        return view('nfe.create', compact('item', 'cidades', 'transportadoras', 'naturezas', 'isPedidoEcommerce', 'numeroNfe', 
            'caixa'));
    }

}
