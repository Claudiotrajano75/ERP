<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estoque;
use App\Models\Produto;
use App\Models\MovimentacaoProduto;
use App\Utils\EstoqueUtil;
use App\Models\ProdutoLocalizacao;
use App\Models\Localizacao;
use Illuminate\Support\Facades\DB;

class EstoqueController extends Controller
{

    protected $util;

    public function __construct(EstoqueUtil $util)
    {
        $this->util = $util;
        $this->middleware('permission:estoque_create', ['only' => ['create', 'store', 'ajuste']]);
        $this->middleware('permission:estoque_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:estoque_view', ['only' => ['show', 'index', 'movimentacoes']]);
        $this->middleware('permission:estoque_delete', ['only' => ['destroy']]);
    }

    public function index(Request $request){

        $data = Estoque::select('estoques.*')
        ->join('produtos', 'produtos.id', '=', 'estoques.produto_id')
        ->where('produtos.empresa_id', request()->empresa_id)
        ->when(!empty($request->produto), function ($q) use ($request) {
            return $q->where('produtos.nome', 'LIKE', "%$request->produto%");
        })
        ->orderBy('id', 'desc')
        // ->groupBy('estoques.produto_id')

        ->paginate(env("PAGINACAO"));

        // ─── KPI Stats ───
        $statsQuery = Estoque::join('produtos', 'produtos.id', '=', 'estoques.produto_id')
            ->where('produtos.empresa_id', request()->empresa_id);

        $stats = [
            'total_produtos' => (clone $statsQuery)->count(DB::raw('DISTINCT estoques.produto_id')),
            'total_itens' => (clone $statsQuery)->sum('estoques.quantidade'),
            'valor_estoque' => (clone $statsQuery)
                ->selectRaw('COALESCE(SUM(estoques.quantidade * produtos.valor_unitario), 0) as total')
                ->value('total'),
            'estoque_baixo' => (clone $statsQuery)
                ->where('produtos.gerenciar_estoque', 1)
                ->whereColumn('estoques.quantidade', '<', 'produtos.estoque_minimo')
                ->count(DB::raw('DISTINCT estoques.produto_id')),
        ];

        return view('estoque.index', compact('data', 'stats'));
    }

    public function movimentacoes(Request $request){

        $query = MovimentacaoProduto::query()
        ->join('produtos', 'produtos.id', '=', 'movimentacao_produtos.produto_id')
        ->where('produtos.empresa_id', $request->empresa_id)
        ->when(!empty($request->produto), function ($q) use ($request) {
            return $q->where('produtos.nome', 'LIKE', "%$request->produto%");
        })
        ->when(!empty($request->tipo), function ($q) use ($request) {
            return $q->where('movimentacao_produtos.tipo', $request->tipo);
        })
        ->when(!empty($request->tipo_transacao), function ($q) use ($request) {
            return $q->where('movimentacao_produtos.tipo_transacao', $request->tipo_transacao);
        })
        ->when(!empty($request->start_date), function ($q) use ($request) {
            return $q->whereDate('movimentacao_produtos.created_at', '>=', $request->start_date);
        })
        ->when(!empty($request->end_date), function ($q) use ($request) {
            return $q->whereDate('movimentacao_produtos.created_at', '<=', $request->end_date);
        })
        ->select('movimentacao_produtos.*');

        $stats = [
            'total' => (clone $query)->count(),
            'entradas' => (clone $query)->where('movimentacao_produtos.tipo', 'incremento')->sum('movimentacao_produtos.quantidade'),
            'saidas' => (clone $query)->where('movimentacao_produtos.tipo', 'reducao')->sum('movimentacao_produtos.quantidade'),
            'ajustes' => (clone $query)->where('movimentacao_produtos.tipo_transacao', 'alteracao_estoque')->count(),
        ];

        $data = (clone $query)
        ->with(['produto', 'user', 'produtoVariacao'])
        ->orderBy('movimentacao_produtos.id', 'desc')
        ->paginate(env("PAGINACAO"));

        return view('estoque.movimentacoes', compact('data', 'stats'));
    }

