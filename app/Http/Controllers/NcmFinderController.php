<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NcmFinderController extends Controller
{
    public function index(Request $request)
    {
        // Pega produtos com NCM zerado ou inválido
        $data = Produto::where('empresa_id', $request->empresa_id)
            ->where(function($q) {
                $q->where('ncm', '00000000')
                  ->orWhereNull('ncm')
                  ->orWhere('ncm', '');
            })
            ->where('codigo_barras', '!=', 'SEM GTIN')
            ->where('codigo_barras', '!=', '')
            ->paginate(50);

        return view('migracao.ncm_finder', compact('data'));
    }

    public function find(Request $request, $id)
    {
        sleep(1); // Evita rate limit excessivo do Cosmos
        $produto = Produto::findOrFail($id);
        $token = $request->token; // Token passado via request

        if (!$token) {
            return response()->json(['error' => 'Token não informado'], 400);
        }

        $ean = $produto->codigo_barras;
        
        // Limpeza básica do EAN
        $ean = preg_replace('/[^0-9]/', '', $ean);
        
        Log::info("Buscando NCM para EAN: $ean");

        if (strlen($ean) < 8 || strlen($ean) > 14) {
             Log::warning("EAN Inválido ou curto ($ean), tentando buscar pelo nome...");
             return $this->searchByName($produto, $token);
        }

        try {
            Log::info("Consultando API Cosmos por GTIN...");
            $response = Http::withHeaders([
                'X-Cosmos-Token' => $token,
                'User-Agent' => 'Cosmos-API-Request'
            ])->get("https://api.cosmos.bluesoft.com.br/gtins/{$ean}");
            
            Log::info("Status Code Cosmos: " . $response->status());

            if ($response->successful()) {
                $data = $response->json();
                return $this->updateProduto($produto, $data, 'GTIN');
            } else {
                Log::warning("GTIN não encontrado, tentando buscar pelo nome...");
                return $this->searchByName($produto, $token);
            }

        } catch (\Exception $e) {
            Log::error("Exception Cosmos: " . $e->getMessage());
            return response()->json(['error' => 'Erro na conexão: ' . $e->getMessage()], 500);
        }
    }

    private function searchByName($produto, $token)
    {
        try {
            $nomeOriginal = trim($produto->nome); // Remove espaços do início/fim
            $nome = $nomeOriginal;

            // Heurística para corrigir nomes espaçados (ex: "M A S S A")
            // Procura por padrão "Letra Espaço Letra Espaço Letra"
            if (preg_match('/(\w\s+){3,}/', $nome)) {
                
                // Remove todos os espaços para juntar
                $nome = preg_replace('/\s+/', '', $nome);
                
                // Opcional: Tentar separar palavras novamente se ficarem grudadas demais (ex: MASSADEMODELAR)
                // Mas para o Cosmos, MASSADEMODELAR muitas vezes acha melhor que M A S S A
                
                Log::info("Nome Limpo para busca: '$nomeOriginal' -> '$nome'");
            }

            $nomeEncoded = urlencode($nome);
            Log::info("Buscando por nome: $nome");

            $response = Http::withHeaders([
                'X-Cosmos-Token' => $token,
                'User-Agent' => 'Cosmos-API-Request'
            ])->get("https://api.cosmos.bluesoft.com.br/products?q={$nomeEncoded}");

            if ($response->successful()) {
                $list = $response->json();
                
                // Pega o primeiro resultado da lista que tenha NCM
                if (!empty($list) && isset($list[0]['ncm']['code'])) {
                    $item = $list[0];
                    return $this->updateProduto($produto, $item, 'NOME (Aproximado)');
                } else {
                     return response()->json(['error' => 'Produto não encontrado por Nome: ' . $nome], 404);
                }
            }
            return response()->json(['error' => 'Erro na busca por Nome: ' . $response->status()], 404);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro busca por nome: ' . $e->getMessage()], 500);
        }
    }

    private function updateProduto($produto, $data, $metodo)
    {
        $ncm = $data['ncm']['code'] ?? null;
        $descricao = $data['ncm']['description'] ?? null; // Descrição do NCM
        
        // As vezes a descrição do produto vem em 'description' ou 'name'
        // Mas o importante aqui é o NCM.

        if ($ncm) {
            $ncm = preg_replace('/[^0-9]/', '', $ncm);
            
            $produto->ncm = $ncm;
            $produto->save();

            return response()->json([
                'success' => true,
                'ncm' => $ncm,
                'descricao' => $descricao, // Retorna a descrição do NCM para a tabela
                'message' => "Encontrado via $metodo"
            ]);
        }
        
        return response()->json(['error' => 'NCM não presente no retorno'], 404);
    }
}
