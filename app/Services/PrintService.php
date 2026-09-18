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
     * Gera texto formatado com comandos ESC/POS basicos para impressao direta
     * 
     * @param string $texto Texto puro
     * @param bool $cortarPaper Se deve cortar o papel no final
     * @return string Texto com comandos ESC/POS
     */
    public function formatarEscPos($texto, $cortarPaper = true)
    {
        // Inicializa a impressora (reset)
        $comando = "\x1B\x40"; // ESC @ - Inicializar impressora
        
        // Configura charset para UTF-8 (CP850)
        $comando .= "\x1B\x74\x02"; // ESC t 2 - Code page 850
        
        // Centalizar (opcional - pode ser removido)
        // $comando .= "\x1B\x61\x01"; // ESC a 1 - Centralizar
        
        $comando .= $texto;
        
        if ($cortarPaper) {
            // Avanca papel e corta
            $comando .= "\n\n";
            $comando .= "\x1D\x56\x41\x10"; // GS V A 16 - Corte parcial
        }
        
        return $comando;
    }
}
