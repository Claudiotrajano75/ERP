<?php

namespace App\Services;

use App\Models\Cidade;
use App\Models\Empresa;
use App\Models\InfoDescarga;
use App\Models\Mdfe;
use InvalidArgumentException;

/**
 * Lê o XML da NF-e (ou o XML da nfeProc) e monta os dados para preencher
 * automaticamente a tela de emissão da MDF-e.
 */
class MdfeAutoFillService
{
    private array $avisos = [];

    /**
     * Recebe os arquivos enviados no upload e devolve o preenchimento pronto.
     */
    public function fromUploadedFiles($files): array
    {
        $notas = [];

        foreach ((array) $files as $file) {
            if (!$file || !$file->isValid()) {
                $this->avisar('Um dos arquivos enviados não pôde ser lido e foi ignorado.');
                continue;
            }

            $notas[] = $this->lerXml(file_get_contents($file->getRealPath()));
        }

        return $this->montar($notas);
    }

    /**
     * Atalho para leitura de um XML em string (usado no XML da SEFAZ/DFe).
     */
    public function fromXmlString(string $xmlString): array
    {
        return $this->montar([$this->lerXml($xmlString)]);
    }

    /**
     * Normaliza um XML de NF-e nos dados que a MDF-e precisa.
     */
    public function lerXml(string $xmlString): array
    {
        $xml = @simplexml_load_string(trim($xmlString));

        if ($xml === false || $xml->NFe->infNFe == null) {
            throw new InvalidArgumentException('Este XML não é uma NF-e válida.');
        }

        $inf = $xml->NFe->infNFe;
        $total = $inf->total->ICMSTot;

        // NFC-e de consumidor final pode não ter destinatário identificado
        $dest = isset($inf->dest) ? $inf->dest : null;
        $enderDest = $dest != null && isset($dest->enderDest) ? $dest->enderDest : null;

        $valor = (float) $total->vNF;
        $produtoPredominante = null;
        $quantidadeItens = 0.0;

        foreach ($inf->det as $det) {
            $prod = $det->prod;
            $valorProduto = (float) $prod->vProd;

            // Algumas notas não trazem o total; nesse caso somamos os itens
            if ($valor <= 0) {
                $valor += $valorProduto;
            }

            $quantidadeItens += (float) $prod->qCom;

            if ($produtoPredominante === null || $valorProduto > $produtoPredominante['valor']) {
                $produtoPredominante = [
                    'valor' => $valorProduto,
                    'nome' => mb_substr((string) $prod->xProd, 0, 60),
                    'ncm' => preg_replace('/[^0-9]/', '', (string) $prod->NCM),
                    'cod_barras' => $this->codigoBarras((string) $prod->cEAN),
                ];
            }
        }

        $peso = $this->pesoBruto($inf);

        return [
            'chave' => substr((string) $inf->attributes()->Id, 3, 44),
            'numero' => (string) $inf->ide->nNF,
            'emitente' => [
                'documento' => $this->somenteNumeros($inf->emit->CNPJ ?: $inf->emit->CPF),
                'nome' => (string) $inf->emit->xNome,
                'codigo_municipio' => (string) $inf->emit->enderEmit->cMun,
                'municipio' => (string) $inf->emit->enderEmit->xMun,
                'uf' => (string) $inf->emit->enderEmit->UF,
            ],
            'destinatario' => [
                'documento' => $dest != null ? $this->somenteNumeros($dest->CNPJ ?: $dest->CPF) : '',
                'nome' => $dest != null ? (string) $dest->xNome : '',
                'codigo_municipio' => $enderDest != null ? (string) $enderDest->cMun : '',
                'municipio' => $enderDest != null ? (string) $enderDest->xMun : '',
                'uf' => $enderDest != null ? (string) $enderDest->UF : '',
            ],
            'valor' => $valor,
            'quantidade' => $peso['peso'],
            'quantidade_origem' => $peso['origem'],
            'quantidade_itens' => $quantidadeItens,
            'produto_predominante' => $produtoPredominante ?: [
                'valor' => 0,
                'nome' => '',
                'ncm' => '',
                'cod_barras' => '',
            ],
        ];
    }

