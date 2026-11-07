<?php

namespace App\Services;

use App\Models\Empresa;
use App\Models\NotaServico;
use Carbon\Carbon;
use Exception;
use NFePHP\Common\Certificate;
use NFePHP\Common\Signer;

class NfseNacionalService
{
    protected Empresa $empresa;
    protected ?Certificate $certificate = null;
    protected int $ambiente; // 1 = Producao, 2 = Homologacao / Producao Restrita
    protected string $baseUrl;
    protected int $timeout = 30;

    public function __construct(Empresa $empresa)
    {
        $this->empresa = $empresa;
        $this->ambiente = (int)($empresa->ambiente ?? 2);
        
        // Endpoints oficiais do Ambiente de Dados Nacional (ADN) da Receita Federal
        $this->baseUrl = $this->ambiente === 1
            ? 'https://adn.nfse.gov.br'
            : 'https://adn.producaorestrita.nfse.gov.br';

        if (!empty($empresa->arquivo) && !empty($empresa->senha)) {
            try {
                $this->certificate = Certificate::readPfx($empresa->arquivo, $empresa->senha);
            } catch (Exception $e) {
                // Deixa $this->certificate como null para tratamento amigavel
            }
        }
    }

    /**
     * Retorna a instancia do Certificado Digital A1
     */
    public function getCertificate(): ?Certificate
    {
        return $this->certificate;
    }

    /**
     * Gera o identificador unico padrao da DPS (atributo Id da tag <infDPS>)
     * Formato oficial: "DPS" + 42 digitos numericos
     * (cLocEmi 7d + tpInsc 1d + doc 14d + serie 5d + nDPS 15d = 42 digitos)
     */
    public function gerarIdDps(string $cLocEmi, string $doc, string $serie, int $nDPS): string
    {
        $docLimpo = preg_replace('/\D/', '', $doc);
        $tpInsc = strlen($docLimpo) > 11 ? '1' : '2'; // 1 = CNPJ, 2 = CPF
        $docPad = str_pad($docLimpo, 14, '0', STR_PAD_LEFT);
        $cLocEmiPad = str_pad(preg_replace('/\D/', '', $cLocEmi), 7, '0', STR_PAD_LEFT);
        $seriePad = str_pad(preg_replace('/\D/', '', $serie ?: '1'), 5, '0', STR_PAD_LEFT);
        $nDPSPad = str_pad((string)$nDPS, 15, '0', STR_PAD_LEFT);

        return "DPS{$cLocEmiPad}{$tpInsc}{$docPad}{$seriePad}{$nDPSPad}";
    }

