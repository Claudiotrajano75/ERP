<?php

namespace App\Services;

/**
 * SpeedService — Utilitário de leitura e parsing de XMLs NFe/NFCe para geração do SPED Fiscal
 * 
 * Métodos esperados pelo SpedController:
 * - getXml($model, $path): Carrega e parseia XML de NFe/NFCe a partir do filesystem
 * - getEmitente($xml): Extrai dados do emitente
 * - getDestinatario($xml): Extrai dados do destinatário
 * - getIde($xml): Extrai dados do ide (identificação da nota)
 * - getChave($xml): Extrai a chave de acesso
 * - getTotal($xml): Extrai os totais (ICMSTot)
 * - getItemNfe($xml): Extrai os itens (produtos + impostos)
 */
class SpeedService
{
    protected $empresa;

    /**
     * @param mixed $empresa Modelo da empresa (Empresa ou ConfigNota)
     */
    public function __construct($empresa = null)
    {
        $this->empresa = $empresa;
    }

    /**
     * Carrega e parseia um XML do filesystem
     *
     * @param mixed $model Modelo que contém a chave (Nfe, Nfce, etc.)
     * @param string $path Subdiretório dentro de public_path (ex: 'xml_nfe/', 'xml_nfce/', 'xml_entrada/')
     * @return \stdClass|null XML parseado como objeto, ou null se não encontrado
     */
    public function getXml($model, $path)
    {
        $chave = null;

        if (is_string($model)) {
            $chave = $model;
        } elseif (is_object($model)) {
            $chave = $model->chave ?? null;
        }

        if (empty($chave)) {
            return null;
        }

        // Limpa caracteres não numéricos da chave
        $chave = preg_replace('/[^0-9]/', '', $chave);

        $filePath = public_path($path . $chave . '.xml');

        if (!file_exists($filePath)) {
            return null;
        }

        $content = file_get_contents($filePath);
        if ($content === false) {
            return null;
        }

        // Remove namespaces para facilitar o parse com SimpleXML
        $content = preg_replace('/<(\/?)[a-zA-Z0-9_]+:([a-zA-Z0-9_]+)/', '<$1$2', $content);

        $xml = simplexml_load_string($content);
        if ($xml === false) {
            return null;
        }

        return json_decode(json_encode($xml));
    }

    /**
     * Extrai o emitente do XML parseado
     *
     * @param \stdClass $xml XML parseado (retorno de getXml)
     * @return \stdClass|null Objeto com CNPJ, xNome, IE, enderEmit, etc.
     */
    public function getEmitente($xml)
    {
        $infNFe = $this->getInfNFe($xml);
        return $infNFe->emit ?? null;
    }

    /**
     * Extrai o destinatário do XML parseado
     *
     * @param \stdClass $xml XML parseado
     * @return \stdClass|null Objeto com CNPJ/CPF, xNome, IE, enderDest, etc.
     */
    public function getDestinatario($xml)
    {
        $infNFe = $this->getInfNFe($xml);
        return $infNFe->dest ?? null;
    }

    /**
     * Extrai os dados de identificação (ide) do XML parseado
     *
     * @param \stdClass $xml XML parseado
     * @return \stdClass|null Objeto com mod, serie, nNF, dhEmi, dhSaiEnt, tpNF, finNFe, etc.
     */
    public function getIde($xml)
    {
        $infNFe = $this->getInfNFe($xml);
        return $infNFe->ide ?? null;
    }

    /**
     * Extrai a chave de acesso do XML parseado
     *
     * @param \stdClass $xml XML parseado
     * @return string|null Chave de 44 dígitos
     */
    public function getChave($xml)
    {
        // Tenta extrair do protNFe (nota transmitida)
        if (isset($xml->protNFe->infProt->chNFe)) {
            return (string)$xml->protNFe->infProt->chNFe;
        }

        // Tenta extrair do atributo Id da infNFe
        $infNFe = $this->getInfNFe($xml);
        if ($infNFe && isset($infNFe->Id)) {
            $id = (string)$infNFe->Id;
            // Formato: "NFe3512345678901234567890123456789012345678901234567890"
            return substr($id, 3);
        }

        return null;
    }

    /**
     * Extrai os totais (ICMSTot) do XML parseado
     *
     * @param \stdClass $xml XML parseado
     * @return \stdClass|null Objeto com vBC, vICMS, vBCST, vST, vProd, vFrete, vSeg, vDesc, vIPI, vPIS, vCOFINS, vNF, vOutro, etc.
     */
    public function getTotal($xml)
    {
        $infNFe = $this->getInfNFe($xml);
        if (isset($infNFe->total->ICMSTot)) {
            return $infNFe->total->ICMSTot;
        }
        return null;
    }

    /**
     * Extrai os itens (det) do XML parseado
     *
     * @param \stdClass $xml XML parseado
     * @return array Lista de objetos com propriedades prod e imposto
     */
    public function getItemNfe($xml)
    {
        $infNFe = $this->getInfNFe($xml);
        $items = [];

        if (!isset($infNFe->det)) {
            return $items;
        }

        $dets = $infNFe->det;

        // det pode ser um objeto único ou um array de objetos
        if (is_array($dets)) {
            foreach ($dets as $det) {
                $items[] = $det;
            }
        } else {
            // Verifica se é um único det ou se tem múltiplos itens
            // Quando json_decode converte, det vira um objeto com chaves numéricas
            $detArr = (array)$dets;
            if (isset($detArr[0])) {
                foreach ($detArr as $d) {
                    $items[] = $d;
                }
            } else {
                $items[] = $dets;
            }
        }

        return $items;
    }

    /**
     * Obtém o nó infNFe do XML, que pode estar em diferentes níveis
     *
     * @param \stdClass $xml XML parseado
     * @return \stdClass|null
     */
    private function getInfNFe($xml)
    {
        if ($xml === null) return null;

        // Já está no infNFe
        if (isset($xml->infNFe)) {
            return $xml->infNFe;
        }

        // Dentro de NFe
        if (isset($xml->NFe) && isset($xml->NFe->infNFe)) {
            return $xml->NFe->infNFe;
        }

        // Retorna o próprio objeto se não encontrar infNFe (fallback)
        return $xml;
    }
}
