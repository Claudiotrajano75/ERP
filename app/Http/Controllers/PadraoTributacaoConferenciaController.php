<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Produto;
use App\Models\PadraoTributacaoProduto;
use App\Models\CategoriaProduto;
use App\Models\Empresa;
use Dompdf\Dompdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ConferenciaPadraoTributacaoExport;

class PadraoTributacaoConferenciaController extends Controller
{
    /**
     * Tela principal de conferência fiscal dos produtos por padrão de tributação.
     */
    public function index(Request $request)
    {
        $empresaId = $this->getEmpresaId();
        if (!$empresaId) {
            session()->flash('flash_error', 'Empresa não identificada.');
            return redirect()->route('home');
        }

        $empresa = Empresa::find($empresaId);

        // Carrega dados e processa divergências
        $dadosRelatorio = $this->carregarDadosRelatorio($request, $empresaId);

        $gruposPadroes   = $dadosRelatorio['grupos'];
        $stats           = $dadosRelatorio['stats'];
        $padroesFiltro   = $dadosRelatorio['padroes_filtro'];
        $categorias      = $dadosRelatorio['categorias'];
        $totalExibidos   = $dadosRelatorio['total_exibidos'];

        return view('padrao_tributacao.conferencia', compact(
            'gruposPadroes',
            'stats',
            'padroesFiltro',
            'categorias',
            'totalExibidos',
            'empresa'
        ));
    }

    /**
     * Gera relatório em PDF (A4 Paisagem).
     */
    public function pdf(Request $request)
    {
        $empresaId = $this->getEmpresaId();
        if (!$empresaId) {
            session()->flash('flash_error', 'Empresa não identificada.');
            return redirect()->back();
        }

        $empresa = Empresa::find($empresaId);
        $dadosRelatorio = $this->carregarDadosRelatorio($request, $empresaId);

        $gruposPadroes = $dadosRelatorio['grupos'];
        $stats         = $dadosRelatorio['stats'];
        $totalExibidos = $dadosRelatorio['total_exibidos'];

        $html = view('padrao_tributacao.conferencia_pdf', compact(
            'gruposPadroes',
            'stats',
            'totalExibidos',
            'empresa'
        ))->render();

        $domPdf = new Dompdf(['enable_remote' => true]);
        $domPdf->loadHtml($html);
        $domPdf->setPaper('A4', 'landscape');
        $domPdf->render();

        $nomeArquivo = 'Conferencia_Fiscal_Produtos_' . date('Ymd_His') . '.pdf';
        return $domPdf->stream($nomeArquivo, ['Attachment' => false]);
    }

    /**
     * Exporta dados para planilha Excel (.xlsx).
     */
    public function excel(Request $request)
    {
        $empresaId = $this->getEmpresaId();
        if (!$empresaId) {
            session()->flash('flash_error', 'Empresa não identificada.');
            return redirect()->back();
        }

        $empresa = Empresa::find($empresaId);
        $dadosRelatorio = $this->carregarDadosRelatorio($request, $empresaId);

        $nomeArquivo = 'conferencia_fiscal_produtos_' . date('Ymd_His') . '.xlsx';
        return Excel::download(new ConferenciaPadraoTributacaoExport($dadosRelatorio, $empresa), $nomeArquivo);
    }