    /**
     * Monta a estrutura XML da Declaração de Prestação de Serviços (DPS)
     * conforme o esquema oficial da Receita Federal (dps_v1.00.xsd).
     */
    public function montarXmlDps(NotaServico $nota, ?int $nDPS = null, ?string $serie = null): string
    {
        $itemServico = $nota->servico;
        if (!$itemServico) {
            throw new Exception("A nota de serviço #{$nota->id} não possui itens de serviço cadastrados.");
        }

        // Numero e Serie da DPS
        if (!$nDPS) {
            $nDPS = $nota->numero_dps ?: $nota->novoNumeroDps();
        }
        if (!$serie) {
            $serie = $nota->serie_dps ?: ($this->empresa->numero_serie_dps ?: '1');
        }

        // Codigo do municipio emissor (7 digitos IBGE)
        $cLocEmi = $this->empresa->cidade ? $this->empresa->cidade->codigo : '2312908';
        $docPrestador = preg_replace('/\D/', '', $this->empresa->cpf_cnpj);

        // Gera o ID exclusivo para assinatura
        $idDps = $this->gerarIdDps($cLocEmi, $docPrestador, $serie, $nDPS);

        // Datas
        $dhEmi = now()->format('Y-m-d\TH:i:sP');
        $dCompet = $itemServico->data_competencia
            ? Carbon::parse($itemServico->data_competencia)->format('Y-m-d')
            : now()->format('Y-m-d');

        // Dados do Prestador
        $imPrestador = preg_replace('/\D/', '', $this->empresa->im ?? '');
        $isSimples = str_contains(strtolower($this->empresa->tributacao ?? ''), 'simples');
        $opSimpNac = $isSimples ? '3' : '1'; // 1 = Nao Optante, 3 = ME/EPP Simples Nacional
        $regApTribSN = $isSimples ? '1' : null; // 1 = Apuracao no SN

        // Dados do Tomador
        $cliente = $nota->cliente;
        $docTomador = preg_replace('/\D/', '', $nota->documento ?: ($cliente->cpf_cnpj ?? ''));
        $nomeTomador = $this->limparTexto($nota->razao_social ?: ($cliente->razao_social ?? 'CONSUMIDOR'));
        $cMunTomador = $nota->cidade ? $nota->cidade->codigo : ($cliente && $cliente->cidade ? $cliente->cidade->codigo : $cLocEmi);
        $cepTomador = preg_replace('/\D/', '', $nota->cep ?: ($cliente->cep ?? ''));
        $logrTomador = $this->limparTexto($nota->rua ?: ($cliente->rua ?? 'RUA PRINCIPAL'));
        $nroTomador = $this->limparTexto($nota->numero ?: ($cliente->numero ?? 'S/N'));
        $complTomador = $this->limparTexto($nota->complemento ?: ($cliente->complemento ?? ''));
        $bairroTomador = $this->limparTexto($nota->bairro ?: ($cliente->bairro ?? 'CENTRO'));
        $foneTomador = preg_replace('/\D/', '', $nota->telefone ?: ($cliente->telefone ?? ''));
        $emailTomador = filter_var($nota->email ?: ($cliente->email ?? ''), FILTER_VALIDATE_EMAIL) ?: null;

        // Dados do Servico
        $cLocPrestacao = !empty($itemServico->cidade_local_prestacao_servico)
            ? preg_replace('/\D/', '', $itemServico->cidade_local_prestacao_servico)
            : $cLocEmi;

        // Codigo de Tributacao Nacional (6 digitos)
        $cTribNac = preg_replace('/\D/', '', $itemServico->codigo_tributacao_nacional ?: ($itemServico->servico->codigo_tributacao_nacional ?? ''));
        if (strlen($cTribNac) < 6) {
            // Fallback usando os digitos do codigo_servico ou padrao nacional generico
            $cTribNac = str_pad(preg_replace('/\D/', '', $itemServico->codigo_servico ?: '010101'), 6, '0', STR_PAD_RIGHT);
        }
        $cTribMun = preg_replace('/\D/', '', $itemServico->codigo_tributacao_municipio ?? '');
        $xDescServ = $this->limparTexto($itemServico->discriminacao ?: ($itemServico->servico->nome ?? 'PRESTACAO DE SERVICOS'));

        // Valores
        $vServ = number_format((float)$itemServico->valor_servico, 2, '.', '');
        $aliquotaIss = (float)($itemServico->aliquota_iss ?? 0);
        $vIss = number_format(($itemServico->valor_servico * ($aliquotaIss / 100)), 2, '.', '');
        $tpRetIss = ((int)$itemServico->iss_retido === 1) ? '2' : '1'; // 1 = Nao retido, 2 = Retido tomador

        // Mapeamento de exigibilidade do ISS para tribISSQN
        // 1 = Tributavel, 2 = Imunidade, 3 = Exportacao, 4 = Nao Incidencia
        $exigibilidade = (int)($itemServico->exigibilidade_iss ?? 1);
        $tribISSQN = match ($exigibilidade) {
            2, 4 => '4', // Nao Incidencia
            3    => '3', // Isencao
            5    => '2', // Imunidade
            default => '1' // Tributavel
        };

        // Construcao do XML com a ordem estrita do schema dps_v1.00.xsd
        $xml = '<DPS xmlns="http://www.sped.fazenda.gov.br/nfse" versao="1.00">';
        $xml .= '<infDPS Id="' . $idDps . '">';
        $xml .= '<tpAmb>' . $this->ambiente . '</tpAmb>';
        $xml .= '<dhEmi>' . $dhEmi . '</dhEmi>';
        $xml .= '<verAplic>1.0.0</verAplic>';
        $xml .= '<serie>' . htmlspecialchars($serie) . '</serie>';
        $xml .= '<nDPS>' . $nDPS . '</nDPS>';
        $xml .= '<dCompet>' . $dCompet . '</dCompet>';
        $xml .= '<tpEmit>1</tpEmit>';
        $xml .= '<cLocEmi>' . $cLocEmi . '</cLocEmi>';

        // <prest>
        $xml .= '<prest>';
        if (strlen($docPrestador) > 11) {
            $xml .= '<CNPJ>' . $docPrestador . '</CNPJ>';
        } else {
            $xml .= '<CPF>' . $docPrestador . '</CPF>';
        }
        if (!empty($imPrestador)) {
            $xml .= '<IM>' . $imPrestador . '</IM>';
        }
        $xml .= '<regTrib>';
        $xml .= '<opSimpNac>' . $opSimpNac . '</opSimpNac>';
        if ($regApTribSN) {
            $xml .= '<regApTribSN>' . $regApTribSN . '</regApTribSN>';
        }
        $xml .= '</regTrib>';
        $xml .= '</prest>';

        // <toma>
        $xml .= '<toma>';
        if (strlen($docTomador) > 11) {
            $xml .= '<CNPJ>' . $docTomador . '</CNPJ>';
        } elseif (!empty($docTomador)) {
            $xml .= '<CPF>' . $docTomador . '</CPF>';
        }
        $xml .= '<xNome>' . htmlspecialchars($nomeTomador) . '</xNome>';
        $xml .= '<end>';
        $xml .= '<endNac>';
        $xml .= '<cMun>' . $cMunTomador . '</cMun>';
        if (!empty($cepTomador)) {
            $xml .= '<CEP>' . str_pad($cepTomador, 8, '0', STR_PAD_LEFT) . '</CEP>';
        }
        $xml .= '</endNac>';
        $xml .= '<xLgr>' . htmlspecialchars($logrTomador) . '</xLgr>';
        $xml .= '<nro>' . htmlspecialchars($nroTomador) . '</nro>';
        if (!empty($complTomador)) {
            $xml .= '<xCpl>' . htmlspecialchars($complTomador) . '</xCpl>';
        }
        $xml .= '<xBairro>' . htmlspecialchars($bairroTomador) . '</xBairro>';
        $xml .= '</end>';
        if (!empty($foneTomador)) {
            $xml .= '<fone>' . $foneTomador . '</fone>';
        }
        if (!empty($emailTomador)) {
            $xml .= '<email>' . htmlspecialchars($emailTomador) . '</email>';
        }
        $xml .= '</toma>';

        // <serv>
        $xml .= '<serv>';
        $xml .= '<locPrest>';
        $xml .= '<cLocPrestacao>' . $cLocPrestacao . '</cLocPrestacao>';
        $xml .= '</locPrest>';
        $xml .= '<cServ>';
        $xml .= '<cTribNac>' . $cTribNac . '</cTribNac>';
        if (!empty($cTribMun)) {
            $xml .= '<cTribMun>' . $cTribMun . '</cTribMun>';
        }
        $xml .= '<xDescServ>' . htmlspecialchars(mb_substr($xDescServ, 0, 2000)) . '</xDescServ>';
        $xml .= '</cServ>';
        $xml .= '</serv>';

        // <valores>
        $xml .= '<valores>';
        $xml .= '<vServPrest>';
        $xml .= '<vServ>' . $vServ . '</vServ>';
        $xml .= '</vServPrest>';

        // Descontos / Deducoes se existirem
        if ((float)($itemServico->desconto_condicional ?? 0) > 0) {
            $xml .= '<vDescCond>' . number_format((float)$itemServico->desconto_condicional, 2, '.', '') . '</vDescCond>';
        }
        if ((float)($itemServico->desconto_incondicional ?? 0) > 0) {
            $xml .= '<vDescIncond>' . number_format((float)$itemServico->desconto_incondicional, 2, '.', '') . '</vDescIncond>';
        }
        if ((float)($itemServico->valor_deducoes ?? 0) > 0) {
            $xml .= '<vDed>' . number_format((float)$itemServico->valor_deducoes, 2, '.', '') . '</vDed>';
        }

        // Tributacao do ISSQN
        $xml .= '<trib>';
        $xml .= '<tribMun>';
        $xml .= '<tribISSQN>' . $tribISSQN . '</tribISSQN>';
        $xml .= '<pAliq>' . number_format($aliquotaIss, 2, '.', '') . '</pAliq>';
        $xml .= '<vISSQN>' . $vIss . '</vISSQN>';
        $xml .= '<tpRetISSQN>' . $tpRetIss . '</tpRetISSQN>';
        $xml .= '</tribMun>';
        $xml .= '</trib>';

        $xml .= '</valores>';
        $xml .= '</infDPS>';
        $xml .= '</DPS>';

        return $xml;
    }

