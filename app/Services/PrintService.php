<?php

namespace App\Services;

use App\Models\ConfigGeral;
use Illuminate\Support\Facades\Log;

class PrintService
{
    /**
     * Imprime texto na impressora termica via socket TCP/IP
     * 
     * @param string $texto Texto formatado para impressao (pode conter comandos ESC/POS)
     * @param ConfigGeral|null $config Configuracao geral (se null, busca automaticamente)
     * @return array ['success' => bool, 'message' => string]
     */
    public function imprimir($texto, $config = null)
    {
        if ($config === null) {
            $config = ConfigGeral::where('empresa_id', request()->empresa_id)->first();
        }

        if (!$config || !$config->isPrinterConfigured()) {
            return [
                'success' => false,
                'message' => 'Impressora termica nao configurada. Configure em Configuracoes Gerais.'
            ];
        }

        $ip = $config->printer_ip;
        $porta = $config->printer_porta;
        $timeout = 5; // segundos

        try {
            // Abre conexao TCP/IP com a impressora
            $socket = @fsockopen($ip, $porta, $errno, $errstr, $timeout);

            if (!$socket) {
                Log::error("PrintService: Falha ao conectar na impressora {$ip}:{$porta} - {$errstr} (errno: {$errno})");
                return [
                    'success' => false,
                    'message' => "Nao foi possivel conectar na impressora {$ip}:{$porta}. Verifique se ela esta ligada e na mesma rede."
                ];
            }

            // Envia os dados para a impressora
            fwrite($socket, $texto);
            fclose($socket);

            Log::info("PrintService: Impressao enviada para {$ip}:{$porta} com sucesso");
            return [
                'success' => true,
                'message' => 'Impressao enviada com sucesso!'
            ];

        } catch (\Exception $e) {
            Log::error("PrintService: Erro ao imprimir - " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro ao enviar para impressora: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Testa a conexao com a impressora
     * 
     * @param string $ip IP da impressora
     * @param int $porta Porta da impressora
     * @return array ['success' => bool, 'message' => string]
     */
    public function testarConexao($ip, $porta)
    {
        $timeout = 3;

        try {
            $socket = @fsockopen($ip, $porta, $errno, $errstr, $timeout);

            if (!$socket) {
                return [
                    'success' => false,
                    'message' => "Falha ao conectar em {$ip}:{$porta} - {$errstr}"
                ];
            }

            // Envia comando ESC/POS simples de teste (imprime linha de teste)
            $comando = "\n" . str_repeat("-", 32) . "\n";
            $comando .= "    TESTE DE IMPRESSAO    \n";
            $comando .= "   " . date('d/m/Y H:i:s') . "   \n";
            $comando .= str_repeat("-", 32) . "\n\n";
            $comando .= "\x1D\x56\x41\x10"; // Corte parcial

            fwrite($socket, $comando);
            fclose($socket);

            return [
                'success' => true,
                'message' => 'Conexao OK! Teste enviado para a impressora.'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao testar conexao: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Converte HTML simples para texto formatado para impressora termica
     * Remove tags HTML e mantem a formatacao textual
     * 
     * @param string $html HTML do cupom
     * @return string Texto formatado para impressora
     */
    public function htmlToText($html)
    {
        // Remove script e style
        $html = preg_replace('#<script[^>]*>.*?</script>#is', '', $html);
        $html = preg_replace('#<style[^>]*>.*?</style>#is', '', $html);
        
        // Substitui <br> e <br/> por quebra de linha
        $html = preg_replace('#<br\s*/?>#i', "\n", $html);
        
        // Substitui </tr> e </p> por quebra de linha
        $html = preg_replace('#</(tr|p|div|h[1-6])>#i', "\n", $html);
        
        // Remove todas as tags HTML restantes
        $html = strip_tags($html);
        
        // Decodifica entidades HTML
        $html = html_entity_decode($html, ENT_QUOTES, 'UTF-8');
        
        // Remove espacos extras por linha
        $linhas = explode("\n", $html);
        $linhas = array_map('rtrim', $linhas);
        
        // Remove linhas vazias duplas
        $resultado = [];
        $ultimaVazia = false;
        foreach ($linhas as $linha) {
            $vazia = trim($linha) === '';
            if ($vazia && $ultimaVazia) {
                continue;
            }
            $resultado[] = $linha;
            $ultimaVazia = $vazia;
        }
        
        return implode("\n", $resultado);
    }

    /**
     * Imprime DANFE NFC-e fiscal diretamente na impressora termica via ESC/POS nativo.
     * Gera texto formatado com colunas corretas para 80mm (48 cols) ou 58mm (32 cols).
     * Se a impressora nao estiver configurada, retorna use_pdf=true para o
     * frontend abrir automaticamente o DANFE PDF.
     *
     * @param \App\Models\Nfce $nfce Model da NFCe com itens, cliente e fatura carregados
     * @param ConfigGeral|null $config Configuracao geral
     * @return array ['success' => bool, 'use_pdf' => bool, 'message' => string]
     */
    public function imprimirNfce($nfce, $config = null)
    {
        if ($config === null) {
            $config = ConfigGeral::where('empresa_id', $nfce->empresa_id)->first();
        }

        if (!$config || !$config->isPrinterConfigured()) {
            return [
                'success' => false,
                'use_pdf'  => true,
                'message'  => 'Impressora termica nao configurada. Abrindo DANFE PDF...',
            ];
        }

        try {
            $empresa = \App\Models\Empresa::with('cidade')->where('id', $nfce->empresa_id)->first();

            // Largura em colunas: 80mm = 48 cols, 58mm = 32 cols
            $cols     = ($config->printer_largura == '58') ? 32 : 48;
            $labelCol = $cols - 14;
            $valueCol = 14;

            // Extrai QR Code e URL de consulta do XML
            $qrCodeUrl = null;
            $urlChave  = null;
            $xmlPath   = public_path('xml_nfce/') . $nfce->chave . '.xml';
            if (!file_exists($xmlPath)) {
                $xmlPath = public_path('xml_nfce_contigencia/') . $nfce->chave . '.xml';
            }
            if (file_exists($xmlPath)) {
                try {
                    $xml = simplexml_load_string(file_get_contents($xmlPath));
                    if ($xml && isset($xml->infNFeSupl)) {
                        $qrCodeUrl = (string) $xml->infNFeSupl->qrCode;
                        $urlChave  = (string) $xml->infNFeSupl->urlChave;
                    } elseif ($xml && isset($xml->NFe->infNFeSupl)) {
                        $qrCodeUrl = (string) $xml->NFe->infNFeSupl->qrCode;
                        $urlChave  = (string) $xml->NFe->infNFeSupl->urlChave;
                    }
                } catch (\Exception $xmlEx) {
                    Log::warning('PrintService: Nao foi possivel ler XML - ' . $xmlEx->getMessage());
                }
            }

            // ── Monta payload ESC/POS ─────────────────────────────────────
            $cmd  = "\x1B\x40";     // ESC @ - Reset/Inicializar
            $cmd .= "\x1B\x74\x10"; // Code page WPC1252

            // ── 1. CABECALHO DO EMITENTE ──────────────────────────────────
            // Ordem do modelo original: nome fantasia (negrito), razao social, CNPJ IE, rua+numero, bairro, cidade-UF
            $cmd .= "\x1B\x61\x01"; // Centralizar
            $cmd .= "\x1B\x45\x01"; // Negrito ON
            $nomeFantasia = iconv('UTF-8', 'CP850//TRANSLIT//IGNORE', mb_strtoupper($empresa->nome_fantasia ?: $empresa->nome));
            $cmd .= $this->centerText($nomeFantasia, $cols) . "\n";
            $cmd .= "\x1B\x45\x00"; // Negrito OFF

            if ($empresa->nome_fantasia && $empresa->nome) {
                $nomeRazao = iconv('UTF-8', 'CP850//TRANSLIT//IGNORE', mb_strtoupper($empresa->nome));
                $cmd .= $this->centerText($nomeRazao, $cols) . "\n";
            }

            $cnpj = 'CNPJ: ' . $this->formatCnpj($empresa->cpf_cnpj ?? '');
            if ($empresa->ie) {
                $cnpj .= ' IE: ' . $empresa->ie;
            }
            $cmd .= $this->centerText($cnpj, $cols) . "\n";

            $rua = iconv('UTF-8', 'CP850//TRANSLIT//IGNORE', ($empresa->rua ?? '') . ', ' . ($empresa->numero ?? ''));
            $cmd .= $this->centerText($rua, $cols) . "\n";

            if ($empresa->bairro) {
                $bairro = iconv('UTF-8', 'CP850//TRANSLIT//IGNORE', mb_strtoupper($empresa->bairro));
                $cmd .= $this->centerText($bairro, $cols) . "\n";
            }

            $cidade = '';
            if ($empresa->cidade) {
                $cidade = iconv('UTF-8', 'CP850//TRANSLIT//IGNORE', $empresa->cidade->nome) . '-' . $empresa->cidade->uf;
            }
            if ($cidade) {
                $cmd .= $this->centerText($cidade, $cols) . "\n";
            }

            if ($empresa->celular || $empresa->telefone) {
                $cmd .= $this->centerText('Fone: ' . ($empresa->celular ?: $empresa->telefone), $cols) . "\n";
            }

            $cmd .= "\x1B\x61\x00"; // Esquerda
            $cmd .= str_repeat('-', $cols) . "\n";

            // ── 2. TITULO DO DOCUMENTO ────────────────────────────────────
            // Igual ao modelo original: texto completo centralizado (sem destaque "DANFE NFC-e")
            $cmd .= "\x1B\x61\x01"; // Centralizar
            $cmd .= $this->centerText('Documento Auxiliar da Nota Fiscal de Consumidor Eletronica', $cols) . "\n";
            $cmd .= $this->centerText('Nao permite aproveitamento de credito de ICMS', $cols) . "\n";
            $cmd .= "\x1B\x61\x00"; // Esquerda
            $cmd .= str_repeat('-', $cols) . "\n";

            // ── 3. CABECALHO DA TABELA DE ITENS ──────────────────────────
            // Igual ao modelo original: Codigo | Descricao | Qtde | UN | Vl Unit | Vl Total
            if ($cols >= 48) {
                $cCod   = 6;   // Codigo
                $cDesc  = 16;  // Descricao
                $cQtde  = 4;   // Qtde
                $cUN    = 3;   // UN
                $cUnit  = 9;   // Vl Unit
                $cTotal = 10;  // Vl Total  (total = 48)
            } else {
                $cCod   = 4;
                $cDesc  = 9;
                $cQtde  = 3;
                $cUN    = 2;
                $cUnit  = 7;
                $cTotal = 7;   // total = 32
            }

            $cmd .= "\x1B\x45\x01"; // Negrito ON
            $cmd .= $this->padR('Codigo', $cCod)
                  . $this->padR('Descricao', $cDesc)
                  . $this->padL('Qtde', $cQtde)
                  . $this->padL('UN', $cUN)
                  . $this->padL('VlUnit', $cUnit)
                  . $this->padL('VlTotal', $cTotal) . "\n";
            $cmd .= "\x1B\x45\x00"; // Negrito OFF
            $cmd .= str_repeat('-', $cols) . "\n";

            // ── 4. ITENS ──────────────────────────────────────────────────
            // Cada item em 1 linha (ou 2 se nome longo) com todas as colunas
            $totalItens = 0;
            foreach ($nfce->itens as $item) {
                $totalItens++;
                $nome    = $item->produto ? $item->produto->nome : ($item->descricao ?? 'Item ' . $item->produto_id);
                $unidade = $item->produto ? ($item->produto->unidade ?? 'UN') : 'UN';
                $nomeConv = iconv('UTF-8', 'CP850//TRANSLIT//IGNORE', mb_strtoupper($nome));

                $codStr   = $this->padR(mb_substr((string)$item->produto_id, 0, $cCod - 1), $cCod);
                $qtdeStr  = $this->padL(number_format($item->quantidade, 0, ',', '.'), $cQtde);
                $unStr    = $this->padL($unidade, $cUN);
                $unitStr  = $this->padL(number_format($item->valor_unitario, 2, ',', '.'), $cUnit);
                $totalStr = $this->padL(number_format($item->sub_total, 2, ',', '.'), $cTotal);

                $nomeT = $this->truncate($nomeConv, $cDesc);
                $cmd .= $codStr
                      . $this->padR($nomeT, $cDesc)
                      . $qtdeStr . $unStr . $unitStr . $totalStr . "\n";

                // Se nome longo, exibe o resto na proxima linha
                if (mb_strlen($nomeConv) > $cDesc) {
                    $resto = trim(mb_substr($nomeConv, $cDesc - 1));
                    if ($resto) {
                        $cmd .= str_repeat(' ', $cCod) . $this->truncate($resto, $cDesc) . "\n";
                    }
                }
            }

            $cmd .= str_repeat('-', $cols) . "\n";


            // ── 5. TOTAIS ─────────────────────────────────────────────────
            // Igual ao modelo original: sempre mostra Valor Total, Desconto, Frete e Valor a Pagar
            $valorTotalR = $nfce->total + ($nfce->desconto ?? 0) - ($nfce->acrescimo ?? 0);

            $cmd .= $this->padR('Qtde total de itens', $labelCol)
                  . $this->padL((string)$totalItens, $valueCol) . "\n";
            $cmd .= $this->padR('Valor Total R$', $labelCol)
                  . $this->padL(number_format($valorTotalR, 2, ',', '.'), $valueCol) . "\n";
            $cmd .= $this->padR('Desconto R$', $labelCol)
                  . $this->padL(number_format($nfce->desconto ?? 0, 2, ',', '.'), $valueCol) . "\n";
            $cmd .= $this->padR('Frete R$', $labelCol)
                  . $this->padL('0,00', $valueCol) . "\n";

            $cmd .= str_repeat('-', $cols) . "\n";
            $cmd .= "\x1B\x45\x01"; // Negrito ON
            $cmd .= $this->padR('Valor a Pagar R$', $labelCol)
                  . $this->padL(number_format($nfce->total, 2, ',', '.'), $valueCol) . "\n";
            $cmd .= "\x1B\x45\x00"; // Negrito OFF
            $cmd .= str_repeat('-', $cols) . "\n";


            // ── 6. FORMAS DE PAGAMENTO ────────────────────────────────────
            // Igual ao modelo original: sempre mostra Troco R$
            $cmd .= "\x1B\x45\x01"; // Negrito ON
            $cmd .= $this->padR('FORMA PAGAMENTO', $labelCol)
                  . $this->padL('VALOR PAGO R$', $valueCol) . "\n";
            $cmd .= "\x1B\x45\x00"; // Negrito OFF

            if (sizeof($nfce->fatura) > 0) {
                foreach ($nfce->fatura as $f) {
                    $tipoPag = \App\Models\Nfce::getTipoPagamento($f->tipo_pagamento) ?? $f->tipo_pagamento;
                    $tipoPag = iconv('UTF-8', 'CP850//TRANSLIT//IGNORE', $tipoPag);
                    $cmd .= $this->padR($tipoPag, $labelCol)
                          . $this->padL(number_format($f->valor, 2, ',', '.'), $valueCol) . "\n";
                }
            } else {
                $tipoPag  = \App\Models\Nfce::getTipoPagamento($nfce->tipo_pagamento) ?? 'Dinheiro';
                $tipoPag  = iconv('UTF-8', 'CP850//TRANSLIT//IGNORE', $tipoPag);
                $valorPag = ($nfce->dinheiro_recebido ?? 0) > 0 ? $nfce->dinheiro_recebido : $nfce->total;
                $cmd .= $this->padR($tipoPag, $labelCol)
                      . $this->padL(number_format($valorPag, 2, ',', '.'), $valueCol) . "\n";
            }

            // Sempre mostra Troco R$ (mesmo zerado - igual ao modelo original)
            $cmd .= $this->padR('Troco R$', $labelCol)
                  . $this->padL(number_format($nfce->troco ?? 0, 2, ',', '.'), $valueCol) . "\n";

            $cmd .= str_repeat('-', $cols) . "\n";


            // ── 7. DADOS DA EMISSAO ───────────────────────────────────────
            $cmd .= "\x1B\x61\x01"; // Centralizar
            $nfceInfo = 'NFC-e No. ' . str_pad($nfce->numero, 9, '0', STR_PAD_LEFT)
                      . '  Serie ' . str_pad($nfce->numero_serie ?: '1', 3, '0', STR_PAD_LEFT);
            $cmd .= $this->centerText($nfceInfo, $cols) . "\n";

            $dataEmissao = $nfce->data_emissao
                ? \Carbon\Carbon::parse($nfce->data_emissao)->format('d/m/Y H:i:s')
                : date('d/m/Y H:i:s');
            $cmd .= $this->centerText('Emissao: ' . $dataEmissao, $cols) . "\n";

            if ($nfce->recibo) {
                $cmd .= $this->centerText('Protocolo: ' . $nfce->recibo, $cols) . "\n";
            }

            $ambiente = ($nfce->ambiente == 2) ? 'HOMOLOGACAO - SEM VALOR FISCAL' : 'PRODUCAO';
            $cmd .= $this->centerText('Ambiente: ' . $ambiente, $cols) . "\n";
            $cmd .= "\x1B\x61\x00"; // Esquerda
            $cmd .= str_repeat('-', $cols) . "\n";

            // ── 8. CHAVE DE ACESSO ────────────────────────────────────────
            $cmd .= "\x1B\x61\x01"; // Centralizar
            $cmd .= "\x1B\x45\x01"; // Negrito ON
            $cmd .= $this->centerText('CHAVE DE ACESSO', $cols) . "\n";
            $cmd .= "\x1B\x45\x00"; // Negrito OFF

            $chaveLimpa     = preg_replace('/[^0-9]/', '', $nfce->chave ?? '');
            $chaveFormatada = trim(chunk_split($chaveLimpa, 4, ' '));
            foreach (str_split($chaveFormatada, $cols) as $parte) {
                $cmd .= $this->centerText(trim($parte), $cols) . "\n";
            }

            $cmd .= "\x1B\x61\x00"; // Esquerda
            $cmd .= str_repeat('-', $cols) . "\n";

            // ── 9. CONSUMIDOR ─────────────────────────────────────────────
            $cmd .= "\x1B\x61\x01"; // Centralizar
            $cmd .= "\x1B\x45\x01"; // Negrito ON
            $cmd .= $this->centerText('CONSUMIDOR', $cols) . "\n";
            $cmd .= "\x1B\x45\x00"; // Negrito OFF

            if ($nfce->cliente || $nfce->cliente_cpf_cnpj || $nfce->cliente_nome) {
                $cpfCnpj = $nfce->cliente
                    ? ($nfce->cliente->cpf_cnpj ? 'CPF/CNPJ: ' . $nfce->cliente->cpf_cnpj : '')
                    : ($nfce->cliente_cpf_cnpj ? 'CPF/CNPJ: ' . $nfce->cliente_cpf_cnpj : '');
                if ($cpfCnpj) {
                    $cmd .= $this->centerText($cpfCnpj, $cols) . "\n";
                }
                $nomeConsumidor = $nfce->cliente
                    ? iconv('UTF-8', 'CP850//TRANSLIT//IGNORE', mb_strtoupper($nfce->cliente->razao_social ?? ''))
                    : iconv('UTF-8', 'CP850//TRANSLIT//IGNORE', mb_strtoupper($nfce->cliente_nome ?? ''));
                if ($nomeConsumidor) {
                    $cmd .= $this->centerText($nomeConsumidor, $cols) . "\n";
                }
            } else {
                $cmd .= $this->centerText('CONSUMIDOR NAO IDENTIFICADO', $cols) . "\n";
            }

            $cmd .= "\x1B\x61\x00"; // Esquerda
            $cmd .= str_repeat('-', $cols) . "\n";

            // ── 10. CONSULTA SEFAZ ────────────────────────────────────────
            $cmd .= "\x1B\x61\x01"; // Centralizar
            $cmd .= $this->centerText('Consulte pela Chave de Acesso em:', $cols) . "\n";
            $uf = $empresa->cidade->uf ?? 'ce';
            $urlConsulta = $urlChave ?: ('http://www.sefaz.' . strtolower($uf) . '.gov.br/nfce/consulta');
            foreach (str_split($urlConsulta, $cols) as $parte) {
                $cmd .= $this->centerText($parte, $cols) . "\n";
            }

            // ── 11. QR CODE NATIVO ESC/POS ────────────────────────────────
            if (!empty($qrCodeUrl)) {
                $cmd .= "\n";
                $cmd .= $this->centerText('* Consulta via QR Code *', $cols) . "\n";
                $cmd .= $this->gerarQrCodeEscPos($qrCodeUrl);
            }

            $cmd .= "\x1B\x61\x00"; // Esquerda
            $cmd .= str_repeat('-', $cols) . "\n";

            // ── 12. RODAPE ────────────────────────────────────────────────
            $cmd .= "\x1B\x61\x01"; // Centralizar
            $cmd .= "\x1B\x45\x01"; // Negrito ON
            $cmd .= $this->centerText('OBRIGADO PELA PREFERENCIA!', $cols) . "\n";
            $cmd .= "\x1B\x45\x00"; // Negrito OFF
            $cmd .= "\x1B\x61\x00"; // Esquerda

            // Avanco de papel e corte
            $cmd .= "\n\n\n";
            $cmd .= "\x1D\x56\x41\x10"; // Corte parcial

            return $this->imprimir($cmd, $config);

        } catch (\Exception $e) {
            Log::error('PrintService: Erro ao gerar DANFE NFCe termico - ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro ao gerar impressao do cupom fiscal: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Gera comando ESC/POS para impressão nativa de QR Code
     * 
     * @param string $url Conteúdo/URL do QR Code
     * @return string Comandos ESC/POS
     */
    public function gerarQrCodeEscPos($url)
    {
        if (empty($url)) {
            return "";
        }

        $comando = "\n";
        // Centraliza
        $comando .= "\x1B\x61\x01";
        
        // 1. Define o modelo do QR Code (Modelo 2)
        $comando .= "\x1D\x28\x6B\x04\x00\x31\x41\x32\x00";
        
        // 2. Define o tamanho do módulo (tamanho 4)
        $comando .= "\x1D\x28\x6B\x03\x00\x31\x43\x04";
        
        // 3. Define o nível de correção de erros (L = 48)
        $comando .= "\x1D\x28\x6B\x03\x00\x31\x45\x30";
        
        // 4. Armazena os dados do QR Code no buffer
        $len = strlen($url) + 3;
        $pL = $len % 256;
        $pH = intdiv($len, 256);
        $comando .= "\x1D\x28\x6B" . chr($pL) . chr($pH) . "\x31\x50\x30" . $url;
        
        // 5. Imprime o QR Code do buffer
        $comando .= "\x1D\x28\x6B\x03\x00\x31\x51\x30";
        
        // Restaura alinhamento para a esquerda
        $comando .= "\x1B\x61\x00";
        $comando .= "\n";
        
        return $comando;
    }

    /**
     * Gera texto formatado com comandos ESC/POS basicos para impressao direta
     * 
     * @param string $texto Texto puro
     * @param bool $cortarPaper Se deve cortar o papel no final
     * @return string Texto com comandos ESC/POS
     */
    public function formatarEscPos($texto, $cortarPaper = true)
    {
        $comando  = "\x1B\x40";     // ESC @ - Inicializar impressora
        $comando .= "\x1B\x74\x10"; // Code page WPC1252
        $comando .= $texto;

        if ($cortarPaper) {
            $comando .= "\n\n";
            $comando .= "\x1D\x56\x41\x10"; // Corte parcial
        }

        return $comando;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Helpers privados de formatacao de texto para ESC/POS
    // ─────────────────────────────────────────────────────────────────────────

    /** Centraliza texto em $cols colunas usando espacos */
    private function centerText(string $text, int $cols): string
    {
        $len = mb_strlen($text);
        if ($len >= $cols) {
            return mb_substr($text, 0, $cols);
        }
        $pad = intdiv($cols - $len, 2);
        return str_repeat(' ', $pad) . $text;
    }

    /** Alinha texto a esquerda preenchendo com espacos ate $width colunas */
    private function padR(string $text, int $width): string
    {
        $text = mb_substr($text, 0, $width);
        return $text . str_repeat(' ', $width - mb_strlen($text));
    }

    /** Alinha texto a direita preenchendo com espacos ate $width colunas */
    private function padL(string $text, int $width): string
    {
        $text = mb_substr($text, 0, $width);
        return str_repeat(' ', $width - mb_strlen($text)) . $text;
    }

    /** Trunca texto com reticencias se maior que $max caracteres */
    private function truncate(string $text, int $max): string
    {
        if (mb_strlen($text) <= $max) {
            return $text;
        }
        return mb_substr($text, 0, $max - 1) . '.';
    }

    /** Formata CNPJ com mascara XX.XXX.XXX/XXXX-XX */
    private function formatCnpj(string $cnpj): string
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        if (strlen($cnpj) === 14) {
            return substr($cnpj, 0, 2) . '.' . substr($cnpj, 2, 3) . '.'
                 . substr($cnpj, 5, 3) . '/' . substr($cnpj, 8, 4) . '-' . substr($cnpj, 12, 2);
        }
        return $cnpj;
    }
}