    /**
     * Monta o preenchimento final: campos do formulário, municípios, percurso e linhas de descarregamento.
     */
    private function montar(array $notas): array
    {
        if (count($notas) == 0) {
            throw new InvalidArgumentException('Nenhuma NF-e válida foi enviada.');
        }

        $empresa = Empresa::findOrFail(request()->empresa_id);
        $documentoEmpresa = $this->somenteNumeros($empresa->cpf_cnpj);
        $ultimaMdfe = $this->ultimaMdfe($empresa->id);
        $chavesUtilizadas = $this->chavesJaUtilizadas($empresa->id);

        $chaves = [];
        $carregamentos = [];
        $descarregamentos = [];
        $valor = 0.0;
        $quantidade = 0.0;
        $notasDaEmpresa = 0;
        $produtoPredominante = null;
        $ufFim = '';
        $documentoContratante = '';

        foreach ($notas as $nota) {
            if (in_array($nota['chave'], $chaves)) {
                $this->avisar("A NF-e {$nota['numero']} foi enviada mais de uma vez e considerada apenas uma vez.");
                continue;
            }

            $chaves[] = $nota['chave'];
            $valor += $nota['valor'];
            $quantidade += $nota['quantidade'];
            $ufFim = $nota['destinatario']['uf'] ?: $ufFim;

            if ($nota['emitente']['documento'] == $documentoEmpresa) {
                $notasDaEmpresa++;
            }

            if (in_array($nota['chave'], $chavesUtilizadas)) {
                $this->avisar("A NF-e {$nota['numero']} já está vinculada a outra MDF-e. Confira antes de emitir.");
            }

            if ($produtoPredominante === null || $nota['produto_predominante']['valor'] > $produtoPredominante['valor']) {
                $produtoPredominante = $nota['produto_predominante'];
            }

            // Contratante do frete: emitente da nota quando não é a própria transportadora
            if ($documentoContratante == '') {
                $documentoContratante = $nota['emitente']['documento'] != $documentoEmpresa
                    ? $nota['emitente']['documento']
                    : $nota['destinatario']['documento'];
            }

            $carregamento = $this->buscaCidade(
                $nota['emitente']['codigo_municipio'],
                $nota['emitente']['uf'],
                $nota['emitente']['municipio']
            );

            if ($carregamento && !in_array($carregamento->id, $carregamentos)) {
                $carregamentos[] = $carregamento->id;
            }

            $descarregamento = $this->buscaCidade(
                $nota['destinatario']['codigo_municipio'],
                $nota['destinatario']['uf'],
                $nota['destinatario']['municipio']
            );

            if (!$descarregamento) {
                $this->avisar("O município de descarregamento {$nota['destinatario']['municipio']}/{$nota['destinatario']['uf']} não está cadastrado. Selecione manualmente no formulário.");
                continue;
            }

            $descarregamentos[] = [
                'chave_nfe' => $nota['chave'],
                'numero' => $nota['numero'],
                'tipo_doc' => 'NFE',
                'valor' => $this->numeroBr($nota['valor'], 2),
                'tp_und_transp' => 1,
                'quantidade_rateio' => $this->numeroBr($nota['quantidade'], 2),
                'quantidade_rateio_carga' => '',
                'chave_cte' => '',
                'municipio_descarregamento' => $descarregamento->id,
                'cidade_info' => $descarregamento->info,
                'cidade_nome' => $descarregamento->nome,
                'cidade_uf' => $descarregamento->uf,
                'lacres_transporte' => [],
                'lacres_unidade' => [],
            ];
        }

        if (count($carregamentos) == 0 && $empresa->cidade_id) {
            $carregamentos[] = $empresa->cidade_id;
            $this->avisar('Não identificamos o município de carregamento na NF-e. Usamos o município do emitente da MDF-e.');
        }

        if ($valor <= 0) {
            $this->avisar('Não conseguimos ler o valor total da NF-e. Informe o valor da carga manualmente.');
        }

        $usouSomaDeItens = false;
        foreach ($notas as $nota) {
            if ($nota['quantidade_origem'] == 'quantidade_itens') {
                $usouSomaDeItens = true;
            }
        }

        if ($quantidade <= 0) {
            $this->avisar('Não encontramos peso nos volumes da NF-e. Informe a quantidade da carga manualmente.');
        } elseif ($usouSomaDeItens) {
            $this->avisar('A NF-e não informa o peso dos volumes. Usamos a soma das quantidades dos itens — confira a quantidade da carga.');
        }

        // Emitente é a própria transportadora quando a nota é dela: carga própria
        $tpEmit = $ultimaMdfe->tp_emit ?? '1';
        if ($notasDaEmpresa == count($notas)) {
            $tpEmit = '2';
        } elseif ($notasDaEmpresa == 0) {
            $tpEmit = '1';
        }

        $campos = [
            'uf_inicio' => $empresa->cidade->uf ?? '',
            'uf_fim' => $ufFim,
            'data_inicio_viagem' => date('Y-m-d'),
            'carga_posterior' => $ultimaMdfe->carga_posterior ?? 0,
            'tp_emit' => $tpEmit,
            'tp_transp' => $ultimaMdfe->tp_transp ?? '1',
            'tipo_modal' => $ultimaMdfe->tipo_modal ?? '1',
            'cnpj_contratante' => $documentoContratante ? __setMask($documentoContratante) : '',
            'quantidade_carga' => $this->numeroBr($quantidade, 3),
            'unidade_medida' => $ultimaMdfe->unidade_medida ?? 'KG',
            'valor_carga' => __moeda($valor),
            'lac_rodo' => $ultimaMdfe->lac_rodo ?? '0',
            'produto_pred_nome' => $produtoPredominante['nome'] ?? '',
            'produto_pred_ncm' => $produtoPredominante['ncm'] ?? '',
            'produto_pred_cod_barras' => $produtoPredominante['cod_barras'] ?? '',
            'tp_carga' => $ultimaMdfe->tp_carga ?? '',
            'resp_seguro' => $ultimaMdfe->resp_seguro ?? '',
            'seguradora_nome' => $ultimaMdfe->seguradora_nome ?? '',
            'seguradora_cnpj' => $ultimaMdfe->seguradora_cnpj ?? '',
            'numero_apolice' => $ultimaMdfe->numero_apolice ?? '',
            'numero_averbacao' => $ultimaMdfe->numero_averbacao ?? '',
        ];

        if ($campos['produto_pred_nome'] != '' && $campos['tp_carga'] == '') {
            $campos['tp_carga'] = '05'; // Carga Geral
        }

        $percurso = $this->percursoSugerido($ultimaMdfe, $campos['uf_inicio'], $ufFim);

        if ($ultimaMdfe != null) {
            $this->avisar('Tipo do transportador, seguro e percurso foram preenchidos com base na última MDF-e emitida. Revise antes de salvar.');
        }

        return [
            'notas' => count($chaves),
            'chaves' => $chaves,
            'campos' => $campos,
            'municipios_carregamento' => $carregamentos,
            'percurso' => $percurso,
            'descarregamentos' => $descarregamentos,
            'produto_predominante' => $produtoPredominante,
            'avisos' => $this->avisos,
        ];
    }