    /**
     * Assina digitalmente o XML da DPS via XMLDSig RSA-SHA256
     * com o Certificado Digital A1 da empresa emitente.
     */
    public function assinarXmlDps(string $xmlDps): string
    {
        if (!$this->certificate) {
            throw new Exception("Certificado digital da empresa não configurado ou senha inválida.");
        }

        // A assinatura no Padrao Nacional da NFS-e exige obrigatoriamente RSA-SHA256
        return Signer::sign($this->certificate, $xmlDps, 'infDPS', 'Id', OPENSSL_ALGO_SHA256);
    }

    /**
     * Compacta o XML assinado em GZIP e codifica em Base64 para o payload da API.
     */
    public function prepararPayloadEnvio(string $xmlAssinado): array
    {
        $gz = gzencode($xmlAssinado, 9);
        if ($gz === false) {
            throw new Exception("Falha ao compactar XML da DPS em GZip.");
        }

        return [
            'dpsXmlGZipB64' => base64_encode($gz)
        ];
    }

    /**
     * Gera a DPS completa, assina, atualiza os dados na NotaServico e retorna os artefatos.
     */
    public function gerarDpsAssinada(NotaServico $nota, bool $salvarNaNota = true): array
    {
        $nDPS = $nota->numero_dps ?: $nota->novoNumeroDps();
        $serie = $nota->serie_dps ?: ($this->empresa->numero_serie_dps ?: '1');

        $cLocEmi = $this->empresa->cidade ? $this->empresa->cidade->codigo : '2312908';
        $docPrestador = preg_replace('/\D/', '', $this->empresa->cpf_cnpj);
        $idDps = $this->gerarIdDps($cLocEmi, $docPrestador, $serie, $nDPS);

        $xmlCru = $this->montarXmlDps($nota, $nDPS, $serie);
        $xmlAssinado = $this->assinarXmlDps($xmlCru);
        $payload = $this->prepararPayloadEnvio($xmlAssinado);

        if ($salvarNaNota) {
            $nota->numero_dps = $nDPS;
            $nota->serie_dps = $serie;
            $nota->chave_dps = $idDps;
            $nota->xml_dps = $xmlAssinado;
            $nota->save();

            // Atualiza sequencial da empresa
            if ($nDPS > (int)$this->empresa->numero_ultima_dps) {
                $this->empresa->numero_ultima_dps = $nDPS;
                $this->empresa->numero_serie_dps = $serie;
                $this->empresa->save();
            }
        }

        return [
            'sucesso'     => true,
            'idDps'       => $idDps,
            'nDPS'        => $nDPS,
            'serie'       => $serie,
            'xmlCru'      => $xmlCru,
            'xmlAssinado' => $xmlAssinado,
            'payload'     => $payload
        ];
    }