    /**
     * Carrega e processa a listagem e divergências fiscais de forma otimizada.
     */
    private function carregarDadosRelatorio(Request $request, $empresaId)
    {
        // 1. Busca todos os padrões da empresa para referência rápida
        $padroesDb = PadraoTributacaoProduto::where('empresa_id', $empresaId)
            ->orderBy('descricao', 'ASC')
            ->get()
            ->keyBy('id');

        // 2. Query otimizada de produtos com joins
        $query = DB::table('produtos as p')
            ->leftJoin('padrao_tributacao_produtos as pt', function ($join) use ($empresaId) {
                $join->on('p.padrao_id', '=', 'pt.id')
                     ->where('pt.empresa_id', '=', $empresaId);
            })
            ->leftJoin('categoria_produtos as cat', 'p.categoria_id', '=', 'cat.id')
            ->leftJoin('marcas as m', 'p.marca_id', '=', 'm.id')
            ->where('p.empresa_id', $empresaId)
            ->select([
                'p.id as produto_id',
                'p.nome as produto_nome',
                'p.codigo_barras',
                'p.referencia',
                'p.unidade',
                'p.status as produto_status',
                'p.padrao_id',
                'cat.nome as categoria_nome',
                'm.nome as marca_nome',

                // Dados fiscais do produto
                'p.ncm as prod_ncm',
                'p.cest as prod_cest',
                'p.cfop_estadual as prod_cfop_estadual',
                'p.cfop_outro_estado as prod_cfop_outro_estado',
                'p.cst_csosn as prod_cst_csosn',
                'p.cst_pis as prod_cst_pis',
                'p.cst_cofins as prod_cst_cofins',
                'p.cst_ipi as prod_cst_ipi',
                'p.cEnq as prod_cEnq',
                'p.perc_icms as prod_perc_icms',
                'p.perc_pis as prod_perc_pis',
                'p.perc_cofins as prod_perc_cofins',
                'p.perc_ipi as prod_perc_ipi',
                'p.perc_ibs as prod_perc_ibs',
                'p.perc_cbs as prod_perc_cbs',
                'p.perc_red_bc as prod_perc_red_bc',

                // Dados fiscais do padrão
                'pt.id as padrao_id_ref',
                'pt.descricao as padrao_descricao',
                'pt.ncm as padrao_ncm',
                'pt.cest as padrao_cest',
                'pt.cfop_estadual as padrao_cfop_estadual',
                'pt.cfop_outro_estado as padrao_cfop_outro_estado',
                'pt.cst_csosn as padrao_cst_csosn',
                'pt.cst_pis as padrao_cst_pis',
                'pt.cst_cofins as padrao_cst_cofins',
                'pt.cst_ipi as padrao_cst_ipi',
                'pt.cEnq as padrao_cEnq',
                'pt.perc_icms as padrao_perc_icms',
                'pt.perc_pis as padrao_perc_pis',
                'pt.perc_cofins as padrao_perc_cofins',
                'pt.perc_ipi as padrao_perc_ipi',
                'pt.perc_ibs as padrao_perc_ibs',
                'pt.perc_cbs as padrao_perc_cbs',
                'pt.perc_red_bc as padrao_perc_red_bc',
            ]);

        // ── Filtros SQL ──────────────────────────────────────────────────────────
        // Busca textual
        if ($request->filled('busca')) {
            $termo = trim($request->busca);
            $query->where(function ($q) use ($termo) {
                $q->where('p.nome', 'LIKE', "%{$termo}%")
                  ->orWhere('p.codigo_barras', 'LIKE', "%{$termo}%")
                  ->orWhere('p.referencia', 'LIKE', "%{$termo}%")
                  ->orWhere('p.id', '=', $termo);
            });
        }

        // Filtro por Categoria
        if ($request->filled('categoria_id')) {
            $query->where('p.categoria_id', $request->categoria_id);
        }

        // Filtro por Situação (Ativo / Inativo)
        if ($request->filled('status') && $request->status !== 'todos') {
            $query->where('p.status', (int) $request->status);
        }

        // Filtro por Padrão de Tributação
        if ($request->filled('padrao_id') && $request->padrao_id !== 'todos') {
            if ($request->padrao_id === 'sem_padrao') {
                $query->where(function ($q) {
                    $q->whereNull('p.padrao_id')->orWhere('p.padrao_id', 0);
                });
            } else {
                $query->where('p.padrao_id', $request->padrao_id);
            }
        }

        // Ordenação: primeiro padrões com nome, depois sem padrão; dentro de cada padrão, por nome do produto
        $produtosRaw = $query->orderByRaw('CASE WHEN pt.descricao IS NULL THEN 1 ELSE 0 END ASC')
            ->orderBy('pt.descricao', 'ASC')
            ->orderBy('p.nome', 'ASC')
            ->get();

        // ── Processamento de Divergências e Estatísticas ───────────────────────────
        $filtroDivergencia = $request->input('filtro_divergencia', 'todos');

        $totalGeral      = 0;
        $totalComPadrao  = 0;
        $totalSemPadrao  = 0;
        $totalConformes  = 0;
        $totalDivergentes = 0;

        $produtosProcessados = [];

        foreach ($produtosRaw as $prod) {
            $padrao = ($prod->padrao_id && isset($padroesDb[$prod->padrao_id]))
                ? $padroesDb[$prod->padrao_id]
                : null;

            $analise = self::avaliarDivergencias($prod, $padrao);

            $prod->analise_status       = $analise['status']; // 'conforme', 'divergente', 'sem_padrao'
            $prod->analise_divergencias = $analise['divergencias'];
            $prod->analise_campos       = $analise['campos_divergentes'];
            $prod->total_divergencias   = $analise['total_divergencias'];

            // Contabilização global
            $totalGeral++;
            if ($prod->padrao_id && $padrao) {
                $totalComPadrao++;
                if ($analise['status'] === 'conforme') {
                    $totalConformes++;
                } else {
                    $totalDivergentes++;
                }
            } else {
                $totalSemPadrao++;
            }

            // Aplicação do filtro de divergência
            if ($filtroDivergencia === 'divergentes' && $analise['status'] !== 'divergente') {
                continue;
            }
            if ($filtroDivergencia === 'conformes' && $analise['status'] !== 'conforme') {
                continue;
            }
            if ($filtroDivergencia === 'sem_padrao' && $analise['status'] !== 'sem_padrao') {
                continue;
            }

            $produtosProcessados[] = $prod;
        }

        // ── Agrupamento por Padrão de Tributação ──────────────────────────────────
        $grupos = [];

        // Agrupa os produtos processados por padrao_id
        $produtosColecao = collect($produtosProcessados);
        $agrupados = $produtosColecao->groupBy(function ($item) use ($padroesDb) {
            return ($item->padrao_id && isset($padroesDb[$item->padrao_id]))
                ? $item->padrao_id
                : 0; // 0 = Sem Padrão
        });

        // 1. Grupos de padrões cadastrados (na ordem do banco)
        foreach ($padroesDb as $pId => $padraoObj) {
            $itensDoGrupo = $agrupados->get($pId, collect());

            // Se filtrou por um padrão específico ou se tem produtos neste padrão
            if ($request->padrao_id && $request->padrao_id != 'todos' && $request->padrao_id != $pId) {
                continue;
            }

            // Mesmo que o filtro de busca tenha reduzido a 0 itens, só mostra se tiver itens ou se não filtrou por busca restritiva
            if ($itensDoGrupo->isNotEmpty() || (!$request->filled('busca') && !$request->filled('filtro_divergencia'))) {
                $conformesGrupo  = $itensDoGrupo->where('analise_status', 'conforme')->count();
                $divergentesGrupo = $itensDoGrupo->where('analise_status', 'divergente')->count();

                $grupos[] = [
                    'padrao_id'        => $pId,
                    'descricao'        => $padraoObj->descricao,
                    'padrao'           => $padraoObj,
                    'produtos'         => $itensDoGrupo,
                    'total_produtos'   => $itensDoGrupo->count(),
                    'total_conformes'  => $conformesGrupo,
                    'total_divergentes'=> $divergentesGrupo,
                    'is_sem_padrao'    => false,
                ];
            }
        }

        // 2. Grupo "PRODUTOS SEM PADRÃO DE TRIBUTAÇÃO VINCULADO"
        $itensSemPadrao = $agrupados->get(0, collect());
        if ($itensSemPadrao->isNotEmpty() && (!$request->filled('padrao_id') || $request->padrao_id === 'todos' || $request->padrao_id === 'sem_padrao')) {
            $grupos[] = [
                'padrao_id'        => 0,
                'descricao'        => 'PRODUTOS SEM PADRÃO DE TRIBUTAÇÃO VINCULADO',
                'padrao'           => null,
                'produtos'         => $itensSemPadrao,
                'total_produtos'   => $itensSemPadrao->count(),
                'total_conformes'  => 0,
                'total_divergentes'=> $itensSemPadrao->count(),
                'is_sem_padrao'    => true,
            ];
        }

        // Listas auxiliares para os selects de filtros
        $padroesFiltro = $padroesDb->values();
        $categorias    = CategoriaProduto::where('empresa_id', $empresaId)->orderBy('nome', 'ASC')->get();

        return [
            'grupos'         => $grupos,
            'stats'          => [
                'total'        => $totalGeral,
                'com_padrao'   => $totalComPadrao,
                'sem_padrao'   => $totalSemPadrao,
                'conformes'    => $totalConformes,
                'divergentes'  => $totalDivergentes,
            ],
            'padroes_filtro' => $padroesFiltro,
            'categorias'     => $categorias,
            'total_exibidos' => count($produtosProcessados),
        ];
    }