    public function create()
    {
        return view('estoque.create');
    }

    public function edit(Request $request, $id)
    {
        $local_id = $request->local_id;
        $item = Estoque::findOrFail($id);
        $locais = Estoque::where('produto_id', $item->produto_id)->get();

        $firstLocation = Localizacao::where('empresa_id', $item->produto->empresa_id)->first();
        
        return view('estoque.edit', compact('item', 'locais', 'firstLocation'));
    }

    public function destroy($id)
    {
        $item = Estoque::findOrFail($id);
        $descricaoLog = $item->produto->nome;

        try {
            // Estorna a movimentação antes de remover o registro, para que a
            // exclusão deixe rastro na trilha de auditoria. O saldo resultante
            // do local após a remoção é 0.
            if ((float)$item->quantidade != 0) {
                $this->util->movimentacaoProduto(
                    $item->produto_id,
                    $item->quantidade,
                    'reducao',
                    $item->id,
                    'alteracao_estoque',
                    \Auth::user()->id,
                    $item->produto_variacao_id,
                    $item->local_id,
                    0
                );
            }

            $item->delete();
            session()->flash("flash_success", "estoque removido com sucesso!");
            __createLog(request()->empresa_id, 'Estoque', 'excluir', $descricaoLog);
        } catch (\Exception $e) {
            __createLog(request()->empresa_id, 'Estoque', 'erro', $e->getMessage());
            session()->flash("flash_error", 'Algo deu errado: '. $e->getMessage());
        }
        return redirect()->route('estoque.index');
    }

    public function store(Request $request)
    {
        try {
            if(isset($request->local_id)){
                ProdutoLocalizacao::updateOrCreate([
                    'produto_id' => $request->produto_id, 
                    'localizacao_id' => $request->local_id
                ]);
            }

            $this->util->incrementaEstoque($request->produto_id, $request->quantidade, $request->produto_variacao_id, $request->local_id);

            $transacao = Estoque::where('produto_id', $request->produto_id)->first();
            $tipo = 'incremento';
            $codigo_transacao = $transacao->id;
            $tipo_transacao = 'alteracao_estoque';

            $this->util->movimentacaoProduto($request->produto_id, $request->quantidade, $tipo, $codigo_transacao, $tipo_transacao, \Auth::user()->id, $request->produto_variacao_id, $request->local_id);

            __createLog($request->empresa_id, 'Estoque', 'cadastrar', $transacao->produto->nome . " - quantidade " . $request->quantidade);
            session()->flash("flash_success", "Estoque adicionado com sucesso!");
        } catch (\Exception $e) {
            // echo $e->getLine();
            // die;
            __createLog($request->empresa_id, 'Estoque', 'erro', $e->getMessage());
            session()->flash("flash_error", "Algo deu errado: " . $e->getMessage());
        }
        return redirect()->route('estoque.index');
    }