    /**
     * Transmite a DPS para a API do Ambiente de Dados Nacional (ADN) da Receita Federal.
     * Utiliza mTLS com o Certificado Digital A1 da empresa.
     */
    public function transmitirDps(NotaServico $nota): array
    {
        $geracao = $this->gerarDpsAssinada($nota, true);
        $payload = $geracao['payload'];

        $endpoint = "{$this->baseUrl}/dps";
        $response = $this->requisicaoHttpMtls('POST', $endpoint, $payload);

        if ($response['status'] >= 200 && $response['status'] < 300) {
            $dados = $response['dados'] ?? [];

            // Extrai chave de acesso e numero oficial da NFS-e
            $chaveNfse = $dados['chaveAcesso'] ?? null;
            $numeroNfse = $dados['numero'] ?? null;
            $linkPdf = $dados['linkDanfse'] ?? "{$this->baseUrl}/danfse/{$chaveNfse}";

            $nota->estado = 'aprovado';
            if ($chaveNfse) $nota->chave = $chaveNfse;
            if ($numeroNfse) $nota->numero_nfse = $numeroNfse;
            $nota->url_pdf_nfse = $linkPdf;
            $nota->motivo_rejeicao = null;

            // Se a API retornou o XML oficial autorizado em Base64/GZip
            if (!empty($dados['nfseXmlGZipB64'])) {
                $xmlNfse = gzdecode(base64_decode($dados['nfseXmlGZipB64']));
                if ($xmlNfse) {
                    $pastaXml = public_path('xml_nota_servico');
                    if (!is_dir($pastaXml)) {
                        mkdir($pastaXml, 0755, true);
                    }
                    $nomeArquivo = ($chaveNfse ?: $geracao['idDps']) . '.xml';
                    file_put_contents("{$pastaXml}/{$nomeArquivo}", $xmlNfse);
                    $nota->url_xml = "/xml_nota_servico/{$nomeArquivo}";
                }
            }

            $nota->save();

            // Atualiza sequencial oficial de NFS-e na empresa
            if ($numeroNfse && $numeroNfse > (int)$this->empresa->numero_ultima_nfse) {
                $this->empresa->numero_ultima_nfse = (int)$numeroNfse;
                $this->empresa->save();
            }

            return [
                'sucesso'     => true,
                'codigo'      => $response['status'],
                'mensagem'    => 'NFS-e autorizada com sucesso pelo Emissor Nacional!',
                'chave'       => $chaveNfse,
                'numero'      => $numeroNfse,
                'link_pdf'    => $linkPdf,
                'id_dps'      => $geracao['idDps'],
                'dados'       => $dados
            ];
        }

        // Caso de erro ou rejeicao retornado pela SEFIN Nacional
        $msgErro = $this->extrairMensagemErro($response);
        $nota->estado = 'rejeitado';
        $nota->motivo_rejeicao = $msgErro;
        $nota->save();

        return [
            'sucesso'     => false,
            'codigo'      => $response['status'],
            'mensagem'    => $msgErro,
            'id_dps'      => $geracao['idDps'],
            'dados'       => $response['dados'] ?? null
        ];
    }

