<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NotaServico;
use App\Services\NfseNacionalService;

class NotaServicoController extends Controller
{
    /**
     * Transmite a DPS para autorização da NFS-e no Emissor Nacional.
     */
    public function transmitir(Request $request)
    {
        try {
            $item = NotaServico::findOrFail($request->id);
            $empresa = $item->empresa;

            if (empty($empresa->arquivo) || empty($empresa->senha)) {
                return response()->json([
                    'sucesso'  => false,
                    'mensagem' => 'Certificado Digital A1 não configurado para esta empresa. Cadastre o certificado e a senha no cadastro da empresa antes de transmitir.'
                ], 422);
            }

            $service = new NfseNacionalService($empresa);
            $resultado = $service->transmitirDps($item);

            if ($resultado['sucesso']) {
                return response()->json($resultado, 200);
            }

            return response()->json($resultado, 404);

        } catch (\Exception $e) {
            return response()->json([
                'sucesso'  => false,
                'mensagem' => 'Erro ao processar NFS-e: ' . $e->getMessage()
            ], 403);
        }
    }

    /**
     * Consulta o status da NFS-e ou da DPS no Ambiente Nacional.
     */
    public function consultar(Request $request)
    {
        try {
            $item = NotaServico::findOrFail($request->id);
            $empresa = $item->empresa;

            if (empty($empresa->arquivo) || empty($empresa->senha)) {
                return response()->json([
                    'sucesso'  => false,
                    'mensagem' => 'Certificado Digital A1 da empresa não configurado.'
                ], 422);
            }

            $service = new NfseNacionalService($empresa);

            // Se ja possuir chave de acesso oficial da NFS-e
            if (!empty($item->chave)) {
                $resp = $service->consultarNfse($item->chave);
            } elseif (!empty($item->chave_dps)) {
                // Consulta pelo ID da DPS
                $resp = $service->consultarDps($item->chave_dps);
            } else {
                return response()->json([
                    'sucesso'  => false,
                    'mensagem' => 'Esta nota não possui chave de acesso nem DPS transmitida para consulta.'
                ], 404);
            }

            if ($resp['sucesso']) {
                $dados = $resp['dados'] ?? [];
                if (!empty($dados['chaveAcesso'])) {
                    $item->estado = 'aprovado';
                    $item->chave = $dados['chaveAcesso'];
                    if (!empty($dados['numero'])) {
                        $item->numero_nfse = $dados['numero'];
                    }
                    $item->url_pdf_nfse = $dados['linkDanfse'] ?? "{$service->getBaseUrl()}/danfse/{$item->chave}";

                    if (!empty($dados['nfseXmlGZipB64'])) {
                        $xml = gzdecode(base64_decode($dados['nfseXmlGZipB64']));
                        if ($xml) {
                            $pastaXml = public_path('xml_nota_servico');
                            if (!is_dir($pastaXml)) {
                                mkdir($pastaXml, 0755, true);
                            }
                            file_put_contents("{$pastaXml}/{$item->chave}.xml", $xml);
                            $item->url_xml = "/xml_nota_servico/{$item->chave}.xml";
                        }
                    }

                    $item->save();
                }

                return response()->json([
                    'sucesso'   => true,
                    'mensagem'  => 'Consulta realizada com sucesso!',
                    'link_pdf'  => $item->url_pdf_nfse ?: "{$service->getBaseUrl()}/danfse/{$item->chave}",
                    'dados'     => $dados
                ], 200);
            }

            return response()->json([
                'sucesso'  => false,
                'mensagem' => $resp['mensagem'] ?? 'Nota ainda não processada pelo Emissor Nacional.',
                'dados'    => $resp['dados'] ?? null
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'sucesso'  => false,
                'mensagem' => 'Erro ao consultar NFS-e: ' . $e->getMessage()
            ], 403);
        }
    }

    /**
     * Cancela a NFS-e no Emissor Nacional.
     */
    public function cancelar(Request $request)
    {
        try {
            $item = NotaServico::findOrFail($request->id);
            $empresa = $item->empresa;

            if (empty($empresa->arquivo) || empty($empresa->senha)) {
                return response()->json([
                    'sucesso'  => false,
                    'mensagem' => 'Certificado Digital A1 da empresa não configurado.'
                ], 422);
            }

            if (empty($item->chave)) {
                return response()->json([
                    'sucesso'  => false,
                    'mensagem' => 'Esta nota não possui chave de acesso autorizada para cancelamento.'
                ], 422);
            }

            $service = new NfseNacionalService($empresa);
            $motivo = $request->motivo ?: 'Cancelamento por solicitacao do emitente';
            $resultado = $service->cancelarNfse($item, $motivo, '1');

            if ($resultado['sucesso']) {
                return response()->json($resultado, 200);
            }

            return response()->json($resultado, 404);

        } catch (\Exception $e) {
            return response()->json([
                'sucesso'  => false,
                'mensagem' => 'Erro ao cancelar NFS-e: ' . $e->getMessage()
            ], 403);
        }
    }
}