    public function ajuste(Request $request)
    {
        try {
            $request->validate([
                'produto_id' => 'required',
                'tipo' => 'required|in:entrada,saida',
                'quantidade' => 'required',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            session()->flash('flash_error', 'Informe o produto, o tipo e a quantidade.');
            return redirect()->back();
        }

        $produto_id = $request->produto_id;
        $quantidade = (float)__convert_value_bd($request->quantidade);
        $local_id = $request->local_id ?: $this->util->localAtual();
        $observacao = $request->observacao ? trim($request->observacao) : null;

        if ($quantidade <= 0) {
            session()->flash('flash_error', 'A quantidade deve ser maior que zero.');
            return redirect()->back();
        }

        $produto = Produto::findOrFail($produto_id);
        __validaObjetoEmpresa($produto);

        try {
            if ($request->tipo == 'entrada') {
                $this->util->incrementaEstoque($produto_id, $quantidade, null, $local_id);
                $tipo = 'incremento';
            } else {
                $estoque = Estoque::where('produto_id', $produto_id)
                ->where('local_id', $local_id)
                ->first();
                $disponivel = $estoque ? (float)$estoque->quantidade : 0;

                if ($quantidade > $disponivel) {
                    session()->flash('flash_error', 'Estoque insuficiente. Disponível: ' . number_format($disponivel, 3, ',', '.'));
                    return redirect()->back();
                }

                $this->util->reduzEstoque($produto_id, $quantidade, null, $local_id);
                $tipo = 'reducao';
            }

            $this->util->movimentacaoProduto(
                $produto_id,
                $quantidade,
                $tipo,
                0,
                'alteracao_estoque',
                \Auth::user()->id,
                null,
                $local_id,
                null,
                $observacao
            );

            $acao = $request->tipo == 'entrada' ? 'Entrada' : 'Saída';
            __createLog($request->empresa_id, 'Estoque', 'cadastrar', $acao . " avulsa - " . $produto->nome . " - quantidade " . $quantidade);

            session()->flash('flash_success', 'Movimentação lançada com sucesso!');
        } catch (\Exception $e) {
            __createLog($request->empresa_id, 'Estoque', 'erro', $e->getMessage());
            session()->flash('flash_error', 'Algo deu errado: ' . $e->getMessage());
        }

        return redirect()->route('estoque.index');
    }

    public function update(Request $request, $id){

        $item = Estoque::findOrFail($id);

        try{
            if(isset($request->local_id)){

                for($i=0; $i<sizeof($request->local_id); $i++){

                    $item = Estoque::findOrFail($request->local_id[$i]);

                    $diferenca = 0;
                    $tipo = 'incremento';

                    if($item->quantidade > $request->quantidade[$i]){
                        $diferenca = $item->quantidade - $request->quantidade[$i];
                        $tipo = 'reducao';
                    }else{
                        $diferenca = $request->quantidade[$i] - $item->quantidade;
                    }
                    $item->quantidade = $request->quantidade[$i];
                    $item->save();

                    $codigo_transacao = $item->id;
                    $tipo_transacao = 'alteracao_estoque';

                    $this->util->movimentacaoProduto($item->produto_id, $diferenca, $tipo, $codigo_transacao, $tipo_transacao, \Auth::user()->id, null, $item->local_id);

                    if(isset($request->novo_estoque)){

                        $firstLocation = Localizacao::where('empresa_id', $item->produto->empresa_id)->first();
                        ProdutoLocalizacao::updateOrCreate([
                            'produto_id' => $item->produto_id, 
                            'localizacao_id' => $firstLocation->id
                        ]);
                    }

                }
            }else{

                $diferenca = 0;
                $tipo = 'incremento';

                if($item->quantidade > $request->quantidade){
                    $diferenca = $item->quantidade - $request->quantidade;
                    $tipo = 'reducao';
                }else{
                    $diferenca = $request->quantidade - $item->quantidade;
                }
                $item->quantidade = $request->quantidade;
                $item->save();

                $codigo_transacao = $item->id;
                $tipo_transacao = 'alteracao_estoque';

                $this->util->movimentacaoProduto($item->produto_id, $diferenca, $tipo, $codigo_transacao, $tipo_transacao, \Auth::user()->id, null, $item->local_id);
            }
            __createLog($request->empresa_id, 'Estoque', 'editar', $item->produto->nome . " - quantidade " . $request->quantidade);
            session()->flash("flash_success", "Estoque alterado com sucesso!");
        }catch (\Exception $e) {
            // echo $e->getLine();
            // die;
            __createLog($request->empresa_id, 'Estoque', 'erro', $e->getMessage());
            session()->flash("flash_error", "Algo deu errado: " . $e->getMessage());
        }
        return redirect()->route('estoque.index');
    }
}