    /**
     * Consulta a NFS-e vinculada ao Id da DPS no Ambiente de Dados Nacional.
     */
    public function consultarDps(string $idDps): array
    {
        $endpoint = "{$this->baseUrl}/dps/{$idDps}";
        return $this->requisicaoHttpMtls('GET', $endpoint);
    }

    /**
     * Consulta a NFS-e pela chave de acesso oficial de 50 dígitos.
     */
    public function consultarNfse(string $chaveAcesso): array
    {
        $endpoint = "{$this->baseUrl}/nfse/{$chaveAcesso}";
        return $this->requisicaoHttpMtls('GET', $endpoint);
    }

    /**
     * Solicita o cancelamento da NFS-e junto ao Emissor Nacional.
     */
    public function cancelarNfse(NotaServico $nota, string $motivo, string $codigoMotivo = '1'): array
    {
        if (empty($nota->chave)) {
            throw new Exception("A nota não possui chave de acesso autorizada para cancelamento.");
        }

        $endpoint = "{$this->baseUrl}/nfse/{$nota->chave}/eventos";
        $payload = [
            'tipoEvento' => 'cancelamento',
            'codigoMotivo' => $codigoMotivo, // 1 = Erro na emissão, 2 = Serviço não prestado, etc.
            'motivo' => $motivo,
            'dataHoraEvento' => now()->format('Y-m-d\TH:i:sP')
        ];

        $response = $this->requisicaoHttpMtls('POST', $endpoint, $payload);
        if ($response['status'] >= 200 && $response['status'] < 300) {
            $nota->estado = 'cancelado';
            $nota->save();
            return [
                'sucesso'  => true,
                'mensagem' => 'NFS-e cancelada com sucesso no Emissor Nacional.',
                'dados'    => $response['dados']
            ];
        }

        $msgErro = $this->extrairMensagemErro($response);
        return [
            'sucesso'  => false,
            'mensagem' => "Não foi possível cancelar a NFS-e: {$msgErro}",
            'codigo'   => $response['status'],
            'dados'    => $response['dados']
        ];
    }