    /**
     * Avalia os 15 campos fiscais entre o produto e o padrão de tributação vinculado.
     */
    public static function avaliarDivergencias($prod, $padrao = null): array
    {
        if (!$padrao) {
            return [
                'status'              => 'sem_padrao',
                'divergencias'        => ['Produto sem Padrão de Tributação vinculado'],
                'campos_divergentes'  => ['padrao_id' => true],
                'total_divergencias'  => 1
            ];
        }

        $divergencias = [];
        $campos       = [];

        // 1. NCM (compara apenas dígitos para ignorar pontuação)
        $prodNcm   = preg_replace('/[^0-9]/', '', (string)$prod->prod_ncm);
        $padraoNcm = preg_replace('/[^0-9]/', '', (string)$padrao->ncm);
        if ($prodNcm !== $padraoNcm) {
            $divergencias[] = "NCM: " . ($prod->prod_ncm ?: 'Vazio') . " (Padrão: {$padrao->ncm})";
            $campos['ncm'] = true;
        }

        // 2. CEST
        $prodCest   = preg_replace('/[^0-9]/', '', (string)$prod->prod_cest);
        $padraoCest = preg_replace('/[^0-9]/', '', (string)$padrao->cest);
        if (!empty($padraoCest) && $prodCest !== $padraoCest) {
            $divergencias[] = "CEST: " . ($prod->prod_cest ?: 'Vazio') . " (Padrão: {$padrao->cest})";
            $campos['cest'] = true;
        }

        // 3. CFOP Estadual
        if ((string)$prod->prod_cfop_estadual !== (string)$padrao->cfop_estadual) {
            $divergencias[] = "CFOP Estadual: " . ($prod->prod_cfop_estadual ?: 'Vazio') . " (Padrão: {$padrao->cfop_estadual})";
            $campos['cfop_estadual'] = true;
        }

        // 4. CFOP Outro Estado (Interestadual)
        if ((string)$prod->prod_cfop_outro_estado !== (string)$padrao->cfop_outro_estado) {
            $divergencias[] = "CFOP Interestadual: " . ($prod->prod_cfop_outro_estado ?: 'Vazio') . " (Padrão: {$padrao->cfop_outro_estado})";
            $campos['cfop_outro_estado'] = true;
        }

        // 5. CST / CSOSN
        if ((string)$prod->prod_cst_csosn !== (string)$padrao->cst_csosn) {
            $divergencias[] = "CST/CSOSN: " . ($prod->prod_cst_csosn ?: 'Vazio') . " (Padrão: {$padrao->cst_csosn})";
            $campos['cst_csosn'] = true;
        }

        // 6. CST PIS
        if ((string)$prod->prod_cst_pis !== (string)$padrao->cst_pis) {
            $divergencias[] = "CST PIS: " . ($prod->prod_cst_pis ?: 'Vazio') . " (Padrão: {$padrao->cst_pis})";
            $campos['cst_pis'] = true;
        }

        // 7. CST COFINS
        if ((string)$prod->prod_cst_cofins !== (string)$padrao->cst_cofins) {
            $divergencias[] = "CST COFINS: " . ($prod->prod_cst_cofins ?: 'Vazio') . " (Padrão: {$padrao->cst_cofins})";
            $campos['cst_cofins'] = true;
        }

        // 8. CST IPI
        if (!empty($padrao->cst_ipi) && (string)$prod->prod_cst_ipi !== (string)$padrao->cst_ipi) {
            $divergencias[] = "CST IPI: " . ($prod->prod_cst_ipi ?: 'Vazio') . " (Padrão: {$padrao->cst_ipi})";
            $campos['cst_ipi'] = true;
        }

        // 9. cEnq (Enquadramento IPI)
        if (!empty($padrao->cEnq) && (string)$prod->prod_cEnq !== (string)$padrao->cEnq) {
            $divergencias[] = "cEnq IPI: " . ($prod->prod_cEnq ?: 'Vazio') . " (Padrão: {$padrao->cEnq})";
            $campos['cEnq'] = true;
        }

        // 10. % ICMS
        if (round((float)$prod->prod_perc_icms, 2) !== round((float)$padrao->perc_icms, 2)) {
            $divergencias[] = "% ICMS: " . number_format((float)$prod->prod_perc_icms, 2, ',', '.') . "% (Padrão: " . number_format((float)$padrao->perc_icms, 2, ',', '.') . "%)";
            $campos['perc_icms'] = true;
        }

        // 11. % PIS
        if (round((float)$prod->prod_perc_pis, 2) !== round((float)$padrao->perc_pis, 2)) {
            $divergencias[] = "% PIS: " . number_format((float)$prod->prod_perc_pis, 2, ',', '.') . "% (Padrão: " . number_format((float)$padrao->perc_pis, 2, ',', '.') . "%)";
            $campos['perc_pis'] = true;
        }

        // 12. % COFINS
        if (round((float)$prod->prod_perc_cofins, 2) !== round((float)$padrao->perc_cofins, 2)) {
            $divergencias[] = "% COFINS: " . number_format((float)$prod->prod_perc_cofins, 2, ',', '.') . "% (Padrão: " . number_format((float)$padrao->perc_cofins, 2, ',', '.') . "%)";
            $campos['perc_cofins'] = true;
        }

        // 13. % IPI
        if (round((float)$prod->prod_perc_ipi, 2) !== round((float)$padrao->perc_ipi, 2)) {
            $divergencias[] = "% IPI: " . number_format((float)$prod->prod_perc_ipi, 2, ',', '.') . "% (Padrão: " . number_format((float)$padrao->perc_ipi, 2, ',', '.') . "%)";
            $campos['perc_ipi'] = true;
        }

        // 14. % Redução BC
        if (round((float)$prod->prod_perc_red_bc, 2) !== round((float)$padrao->perc_red_bc, 2)) {
            $divergencias[] = "% Red. BC: " . number_format((float)$prod->prod_perc_red_bc, 2, ',', '.') . "% (Padrão: " . number_format((float)$padrao->perc_red_bc, 2, ',', '.') . "%)";
            $campos['perc_red_bc'] = true;
        }

        // 15. % IBS e % CBS
        if (isset($prod->prod_perc_ibs) && isset($padrao->perc_ibs) && round((float)$prod->prod_perc_ibs, 2) !== round((float)$padrao->perc_ibs, 2)) {
            $divergencias[] = "% IBS: " . number_format((float)$prod->prod_perc_ibs, 2, ',', '.') . "% (Padrão: " . number_format((float)$padrao->perc_ibs, 2, ',', '.') . "%)";
            $campos['perc_ibs'] = true;
        }
        if (isset($prod->prod_perc_cbs) && isset($padrao->perc_cbs) && round((float)$prod->prod_perc_cbs, 2) !== round((float)$padrao->perc_cbs, 2)) {
            $divergencias[] = "% CBS: " . number_format((float)$prod->prod_perc_cbs, 2, ',', '.') . "% (Padrão: " . number_format((float)$padrao->perc_cbs, 2, ',', '.') . "%)";
            $campos['perc_cbs'] = true;
        }

        $isDivergente = count($divergencias) > 0;

        return [
            'status'              => $isDivergente ? 'divergente' : 'conforme',
            'divergencias'        => $divergencias,
            'campos_divergentes'  => $campos,
            'total_divergencias'  => count($divergencias),
        ];
    }

    /**
     * Recupera o empresa_id da sessão ou do usuário autenticado.
     */
    private function getEmpresaId()
    {
        $empresaId = request()->empresa_id;
        if (!$empresaId && Auth::check() && Auth::user()->empresa) {
            $empresaId = Auth::user()->empresa->empresa_id;
        }
        return $empresaId;
    }
}
