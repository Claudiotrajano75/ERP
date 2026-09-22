<?php
/**
 * ═══════════════════════════════════════════════════════════════════════
 * ERP - AGENTE LOCAL DE IMPRESSÃO TÉRMICA ESC/POS (WebSocket + HTTP)
 * ═══════════════════════════════════════════════════════════════════════
 * 
 * Permite que o ERP hospedado na nuvem (Hostinger, AWS, etc.) envie 
 * impressões fiscais NFC-e e não fiscais diretamente para impressoras 
 * térmicas na rede local (LAN: 192.168.x.x) com corte e formatação nativa.
 *
 * Suporta WebSocket (ws://127.0.0.1:9187) - sem bloqueios de Mixed Content
 * em páginas HTTPS na nuvem, e HTTP REST como fallback.
 *
 * Porta padrão do Agente: 9187
 */

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
set_time_limit(0);
date_default_timezone_set('America/Sao_Paulo');

// Se executado via servidor embutido do PHP (php -S)
if (php_sapi_name() === 'cli-server') {
    handleHttpRequestDirect();
    exit;
}

// Execução CLI padrão (Socket Server WebSocket + HTTP)
$host = '127.0.0.1';
$port = 9187;

$server = @stream_socket_server("tcp://{$host}:{$port}", $errno, $errstr);
if (!$server) {
    echo "\n[ERRO CRITICO] Nao foi possivel iniciar na porta {$port}: {$errstr} ({$errno})\n";
    echo "Verifique se a porta ja nao esta em uso por outra instancia.\n";
    exit(1);
}

echo "═══════════════════════════════════════════════════════════════════════\n";
echo "  ERP - AGENTE LOCAL DE IMPRESSAO TERMICA INICIADO COM SUCESSO!\n";
echo "═══════════════════════════════════════════════════════════════════════\n";
echo "  Porta WebSocket / HTTP: {$port}\n";
echo "  URL WebSocket:          ws://127.0.0.1:{$port}\n";
echo "  Status:                 Pronto para receber impressoes do ERP na nuvem!\n";
echo "═══════════════════════════════════════════════════════════════════════\n\n";

$clients = [];
$wsHandshakes = [];