    /**
     * Executa requisição HTTP REST com autenticação mútua via TLS (mTLS).
     */
    protected function requisicaoHttpMtls(string $metodo, string $url, ?array $payload = null): array
    {
        if (!$this->certificate) {
            throw new Exception("Certificado A1 da empresa necessário para comunicação segura (mTLS) com o governo.");
        }

        // Escreve certificados temporários em formato PEM para o cURL
        $tempCert = tempnam(sys_get_temp_dir(), 'nfse_cert_');
        $tempKey = tempnam(sys_get_temp_dir(), 'nfse_key_');

        file_put_contents($tempCert, (string)$this->certificate->publicKey . "\n" . (string)$this->certificate->chainKeys);
        file_put_contents($tempKey, (string)$this->certificate->privateKey);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

        // mTLS - Certificado do cliente e chave privada
        curl_setopt($ch, CURLOPT_SSLCERT, $tempCert);
        curl_setopt($ch, CURLOPT_SSLKEY, $tempKey);

        $headers = [
            'Accept: application/json',
            'Content-Type: application/json; charset=utf-8'
        ];

        if ($metodo === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if ($payload) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            }
        } elseif ($metodo !== 'GET') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $metodo);
            if ($payload) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            }
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $respostaCorpo = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        // Remove com seguranca os arquivos temporarios de chave
        @unlink($tempCert);
        @unlink($tempKey);

        if (!empty($curlError)) {
            return [
                'status'   => 0,
                'sucesso'  => false,
                'erro_curl' => $curlError,
                'dados'    => null,
                'mensagem' => "Erro de conexão mTLS com o portal da NFS-e: {$curlError}"
            ];
        }

        $dadosJson = json_decode($respostaCorpo, true);

        return [
            'status'   => $httpCode,
            'sucesso'  => ($httpCode >= 200 && $httpCode < 300),
            'bruto'    => $respostaCorpo,
            'dados'    => $dadosJson ?: $respostaCorpo
        ];
    }

    /**
     * Extrai mensagem de erro legivel do payload retornado pela SEFIN.
     */
    protected function extrairMensagemErro(array $response): string
    {
        $dados = $response['dados'] ?? null;
        if (is_array($dados)) {
            if (!empty($dados['erros']) && is_array($dados['erros'])) {
                $mensagens = [];
                foreach ($dados['erros'] as $err) {
                    if (is_array($err)) {
                        $codigo = $err['codigo'] ?? '';
                        $desc = $err['mensagem'] ?? ($err['descricao'] ?? json_encode($err));
                        $mensagens[] = "[$codigo] $desc";
                    } else {
                        $mensagens[] = (string)$err;
                    }
                }
                return implode('; ', $mensagens);
            }

            if (!empty($dados['mensagens']) && is_array($dados['mensagens'])) {
                return implode('; ', array_map(fn($m) => is_array($m) ? ($m['descricao'] ?? json_encode($m)) : (string)$m, $dados['mensagens']));
            }

            if (!empty($dados['message'])) {
                return $dados['message'];
            }
        }

        if (!empty($response['mensagem'])) {
            return $response['mensagem'];
        }

        return "Erro HTTP {$response['status']} ao comunicar com a SEFIN Nacional.";
    }

    /**
     * Limpa acentos e caracteres nao compativeis com o padrao nacional da Receita Federal.
     */
    protected function limparTexto(?string $texto): string
    {
        if (empty($texto)) {
            return '';
        }

        $comAcento = ['à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ü', 'ú', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ü', 'Ú'];
        $semAcento = ['a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U'];

        $limpo = str_replace($comAcento, $semAcento, $texto);
        $limpo = preg_replace('/[^\w\s\-\.\,\/]/u', '', $limpo);
        return trim(preg_replace('/\s+/', ' ', $limpo));
    }
}
