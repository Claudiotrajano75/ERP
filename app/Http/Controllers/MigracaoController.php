<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\ProdutoLocalizacao;
use App\Utils\EstoqueUtil;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MigracaoController extends Controller
{
    protected $utilEstoque;

    public function __construct(EstoqueUtil $utilEstoque)
    {
        $this->utilEstoque = $utilEstoque;
    }

    public function index()
    {
        return view('migracao.index');
    }

    public function resetProducts()
    {
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Produto::truncate();
            ProdutoLocalizacao::truncate();
            // Também limpar tabela de correção de NCM se existir (opcional)
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return "Tabela de Produtos Zerada com Sucesso!";
        } catch (\Exception $e) {
            return "Erro: " . $e->getMessage();
        }
    }

    public function fixEncoding()
    {
        ini_set('max_execution_time', 0);
        $produtos = Produto::all();
        $count = 0;

        foreach ($produtos as $p) {
            $novoNome = str_replace(["\0", "\x00"], "", $p->nome);
            $novoCod = str_replace(["\0", "\x00"], "", $p->codigo_barras);

            if ($novoNome !== $p->nome || $novoCod !== $p->codigo_barras) {
                $p->nome = $novoNome;
                $p->codigo_barras = $novoCod;
                $p->save();
                $count++;
            }
        }

        return "Corrigidos encoding de $count produtos!";
    }

    public function store(Request $request)
    {
        if ($request->hasFile('file')) {
            ini_set('max_execution_time', 0);
            ini_set('memory_limit', -1);

            $file = $request->file('file');
            $handle = fopen($file, "r");
            
            // Detectar delimitador (Simples)
            $preview = fgets($handle);
            $delimiter = (strpos($preview, ';') !== false) ? ';' : ',';
            rewind($handle);

            $header = fgetcsv($handle, 0, $delimiter); // 0 = sem limite de comprimento

            // Mapeamento simples baseada na query SQL fornecida
            // 0: id_antigo, 1: nome, 2: codigo_barras, 3: referencia, 4: valor_venda
            // 5: valor_compra, 6: ncm, 7: cest, 8: unidade, 9: inativo

            $cont = 0;
            $erros = [];

            try {
                DB::beginTransaction();

                while ($row = fgetcsv($handle, 0, $delimiter)) {
                    // Pular linhas vazias
                    if (count($row) < 2) continue;

                    // Dados do CSV (Ajuste conforme a ordem da Query SQL que passamos)
                    // SELECT ProdutoId, Produto, CodBarras, Referencia, PrcVenda, PrcCompra, NCM, CEST, Unidade, Descontinuado
                    
                    $nome = $row[1] ?? 'PRODUTO SEM NOME';
                    // FIX UTF-16 NULL BYTES
                    $nome = str_replace(["\0", "\x00"], "", $nome);

                    $codigo_barras = $row[2] ?? '';
                    $codigo_barras = str_replace(["\0", "\x00"], "", $codigo_barras);

                    $referencia = $row[3] ?? '';
                    $valor_venda = $this->parseMoney($row[4] ?? 0);
                    $valor_compra = $this->parseMoney($row[5] ?? 0);
                    $ncm = preg_replace('/[^0-9]/', '', $row[6] ?? '');
                    $cest = preg_replace('/[^0-9]/', '', $row[7] ?? '');
                    $unidade = trim($row[8] ?? '');
                    $inativo = $row[9] ?? 'N';

                    // Tratamento de Defaults
                    if (empty($ncm) || strlen($ncm) < 8) {
                        $ncm = '00000000'; 
                    }

                    // Se unidade vier numérica, vazia ou muito curta, força UN
                    // Ex: "2", "  2  ", "" -> vira "UN"
                    if (empty($unidade) || is_numeric($unidade) || strlen($unidade) > 4) {
                        $unidade = 'UN';
                    }

                    // Ignorar descontinuados se desejado, ou importar como inativo
                    // Aqui vamos importar tudo, o usuário filtra depois se quiser

                    // Verifica duplicidade por nome ou código de barras
                    $exists = Produto::where('empresa_id', $request->empresa_id)
                        ->where(function($q) use ($nome, $codigo_barras) {
                            $q->where('nome', $nome);
                            if (!empty($codigo_barras)) {
                                $q->orWhere('codigo_barras', $codigo_barras);
                            }
                        })->first();

                    if ($exists) {
                        continue; // Já existe, pula
                    }

                    $data = [
                        'empresa_id' => $request->empresa_id,
                        'nome' => mb_strtoupper($nome),
                        'codigo_barras' => $codigo_barras ?: 'SEM GTIN',
                        'referencia' => $referencia,
                        'ncm' => $ncm,
                        'cest' => $cest,
                        'unidade' => $unidade,
                        'conversao_unitaria' => 1,
                        
                        // Defaults Fiscais para Simples Nacional (Mais comum)
                        'origem' => 0, // Nacional
                        'perc_icms' => 0,
                        'perc_pis' => 0,
                        'perc_cofins' => 0,
                        'perc_ipi' => 0,
                        
                        'cst_csosn' => '102', // Tributado pelo Simples Nacional sem permissão de crédito
                        'cst_pis' => '99',
                        'cst_cofins' => '99',
                        'cst_ipi' => '99',
                        
                        'cfop_estadual' => '5102', // Venda mercadoria adquirida de terceiros
                        'cfop_outro_estado' => '6102',
                        'cfop_entrada_estadual' => '1102', 
                        'cfop_entrada_outro_estado' => '2102',
                        
                        'valor_unitario' => $valor_venda > 0 ? $valor_venda : 0.01,
                        'valor_compra' => $valor_compra,
                        'gerenciar_estoque' => 0, // Vamos ativar se vier estoque depois
                        'inativo' => ($inativo == 'S' || $inativo == '1') ? 1 : 0
                    ];

                    $produto = Produto::create($data);
                    
                    // Cria localização padrão
                    ProdutoLocalizacao::create([
                        'produto_id' => $produto->id,
                        'localizacao_id' => $request->local_id // Local padrão selecionado na view
                    ]);

                    $cont++;
                }

                DB::commit();
                session()->flash('flash_success', "Importação concluída! $cont produtos importados.");
                
            } catch (\Exception $e) {
                DB::rollBack();
                session()->flash('flash_error', 'Erro na importação: ' . $e->getMessage());
            }

            return redirect()->back();
        }

        return redirect()->back();
    }

    private function parseMoney($value)
    {
        // Remove R$, espaços, e converte vírgula pra ponto
        if(is_numeric($value)) return $value;
        $value = str_replace(['R$', ' ', '.'], '', $value);
        $value = str_replace(',', '.', $value);
        return (float) $value;
    }
}
