<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cidade;
use App\Models\PlanoConta;
use App\Models\ContaEmpresa;
use App\Models\ContaBoleto;
use App\Models\VideoSuporte;
use App\Models\ModeloEtiqueta;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HelperController extends Controller
{
    public function cidadePorNome($nome){
        $cidade = Cidade::
        where('nome', $nome)
        ->first();

        return response()->json($cidade, 200);
    }

    public function videoSuporte(Request $request){
        $item = VideoSuporte::where('url_servidor', $request->url)
        ->first();
        if($item == null){
            return response()->json("", 200);
        }
        return view('video_suporte.button', compact('item'));
    }

    public function cidadePorCodigoIbge($codigo){
        $cidade = Cidade::
        where('codigo', $codigo)
        ->first();

        return response()->json($cidade, 200);
    }

    public function cidadePorId($id){
        $cidade = Cidade::findOrFail($id);

        return response()->json($cidade, 200);
    }

    public function buscaCidades(Request $request){
        $data = Cidade::
        where('nome', 'like', "%$request->pesquisa%")
        ->get();

        return response()->json($data, 200);
        
    }

    public function planoContas(Request $request){
        $data = PlanoConta::
        where('descricao', 'like', "%$request->pesquisa%")
        ->where('empresa_id', $request->empresa_id)
        ->get();

        return response()->json($data, 200); 
    }

    public function contasEmpresa(Request $request){
        $data = ContaEmpresa::
        where('nome', 'like', "%$request->pesquisa%")
        ->where('empresa_id', $request->empresa_id)
        ->where('status', 1)
        ->get();

        return response()->json($data, 200); 
    }

    public function contasEmpresaCount(Request $request){
        $data = ContaEmpresa::
        where('empresa_id', $request->empresa_id)
        ->where('status', 1)
        ->count();

        return response()->json($data, 200); 
    }

    public function contaBoleto(Request $request){
        $data = ContaBoleto::findOrFail($request->conta_boleto_id);

        return response()->json($data, 200); 
    }

    public function etiqueta(Request $request){
        $item = ModeloEtiqueta::findOrFail($request->modelo_id);
        return response()->json($item, 200);
    }

    public function consultaCep(Request $request, $cep = null)
    {
        $cepInformado = $cep ?: $request->get('cep');
        $cepLimpo = preg_replace('/[^0-9]/', '', (string)$cepInformado);

        if (strlen($cepLimpo) !== 8) {
            return response()->json([
                'sucesso' => false,
                'erro' => true,
                'mensagem' => 'CEP inválido. Deve conter exatamente 8 dígitos numéricos.'
            ], 422);
        }

        $dados = null;

        // 1. Consulta primária com ViaCEP via Laravel HTTP Client nativo
        try {
            $response = Http::timeout(5)->get("https://viacep.com.br/ws/{$cepLimpo}/json/");
            if ($response->successful()) {
                $json = $response->json();
                if (is_array($json) && (!isset($json['erro']) || ($json['erro'] !== true && $json['erro'] !== 'true'))) {
                    $dados = [
                        'origem' => 'viacep',
                        'cep' => $json['cep'] ?? $cepLimpo,
                        'logradouro' => $json['logradouro'] ?? '',
                        'rua' => $json['logradouro'] ?? '',
                        'complemento' => '',
                        'bairro' => $json['bairro'] ?? '',
                        'cidade' => $json['localidade'] ?? '',
                        'uf' => strtoupper($json['uf'] ?? ''),
                        'ibge' => $json['ibge'] ?? '',
                    ];
                }
            }
        } catch (\Throwable $e) {
            Log::warning("Consulta ViaCEP falhou para o CEP {$cepLimpo}: " . $e->getMessage());
        }

        // 2. Fallback de alta disponibilidade com BrasilAPI
        if (!$dados) {
            try {
                $response = Http::timeout(5)->get("https://brasilapi.com.br/api/cep/v1/{$cepLimpo}");
                if ($response->successful()) {
                    $json = $response->json();
                    if (is_array($json) && !empty($json['city'])) {
                        $dados = [
                            'origem' => 'brasilapi',
                            'cep' => $json['cep'] ?? $cepLimpo,
                            'logradouro' => $json['street'] ?? '',
                            'rua' => $json['street'] ?? '',
                            'complemento' => '',
                            'bairro' => $json['neighborhood'] ?? '',
                            'cidade' => $json['city'] ?? '',
                            'uf' => strtoupper($json['state'] ?? ''),
                            'ibge' => '',
                        ];
                    }
                }
            } catch (\Throwable $e) {
                Log::warning("Consulta BrasilAPI falhou para o CEP {$cepLimpo}: " . $e->getMessage());
            }
        }

        // Se o CEP não for encontrado em nenhuma base
        if (!$dados) {
            return response()->json([
                'sucesso' => false,
                'erro' => true,
                'mensagem' => 'CEP não encontrado.'
            ], 404);
        }

        // Localiza a cidade correspondente no banco de dados local do ERP
        $cidadeModel = null;
        if (!empty($dados['ibge'])) {
            $cidadeModel = Cidade::where('codigo', $dados['ibge'])->first();
        }
        if (!$cidadeModel && !empty($dados['cidade']) && !empty($dados['uf'])) {
            $cidadeModel = Cidade::where('nome', $dados['cidade'])
                ->where('uf', $dados['uf'])
                ->first();
        }
        if (!$cidadeModel && !empty($dados['cidade'])) {
            $cidadeModel = Cidade::where('nome', 'like', $dados['cidade'])->first();
        }

        $dados['cidade_id'] = $cidadeModel ? $cidadeModel->id : null;
        $dados['cidade_info'] = $cidadeModel ? $cidadeModel->info : ($dados['cidade'] . ' (' . $dados['uf'] . ')');
        $dados['sucesso'] = true;
        $dados['erro'] = false;

        return response()->json($dados, 200);
    }
}
