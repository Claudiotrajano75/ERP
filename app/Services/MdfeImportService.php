<?php

namespace App\Services;

use App\Models\Cidade;
use App\Models\Ciot;
use App\Models\CTeDescarga;
use App\Models\Empresa;
use App\Models\InfoDescarga;
use App\Models\Mdfe;
use App\Models\MunicipioCarregamento;
use App\Models\NFeDescarga;
use App\Models\Percurso;
use App\Models\ValePedagio;
use App\Models\Veiculo;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class MdfeImportService
{
    /**
     * Processa um arquivo enviado (XML ou ZIP) e importa o(s) MDF-e(s).
     *
     * @param UploadedFile $file
     * @param int $empresaId
     * @return array
     */
    public function importarArquivo(UploadedFile $file, int $empresaId): array
    {
        $extensao = strtolower($file->getClientOriginalExtension());

        if ($extensao === 'zip') {
            return $this->importarZip($file, $empresaId);
        }

        if ($extensao === 'xml') {
            $conteudo = file_get_contents($file->getRealPath());
            $resultado = $this->importarXmlString($conteudo, $empresaId, $file->getClientOriginalName());

            return [
                'sucesso' => true,
                'importados' => $resultado['status'] === 'importado' ? 1 : 0,
                'atualizados' => $resultado['status'] === 'atualizado' ? 1 : 0,
                'total' => 1,
                'detalhes' => [$resultado],
                'mensagem' => $resultado['mensagem']
            ];
        }

        throw new Exception("Formato inválido. Envie um arquivo com extensão .XML ou .ZIP.");
    }

    /**
     * Extrai e importa múltiplos XMLs contidos em um arquivo ZIP.
     */
    private function importarZip(UploadedFile $file, int $empresaId): array
    {
        $zip = new ZipArchive();
        if ($zip->open($file->getRealPath()) !== true) {
            throw new Exception("Não foi possível abrir o arquivo ZIP enviado.");
        }

        $importados = 0;
        $atualizados = 0;
        $erros = [];
        $detalhes = [];

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $nomeArquivo = $zip->getNameIndex($i);

            // Ignora pastas ou arquivos de sistema (ex: Mac OSX __MACOSX)
            if (substr($nomeArquivo, -1) === '/' || str_starts_with($nomeArquivo, '__MACOSX')) {
                continue;
            }

            if (strtolower(pathinfo($nomeArquivo, PATHINFO_EXTENSION)) !== 'xml') {
                continue;
            }

            $conteudo = $zip->getFromIndex($i);
            if (empty(trim($conteudo))) {
                continue;
            }

            try {
                $res = $this->importarXmlString($conteudo, $empresaId, basename($nomeArquivo));
                $detalhes[] = $res;
                if ($res['status'] === 'importado') {
                    $importados++;
                } elseif ($res['status'] === 'atualizado') {
                    $atualizados++;
                }
            } catch (Exception $e) {
                $erros[] = basename($nomeArquivo) . ': ' . $e->getMessage();
            }
        }

        $zip->close();

        $totalProcessados = $importados + $atualizados;
        if ($totalProcessados === 0 && !empty($erros)) {
            throw new Exception("Falha ao importar arquivos do ZIP: " . implode('; ', array_slice($erros, 0, 3)));
        }

        $mensagem = "Processamento concluído: {$importados} MDF-e(s) importado(s)";
        if ($atualizados > 0) {
            $mensagem .= ", {$atualizados} atualizado(s)";
        }
        if (!empty($erros)) {
            $mensagem .= " (" . count($erros) . " arquivo(s) com alerta).";
        } else {
            $mensagem .= " com sucesso!";
        }

        return [
            'sucesso' => true,
            'importados' => $importados,
            'atualizados' => $atualizados,
            'total' => $totalProcessados,
            'erros' => $erros,
            'detalhes' => $detalhes,
            'mensagem' => $mensagem
        ];
    }

    /**
     * Lê e grava uma string XML de MDF-e no banco de dados e no disco.
     */
    public function importarXmlString(string $xmlContent, int $empresaId, string $nomeArquivo = 'arquivo.xml'): array
    {
        $xmlContent = trim($xmlContent);
        if (empty($xmlContent)) {
            throw new Exception("O conteúdo do XML está vazio.");
        }

        $xml = @simplexml_load_string($xmlContent);
        if ($xml === false) {
            throw new Exception("O arquivo {$nomeArquivo} não é um XML válido.");
        }

        // Localiza o nó <MDFe> e <infMDFe>
        $infMDFe = null;
        $protMDFe = null;

        if (isset($xml->MDFe->infMDFe)) {
            $infMDFe = $xml->MDFe->infMDFe;
            $protMDFe = $xml->protMDFe ?? null;
        } elseif (isset($xml->infMDFe)) {
            $infMDFe = $xml->infMDFe;
            $protMDFe = $xml->protMDFe ?? null;
        }

        if ($infMDFe === null) {
            // Verifica se enviou NF-e ou CT-e por engano
            if (isset($xml->NFe) || isset($xml->infNFe)) {
                throw new Exception("O arquivo {$nomeArquivo} é uma NF-e, não um manifesto MDF-e.");
            }
            if (isset($xml->CTe) || isset($xml->infCte)) {
                throw new Exception("O arquivo {$nomeArquivo} é um CT-e, não um manifesto MDF-e.");
            }
            throw new Exception("Estrutura de MDF-e (<infMDFe>) não encontrada no arquivo {$nomeArquivo}.");
        }

        // Chave de 44 dígitos
        $chave = '';
        if (isset($infMDFe->attributes()->Id)) {
            $chave = preg_replace('/[^0-9]/', '', (string)$infMDFe->attributes()->Id);
        }
        if (strlen($chave) !== 44 && $protMDFe && isset($protMDFe->infProt->chMDFe)) {
            $chave = preg_replace('/[^0-9]/', '', (string)$protMDFe->infProt->chMDFe);
        }

        if (strlen($chave) !== 44) {
            throw new Exception("Não foi possível identificar uma chave de MDF-e válida de 44 dígitos em {$nomeArquivo}.");
        }

        // Protocolo e status da SEFAZ
        $protocolo = '';
        $estadoEmissao = 'aprovado';
        if ($protMDFe && isset($protMDFe->infProt)) {
            $protocolo = (string)($protMDFe->infProt->nProt ?? '');
            $cStat = (string)($protMDFe->infProt->cStat ?? '');
            if ($cStat == '101') {
                $estadoEmissao = 'cancelado';
            } elseif ($cStat == '100' || $cStat == '132') {
                $estadoEmissao = 'aprovado';
            }
        }

        $ide = $infMDFe->ide;
        $emit = $infMDFe->emit ?? null;
        $rodo = $infMDFe->infModal->rodo ?? null;
        $tot = $infMDFe->tot ?? null;
        $seg = $infMDFe->seg ?? null;
        $prodPred = $infMDFe->prodPred ?? null;
        $infAdic = $infMDFe->infAdic ?? null;

        // Datas
        $dhEmi = (string)($ide->dhEmi ?? date('Y-m-d\TH:i:sP'));
        $dataInicioViagem = !empty($ide->dhIniViagem) 
            ? date('Y-m-d', strtotime((string)$ide->dhIniViagem))
            : date('Y-m-d', strtotime($dhEmi));

        // CNPJ Contratante ou Emitente
        $cnpjContratante = '';
        if ($rodo && isset($rodo->infANTT->infContratante->CNPJ)) {
            $cnpjContratante = (string)$rodo->infANTT->infContratante->CNPJ;
        } elseif ($emit && isset($emit->CNPJ)) {
            $cnpjContratante = (string)$emit->CNPJ;
        }

        // Veículo de Tração
        $veiculoTracaoId = null;
        $condutorNome = '';
        $condutorCpf = '';
        $lacRodo = '';

        if ($rodo && isset($rodo->veicTracao)) {
            $vt = $rodo->veicTracao;
            $placaTracao = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string)$vt->placa));
            $ufTracao = (string)($vt->UF ?? $ide->UFIni ?? '');

            if (!empty($placaTracao)) {
                $veiculo = Veiculo::where('empresa_id', $empresaId)
                    ->where('placa', $placaTracao)
                    ->first();

                if (!$veiculo) {
                    $veiculo = Veiculo::create([
                        'empresa_id' => $empresaId,
                        'placa' => $placaTracao,
                        'uf' => $ufTracao,
                        'tipo' => '14', // Caminhão
                        'tipo_carroceria' => (string)($vt->tpCar ?? '00'),
                        'tipo_rodado' => (string)($vt->tpRod ?? '01'),
                        'tara' => (float)($vt->tara ?? 0),
                        'capacidade' => (float)($vt->capKG ?? 0),
                        'rntrc' => (string)($rodo->infANTT->RNTRC ?? ''),
                        'status' => 1
                    ]);
                }
                $veiculoTracaoId = $veiculo->id;
            }

            if (isset($vt->condutor)) {
                $condutorNome = (string)($vt->condutor->xNome ?? '');
                $condutorCpf = preg_replace('/[^0-9]/', '', (string)($vt->condutor->CPF ?? ''));
            }
        }

        // Veículo Reboque (se houver)
        $veiculoReboqueId = null;
        if ($rodo && isset($rodo->veicReboque)) {
            $vr = $rodo->veicReboque;
            $placaReboque = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string)$vr->placa));
            $ufReboque = (string)($vr->UF ?? '');

            if (!empty($placaReboque)) {
                $reboque = Veiculo::where('empresa_id', $empresaId)
                    ->where('placa', $placaReboque)
                    ->first();

                if (!$reboque) {
                    $reboque = Veiculo::create([
                        'empresa_id' => $empresaId,
                        'placa' => $placaReboque,
                        'uf' => $ufReboque,
                        'tipo' => '10', // Reboque
                        'tipo_carroceria' => (string)($vr->tpCar ?? '00'),
                        'tara' => (float)($vr->tara ?? 0),
                        'capacidade' => (float)($vr->capKG ?? 0),
                        'status' => 1
                    ]);
                }
                $veiculoReboqueId = $reboque->id;
            }
        }

        if ($rodo && isset($rodo->lacRodo->nLacre)) {
            $lacRodo = (string)$rodo->lacRodo->nLacre;
        }

        // Local de emissão padrão
        $localId = null;
        if (auth()->check() && function_exists('__getLocalAtivo')) {
            try {
                $local = __getLocalAtivo();
                if ($local) {
                    $localId = $local->id;
                }
            } catch (\Throwable $e) {
                $localId = null;
            }
        }

        // Cria diretório de XMLs se não existir e salva o XML físico
        $diretorioXml = public_path('xml_mdfe');
        if (!is_dir($diretorioXml)) {
            mkdir($diretorioXml, 0777, true);
        }
        $caminhoXml = $diretorioXml . '/' . $chave . '.xml';
        file_put_contents($caminhoXml, $xmlContent);

        // Inicia transação no banco de dados
        return DB::transaction(function () use (
            $empresaId, $chave, $ide, $tot, $seg, $prodPred, $infAdic, $infMDFe,
            $protocolo, $estadoEmissao, $dataInicioViagem, $dhEmi, $cnpjContratante,
            $veiculoTracaoId, $veiculoReboqueId, $condutorNome, $condutorCpf, $lacRodo,
            $localId, $nomeArquivo
        ) {
            $mdfe = Mdfe::where('empresa_id', $empresaId)
                ->where('chave', $chave)
                ->first();

            $statusOperacao = 'importado';
            if ($mdfe) {
                $statusOperacao = 'atualizado';
            } else {
                $mdfe = new Mdfe();
                $mdfe->empresa_id = $empresaId;
                $mdfe->chave = $chave;
            }

            // Atributos principais
            $mdfe->mdfe_numero = (int)($ide->nMDF ?? 0);
            $mdfe->uf_inicio = (string)($ide->UFIni ?? '');
            $mdfe->uf_fim = (string)($ide->UFFim ?? '');
            $mdfe->tipo_modal = (int)($ide->modal ?? 1);
            $mdfe->tp_emit = (int)($ide->tpEmit ?? 2);
            $mdfe->tp_transp = (int)($ide->tpTransp ?? 1);
            $mdfe->data_inicio_viagem = $dataInicioViagem;
            $mdfe->cnpj_contratante = $cnpjContratante;
            $mdfe->veiculo_tracao_id = $veiculoTracaoId;
            $mdfe->veiculo_reboque_id = $veiculoReboqueId;
            $mdfe->condutor_nome = $condutorNome;
            $mdfe->condutor_cpf = $condutorCpf;
            $mdfe->lac_rodo = $lacRodo;
            $mdfe->protocolo = $protocolo;
            $mdfe->estado_emissao = $estadoEmissao;
            $mdfe->encerrado = 0;
            $mdfe->local_id = $localId;

            // Totais
            if ($tot) {
                $mdfe->valor_carga = (float)($tot->vCarga ?? 0);
                $mdfe->quantidade_carga = (float)($tot->qCarga ?? 0);
                $mdfe->unidade_medida = (string)($tot->cUnid ?? '01');
            }

            // Produto predominante
            if ($prodPred) {
                $mdfe->tp_carga = (string)($prodPred->tpCarga ?? '05');
                $mdfe->produto_pred_nome = (string)($prodPred->xProd ?? '');
                $mdfe->produto_pred_ncm = preg_replace('/[^0-9]/', '', (string)($prodPred->NCM ?? ''));
                $mdfe->produto_pred_cod_barras = (string)($prodPred->cEAN ?? '');
                if (isset($prodPred->infLotacao->infLocalCarrega->CEP)) {
                    $mdfe->cep_carrega = (string)$prodPred->infLotacao->infLocalCarrega->CEP;
                }
                if (isset($prodPred->infLotacao->infLocalDescarrega->CEP)) {
                    $mdfe->cep_descarrega = (string)$prodPred->infLotacao->infLocalDescarrega->CEP;
                }
            }

            // Seguradora
            if ($seg) {
                $mdfe->seguradora_nome = (string)($seg->infSeg->xSeg ?? '');
                $mdfe->seguradora_cnpj = (string)($seg->infSeg->CNPJ ?? '');
                $mdfe->numero_apolice = (string)($seg->nApol ?? '');
                $mdfe->numero_averbacao = (string)($seg->nAver ?? '');
                $mdfe->responsavel_seguro = (string)($seg->infResp->respSeg ?? '');
                $mdfe->cnpj_responsavel_seguro = (string)($seg->infResp->CNPJ ?? '');
            }

            // Informações adicionais
            if ($infAdic) {
                $mdfe->info_complementar = (string)($infAdic->infCpl ?? '');
                $mdfe->info_adicional_fisco = (string)($infAdic->infAdFisco ?? '');
            }

            // Salva data original do documento se for novo
            if (!$mdfe->exists && !empty($dhEmi)) {
                $mdfe->created_at = date('Y-m-d H:i:s', strtotime($dhEmi));
            }

            $mdfe->save();

            // Sincroniza Municípios de Carregamento
            $mdfe->municipiosCarregamento()->delete();
            if (isset($ide->infMunCarrega)) {
                foreach ($ide->infMunCarrega as $mc) {
                    $codMun = (string)($mc->cMunCarrega ?? '');
                    $cidade = $this->buscarOuCriarCidade($codMun, (string)($mc->xMunCarrega ?? ''), $mdfe->uf_inicio);
                    if ($cidade) {
                        MunicipioCarregamento::create([
                            'mdfe_id' => $mdfe->id,
                            'cidade_id' => $cidade->id
                        ]);
                    }
                }
            }

            // Sincroniza Percurso
            $mdfe->percurso()->delete();
            if (isset($ide->infPercurso)) {
                foreach ($ide->infPercurso as $perc) {
                    $ufPer = (string)($perc->UFPer ?? '');
                    if (!empty($ufPer)) {
                        Percurso::create([
                            'mdfe_id' => $mdfe->id,
                            'uf' => $ufPer
                        ]);
                    }
                }
            }

            // Sincroniza Descarregamentos e Documentos (NF-e / CT-e)
            $mdfe->infoDescarga()->delete();
            if (isset($infMDFe->infDoc->infMunDescarga)) {
                $placa = $veiculoTracaoId ? (Veiculo::find($veiculoTracaoId)->placa ?? '') : '';

                foreach ($infMDFe->infDoc->infMunDescarga as $munDesc) {
                    $codMunDesc = (string)($munDesc->cMunDescarga ?? '');
                    $cidadeDesc = $this->buscarOuCriarCidade($codMunDesc, (string)($munDesc->xMunDescarga ?? ''), $mdfe->uf_fim);

                    $cidadeId = $cidadeDesc ? $cidadeDesc->id : null;

                    // NF-es vinculadas a este município de descarregamento
                    if (isset($munDesc->infNFe)) {
                        foreach ($munDesc->infNFe as $infNfe) {
                            $chNFe = (string)($infNfe->chNFe ?? '');
                            if (!empty($chNFe)) {
                                $info = InfoDescarga::create([
                                    'mdfe_id' => $mdfe->id,
                                    'cidade_id' => $cidadeId,
                                    'tp_unid_transp' => 1,
                                    'id_unid_transp' => $placa,
                                    'quantidade_rateio' => $mdfe->quantidade_carga > 0 ? $mdfe->quantidade_carga : 0
                                ]);

                                NFeDescarga::create([
                                    'info_id' => $info->id,
                                    'chave' => $chNFe,
                                    'seg_cod_barras' => (string)($infNfe->SegCodBarra ?? '')
                                ]);
                            }
                        }
                    }

                    // CT-es vinculados
                    if (isset($munDesc->infCTe)) {
                        foreach ($munDesc->infCTe as $infCte) {
                            $chCTe = (string)($infCte->chCTe ?? '');
                            if (!empty($chCTe)) {
                                $info = InfoDescarga::create([
                                    'mdfe_id' => $mdfe->id,
                                    'cidade_id' => $cidadeId,
                                    'tp_unid_transp' => 1,
                                    'id_unid_transp' => $placa,
                                    'quantidade_rateio' => $mdfe->quantidade_carga > 0 ? $mdfe->quantidade_carga : 0
                                ]);

                                CTeDescarga::create([
                                    'info_id' => $info->id,
                                    'chave' => $chCTe,
                                    'seg_cod_barras' => (string)($infCte->SegCodBarra ?? '')
                                ]);
                            }
                        }
                    }
                }
            }

            // Log da operação
            if (function_exists('__createLog')) {
                __createLog($empresaId, 'MDFe', 'importacao xml', "MDF-e nº {$mdfe->mdfe_numero} ({$mdfe->chave}) {$statusOperacao} via XML");
            }

            return [
                'status' => $statusOperacao,
                'mdfe_id' => $mdfe->id,
                'numero' => $mdfe->mdfe_numero,
                'chave' => $mdfe->chave,
                'arquivo' => $nomeArquivo,
                'mensagem' => "MDF-e nº {$mdfe->mdfe_numero} {$statusOperacao} com sucesso!"
            ];
        });
    }

    /**
     * Localiza ou cria a cidade no banco com base no código IBGE, nome e UF.
     */
    private function buscarOuCriarCidade(?string $codigoIbge, ?string $nome, ?string $uf): ?Cidade
    {
        if (!empty($codigoIbge)) {
            $cidade = Cidade::where('codigo', $codigoIbge)->first();
            if ($cidade) {
                return $cidade;
            }
        }

        if (!empty($nome) && !empty($uf)) {
            $cidade = Cidade::where('nome', 'like', $nome)
                ->where('uf', $uf)
                ->first();
            if ($cidade) {
                return $cidade;
            }
        }

        if (!empty($codigoIbge) && !empty($nome) && !empty($uf)) {
            try {
                return Cidade::create([
                    'codigo' => $codigoIbge,
                    'nome' => $nome,
                    'uf' => $uf
                ]);
            } catch (Exception $e) {
                return Cidade::where('uf', $uf)->first();
            }
        }

        return null;
    }
}