while (true) {
    $read = array_merge([$server], $clients);
    $write = null;
    $except = null;

    if (@stream_select($read, $write, $except, 1) < 1) {
        continue;
    }

    // Nova conexão de entrada
    if (in_array($server, $read)) {
        $newSocket = @stream_socket_accept($server);
        if ($newSocket) {
            stream_set_blocking($newSocket, 0);
            $clients[(int)$newSocket] = $newSocket;
        }
        unset($read[array_search($server, $read)]);
    }

    // Processa dados dos clientes conectados
    foreach ($read as $client) {
        $clientId = (int)$client;
        $data = @fread($client, 65536);

        if ($data === false || strlen($data) === 0) {
            cleanupClient($clientId, $clients, $wsHandshakes);
            continue;
        }

        // Se o cliente ainda não fez o handshake de WebSocket
        if (!isset($wsHandshakes[$clientId])) {
            // Verifica se é um handshake de WebSocket
            if (strpos($data, 'Upgrade: websocket') !== false || strpos($data, 'Upgrade: WebSocket') !== false) {
                if (preg_match('/Sec-WebSocket-Key:\s*([^\r\n]+)/i', $data, $matches)) {
                    $key = trim($matches[1]);
                    $acceptKey = base64_encode(sha1($key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11', true));
                    
                    $upgradeHeader = "HTTP/1.1 101 Switching Protocols\r\n" .
                                     "Upgrade: websocket\r\n" .
                                     "Connection: Upgrade\r\n" .
                                     "Sec-WebSocket-Accept: {$acceptKey}\r\n\r\n";
                    @fwrite($client, $upgradeHeader);
                    $wsHandshakes[$clientId] = true;
                    echo "[" . date('H:i:s') . "] Cliente WebSocket conectado do ERP na Nuvem.\n";
                }
                continue;
            }

            // É uma requisição HTTP tradicional (CORS / REST)
            handleHttpRawRequest($client, $data);
            cleanupClient($clientId, $clients, $wsHandshakes);
            continue;
        }

        // Cliente WebSocket estabelecido - processa frames RFC 6455
        $frame = decodeWsFrame($data);
        if (!$frame) {
            continue;
        }

        if ($frame['opcode'] === 0x8) { // Close frame
            cleanupClient($clientId, $clients, $wsHandshakes);
            continue;
        }

        if ($frame['opcode'] === 0x9) { // Ping
            @fwrite($client, encodeWsFrame($frame['payload'], 0xA));
            continue;
        }

        if ($frame['opcode'] === 0x1) { // Text frame (JSON)
            $payload = json_decode($frame['payload'], true);
            if (!is_array($payload)) {
                $payload = [];
            }

            $action = $payload['action'] ?? 'print';
            $ip     = trim($payload['ip'] ?? '192.168.88.87');
            $porta  = (int)($payload['porta'] ?? 9100);
            $callId = $payload['call_id'] ?? null;

            if ($action === 'ping') {
                $res = ['action' => 'pong', 'timestamp' => date('Y-m-d H:i:s')];
                if ($callId) $res['call_id'] = $callId;
                @fwrite($client, encodeWsFrame(json_encode($res)));
                continue;
            }

            if ($action === 'test') {
                echo "[" . date('H:i:s') . "] Testando conexao com impressora em {$ip}:{$porta}...\n";
                $result = executarTesteImpressao($ip, $porta);
                if ($callId) $result['call_id'] = $callId;
                @fwrite($client, encodeWsFrame(json_encode($result)));
                continue;
            }

            if ($action === 'print') {
                $rawBase64 = $payload['data'] ?? '';
                echo "[" . date('H:i:s') . "] Imprimindo documento em {$ip}:{$porta}...\n";
                $result = executarImpressao($ip, $porta, $rawBase64);
                if ($callId) $result['call_id'] = $callId;
                @fwrite($client, encodeWsFrame(json_encode($result)));
                continue;
            }
        }
    }
}

function cleanupClient($clientId, &$clients, &$wsHandshakes) {
    if (isset($clients[$clientId])) {
        @fclose($clients[$clientId]);
        unset($clients[$clientId]);
    }
    unset($wsHandshakes[$clientId]);
}

/**
 * Decodifica um frame WebSocket RFC 6455
 */
function decodeWsFrame($data) {
    if (strlen($data) < 2) return null;
    $firstByte = ord($data[0]);
    $secondByte = ord($data[1]);
    $opcode = $firstByte & 0x0F;
    $isMasked = ($secondByte & 0x80) !== 0;
    $length = $secondByte & 0x7F;
    $offset = 2;

    if ($length === 126) {
        if (strlen($data) < 4) return null;
        $arr = unpack('nlen', substr($data, 2, 2));
        $length = $arr['len'];
        $offset = 4;
    } elseif ($length === 127) {
        if (strlen($data) < 10) return null;
        $arr = unpack('Jlen', substr($data, 2, 8));
        $length = $arr['len'];
        $offset = 10;
    }

    if ($isMasked) {
        if (strlen($data) < $offset + 4) return null;
        $mask = substr($data, $offset, 4);
        $offset += 4;
        $payloadRaw = substr($data, $offset, $length);
        $payload = '';
        $payloadLen = strlen($payloadRaw);
        for ($i = 0; $i < $payloadLen; $i++) {
            $payload .= chr(ord($payloadRaw[$i]) ^ ord($mask[$i % 4]));
        }
        return ['opcode' => $opcode, 'payload' => $payload];
    } else {
        $payload = substr($data, $offset, $length);
        return ['opcode' => $opcode, 'payload' => $payload];
    }
}

/**
 * Codifica um payload em frame WebSocket RFC 6455
 */
function encodeWsFrame($text, $opcode = 0x1) {
    $length = strlen($text);
    $header = chr(0x80 | ($opcode & 0x0F));
    if ($length <= 125) {
        $header .= chr($length);
    } elseif ($length <= 65535) {
        $header .= chr(126) . pack('n', $length);
    } else {
        $header .= chr(127) . pack('J', $length);
    }
    return $header . $text;
}

/**
 * Testa conexão e envia recibo de teste com corte
 */
function executarTesteImpressao($ip, $porta) {
    $socket = @fsockopen($ip, $porta, $errno, $errstr, 3);
    if (!$socket) {
        return [
            'success' => false,
            'message' => "Falha ao conectar na impressora {$ip}:{$porta} - {$errstr} ({$errno})"
        ];
    }

    $teste  = "\x1B\x40"; // Reset
    $teste .= "\x1B\x61\x01"; // Centro
    $teste .= "\x1B\x45\x01"; // Negrito
    $teste .= "\nTESTE DE IMPRESSAO\n";
    $teste .= "AGENTE LOCAL CONECTADO!\n";
    $teste .= "\x1B\x45\x00";
    $teste .= date('d/m/Y H:i:s') . "\n";
    $teste .= "IP: {$ip}:{$porta}\n";
    $teste .= str_repeat('-', 32) . "\n\n\n";
    $teste .= "\x1D\x56\x41\x10"; // Corte parcial

    @fwrite($socket, $teste);
    @fflush($socket);
    @fclose($socket);

    return [
        'success' => true,
        'message' => "Conexão com a impressora {$ip}:{$porta} realizada com sucesso! Teste impresso."
    ];
}

/**
 * Envia bytes ESC/POS brutos para a impressora
 */
function executarImpressao($ip, $porta, $dataBase64) {
    if (empty($ip) || empty($dataBase64)) {
        return [
            'success' => false,
            'message' => 'Parâmetros inválidos: IP e dados de impressão são obrigatórios.'
        ];
    }

    $rawBytes = base64_decode($dataBase64, true);
    if ($rawBytes === false) {
        $rawBytes = $dataBase64;
    }

    $socket = @fsockopen($ip, $porta, $errno, $errstr, 4);
    if (!$socket) {
        return [
            'success' => false,
            'message' => "Erro ao conectar com a impressora em {$ip}:{$porta} - {$errstr}"
        ];
    }

    @fwrite($socket, $rawBytes);
    @fflush($socket);
    @fclose($socket);

    return [
        'success' => true,
        'message' => 'Impresso com sucesso via Agente Local!'
    ];
}

/**
 * Trata requisição HTTP quando executado via socket server
 */
function handleHttpRawRequest($client, $rawHttp) {
    $corsHeaders = "Access-Control-Allow-Origin: *\r\n" .
                   "Access-Control-Allow-Methods: GET, POST, OPTIONS\r\n" .
                   "Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With\r\n" .
                   "Access-Control-Allow-Private-Network: true\r\n" .
                   "Content-Type: application/json; charset=utf-8\r\n";

    if (strpos($rawHttp, 'OPTIONS ') === 0) {
        $res = "HTTP/1.1 200 OK\r\n" . $corsHeaders . "Content-Length: 0\r\n\r\n";
        @fwrite($client, $res);
        return;
    }

    $lines = explode("\r\n", $rawHttp);
    $firstLine = $lines[0] ?? '';
    preg_match('/^(GET|POST)\s+([^\s]+)/', $firstLine, $m);
    $uri = $m[2] ?? '/';

    $bodyPos = strpos($rawHttp, "\r\n\r\n");
    $body = $bodyPos !== false ? substr($rawHttp, $bodyPos + 4) : '';
    $input = json_decode($body, true) ?? [];

    $responseJson = [];
    $statusCode = '200 OK';

    if (strpos($uri, '/test') !== false) {
        $ip = trim($input['ip'] ?? '192.168.88.87');
        $porta = (int)($input['porta'] ?? 9100);
        $responseJson = executarTesteImpressao($ip, $porta);
        if (!$responseJson['success']) $statusCode = '400 Bad Request';
    } elseif (strpos($uri, '/print') !== false) {
        $ip = trim($input['ip'] ?? '');
        $porta = (int)($input['porta'] ?? 9100);
        $data = $input['data'] ?? '';
        $responseJson = executarImpressao($ip, $porta, $data);
        if (!$responseJson['success']) $statusCode = '400 Bad Request';
    } else {
        $responseJson = [
            'status' => 'online',
            'service' => 'ERP Print Agent',
            'version' => '2.0.0',
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }

    $jsonStr = json_encode($responseJson);
    $res = "HTTP/1.1 {$statusCode}\r\n" .
           $corsHeaders .
           "Content-Length: " . strlen($jsonStr) . "\r\n\r\n" .
           $jsonStr;
    @fwrite($client, $res);
}

/**
 * Fallback para php -S
 */
function handleHttpRequestDirect() {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    header('Access-Control-Allow-Private-Network: true');
    header('Content-Type: application/json; charset=utf-8');

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $rawBody = file_get_contents('php://input');
    $input = json_decode($rawBody, true) ?? [];

    if ($uri === '/test') {
        $ip = trim($input['ip'] ?? $_GET['ip'] ?? '192.168.88.87');
        $porta = (int)($input['porta'] ?? $_GET['porta'] ?? 9100);
        $res = executarTesteImpressao($ip, $porta);
        if (!$res['success']) http_response_code(400);
        echo json_encode($res);
        exit;
    }

    if ($uri === '/print') {
        $ip = trim($input['ip'] ?? '');
        $porta = (int)($input['porta'] ?? 9100);
        $data = $input['data'] ?? '';
        $res = executarImpressao($ip, $porta, $data);
        if (!$res['success']) http_response_code(400);
        echo json_encode($res);
        exit;
    }

    echo json_encode([
        'status' => 'online',
        'service' => 'ERP Print Agent',
        'version' => '2.0.0',
        'timestamp' => date('Y-m-d H:i:s')
    ]);
}
