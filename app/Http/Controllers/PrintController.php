<?php

namespace App\Http\Controllers;

use App\Models\ConfigGeral;
use App\Models\Nfce;
use App\Models\Empresa;
use App\Models\Troca;
use App\Models\SangriaCaixa;
use App\Models\SuprimentoCaixa;
use App\Services\PrintService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrintController extends Controller
{
    protected $printService;

    public function __construct(PrintService $printService)
    {
        $this->printService = $printService;
    }

    /**
     * Testa a conexao com a impressora termica
     */
    public function testar(Request $request)
    {
        $ip = $request->input('ip');
        $porta = $request->input('porta', 9100);

        if (empty($ip)) {
            return response()->json([
                'success' => false,
                'message' => 'Informe o IP da impressora.'
            ], 422);
        }

        $resultado = $this->printService->testarConexao($ip, $porta);
        
        return response()->json($resultado);
    }

    /**
     * Imprime cupom nao fiscal na impressora termica
     */
    public function imprimirCupom($id)
    {
        try {
            $item = Nfce::with(['itens.produto', 'cliente', 'fatura'])->findOrFail($id);
            $config = Empresa::with('cidade')->where('id', $item->empresa_id)->first();
            $configGeral = ConfigGeral::where('empresa_id', $item->empresa_id)->first();

            // Verifica se a impressora esta configurada
            if (!$configGeral || !$configGeral->isPrinterConfigured()) {
                // Sem impressora configurada - retorna flag para usar o comportamento antigo (PDF)
                return response()->json([
                    'success' => false,
                    'use_pdf' => true,
                    'message' => 'Impressora nao configurada. Use o PDF.'
                ]);
            }

            // Renderiza o HTML do cupom
            $html = view('front_box.cupom_nao_fiscal', compact('item', 'config'))->render();

            // Converte HTML para texto
            $texto = $this->printService->htmlToText($html);

            // Formata com comandos ESC/POS
            $textoFormatado = $this->printService->formatarEscPos($texto);

            // Envia para a impressora
            $resultado = $this->printService->imprimir($textoFormatado, $configGeral);

            return response()->json($resultado);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao gerar cupom: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Imprime cupom de troca na impressora termica
     */
    public function imprimirTroca($id)
    {
        try {
            $item = Troca::with(['itens.produto', 'cliente', 'nfce.itens.produto', 'nfce.fatura', 'nfce.cliente'])->findOrFail($id);
            $config = Empresa::with('cidade')->where('id', $item->empresa_id)->first();
            $configGeral = ConfigGeral::where('empresa_id', $item->empresa_id)->first();

            if (!$configGeral || !$configGeral->isPrinterConfigured()) {
                return response()->json([
                    'success' => false,
                    'use_pdf' => true,
                    'message' => 'Impressora nao configurada. Use o PDF.'
                ]);
            }

            $html = view('trocas.cupom_troca', compact('item', 'config'))->render();
            $texto = $this->printService->htmlToText($html);
            $textoFormatado = $this->printService->formatarEscPos($texto);
            $resultado = $this->printService->imprimir($textoFormatado, $configGeral);

            return response()->json($resultado);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao gerar cupom de troca: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Imprime cupom de pre-venda na impressora termica
     */
    public function imprimirPreVenda($id)
    {
        try {
            $item = \App\Models\PreVenda::with(['itens.produto', 'cliente'])->findOrFail($id);
            $config = Empresa::with('cidade')->where('id', $item->empresa_id)->first();
            $configGeral = ConfigGeral::where('empresa_id', $item->empresa_id)->first();

            if (!$configGeral || !$configGeral->isPrinterConfigured()) {
                return response()->json([
                    'success' => false,
                    'use_pdf' => true,
                    'message' => 'Impressora nao configurada. Use o PDF.'
                ]);
            }

            $html = view('pre_venda.cupom', compact('item', 'config'))->render();
            $texto = $this->printService->htmlToText($html);
            $textoFormatado = $this->printService->formatarEscPos($texto);
            $resultado = $this->printService->imprimir($textoFormatado, $configGeral);

            return response()->json($resultado);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao gerar cupom de pre-venda: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Imprime comprovante de sangria na impressora termica
     */
    public function imprimirSangria($id)
    {
        try {
            $sangria = SangriaCaixa::findOrFail($id);
            $empresa = Empresa::with('cidade')->findOrFail($sangria->empresa_id);
            $configGeral = ConfigGeral::where('empresa_id', $sangria->empresa_id)->first();

            if (!$configGeral || !$configGeral->isPrinterConfigured()) {
                return response()->json([
                    'success' => false,
                    'use_pdf' => true,
                    'message' => 'Impressora nao configurada. Use o PDF.'
                ]);
            }

            $html = view('front_box.sangria_print', compact('sangria', 'empresa'))->render();
            $texto = $this->printService->htmlToText($html);
            $textoFormatado = $this->printService->formatarEscPos($texto);
            $resultado = $this->printService->imprimir($textoFormatado, $configGeral);

            return response()->json($resultado);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao gerar comprovante de sangria: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Imprime comprovante de suprimento na impressora termica
     */
    public function imprimirSuprimento($id)
    {
        try {
            $suprimento = SuprimentoCaixa::findOrFail($id);
            $empresa = Empresa::with('cidade')->findOrFail($suprimento->empresa_id);
            $configGeral = ConfigGeral::where('empresa_id', $suprimento->empresa_id)->first();

            if (!$configGeral || !$configGeral->isPrinterConfigured()) {
                return response()->json([
                    'success' => false,
                    'use_pdf' => true,
                    'message' => 'Impressora nao configurada. Use o PDF.'
                ]);
            }

            $html = view('front_box.suprimento_print', compact('suprimento', 'empresa'))->render();
            $texto = $this->printService->htmlToText($html);
            $textoFormatado = $this->printService->formatarEscPos($texto);
            $resultado = $this->printService->imprimir($textoFormatado, $configGeral);

            return response()->json($resultado);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao gerar comprovante de suprimento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna a configuracao atual da impressora para o frontend
     */
    public function configuracao()
    {
        $configGeral = ConfigGeral::where('empresa_id', request()->empresa_id)->first();

        return response()->json([
            'printer_configured' => $configGeral ? $configGeral->isPrinterConfigured() : false,
            'printer_nome' => $configGeral->printer_nome ?? null,
            'printer_ip' => $configGeral->printer_ip ?? null,
            'printer_porta' => $configGeral->printer_porta ?? 9100,
            'printer_largura' => $configGeral->printer_largura ?? '80',
        ]);
    }
}