    /**
     * Peso bruto dos volumes da nota, com alternativas quando não informado.
     */
    private function pesoBruto($inf): array
    {
        $pesoBruto = 0.0;
        $pesoLiquido = 0.0;

        foreach ($inf->transp->vol as $volume) {
            $pesoBruto += (float) $volume->pesoB;
            $pesoLiquido += (float) $volume->pesoL;
        }

        if ($pesoBruto > 0) {
            return ['peso' => $pesoBruto, 'origem' => 'peso_bruto'];
        }

        if ($pesoLiquido > 0) {
            return ['peso' => $pesoLiquido, 'origem' => 'peso_liquido'];
        }

        $quantidade = 0.0;
        foreach ($inf->det as $det) {
            $quantidade += (float) $det->prod->qCom;
        }

        return ['peso' => $quantidade, 'origem' => 'quantidade_itens'];
    }

    /**
     * Procura a cidade pelo código do IBGE e, se não achar, por nome e UF.
     */
    private function buscaCidade($codigoIbge, $uf = null, $nome = null)
    {
        if ($codigoIbge) {
            $cidade = Cidade::getCidadeCod($codigoIbge);
            if ($cidade) {
                return $cidade;
            }
        }

        if (!$nome) {
            return null;
        }

        return Cidade::where('uf', $uf)
            ->where('nome', $nome)
            ->first();
    }

    /**
     * Última MDF-e da empresa, usada como referência de defaults.
     * É um preenchimento de apoio: se a consulta falhar, a emissão segue com o que veio do XML.
     */
    private function ultimaMdfe($empresaId): ?Mdfe
    {
        try {
            return Mdfe::where('empresa_id', $empresaId)
                ->whereNotNull('veiculo_tracao_id')
                ->orderBy('id', 'desc')
                ->first();
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Chaves de NF-e que já estão em outra MDF-e ativa da mesma empresa.
     * Serve apenas para alertar o usuário: se a consulta falhar, seguimos sem o aviso.
     */
    private function chavesJaUtilizadas($empresaId): array
    {
        try {
            return InfoDescarga::query()
                ->join('n_fe_descargas as nd', 'nd.info_id', '=', 'info_descargas.id')
                ->join('mdfes', 'mdfes.id', '=', 'info_descargas.mdfe_id')
                ->where('mdfes.empresa_id', $empresaId)
                ->where('mdfes.estado_emissao', '!=', 'cancelado')
                ->pluck('nd.chave')
                ->all();
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Reaproveita as UFs de percurso da última MDF-e com o mesmo trajeto.
     */
    private function percursoSugerido(?Mdfe $ultimaMdfe, $ufInicio, $ufFim): array
    {
        if ($ultimaMdfe == null || $ufInicio == '' || $ufFim == '') {
            return [];
        }

        if ($ultimaMdfe->uf_inicio != $ufInicio || $ultimaMdfe->uf_fim != $ufFim) {
            return [];
        }

        return $ultimaMdfe->percurso()->pluck('uf')->all();
    }

    private function codigoBarras($codigo): string
    {
        $codigo = trim((string) $codigo);

        if ($codigo == '' || strtoupper($codigo) == 'SEM GTIN') {
            return '';
        }

        return preg_replace('/[^0-9]/', '', $codigo);
    }

    private function somenteNumeros($valor): string
    {
        return preg_replace('/[^0-9]/', '', (string) $valor);
    }

    /**
     * Número no formato usado pelas máscaras pt-BR (ex.: 12500.5 -> "12.500,500").
     */
    private function numeroBr($valor, $decimais): string
    {
        return number_format((float) $valor, $decimais, ',', '.');
    }

    private function avisar(string $mensagem): void
    {
        $this->avisos[] = $mensagem;
    }
}
