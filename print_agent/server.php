<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * ERP - AGENTE LOCAL DE IMPRESSÃO TÉRMICA ESC/POS
 * ═══════════════════════════════════════════════════════════════
 * 
 * Permite que o ERP hospedado na nuvem (Hostinger, AWS, etc.) envie 
 * impressões fiscais NFC-e e não fiscais diretamente para impressoras 
 * térmicas na rede local (LAN: 192.168.x.x) com corte e formatação nativa.
 *
 * Porta padrão do Agente: 9187
 */

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Private-Network: true');
header('Content-Type: application/json; charset=utf-8');

// Responde imediatamente a requisições CORS Preflight (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Endpoint de Status / Healthcheck
if ($uri === '/' || $uri === '/status') {
    echo json_encode([
        'status' => 'online',
        'service' => 'ERP Print Agent',
        'version' => '1.0.0',
        'php_version' => PHP_VERSION,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    exit;
}

// Endpoint de Teste de Conexão com a Impressora
if ($uri === '/test') {
    $rawBody = file_get_contents('php://input');
    $input   = json_decode($rawBody, true) ?? [];
    if (empty($input)) {
        $input = !empty($_POST) ? $_POST : $_GET;
    }
    $ip    = trim($input['ip'] ?? $_GET['ip'] ?? '192.168.88.87');
    $porta = (int)($input['porta'] ?? $_GET['porta'] ?? 9100);

    $socket = @fsockopen($ip, $porta, $errno, $errstr, 3);
    if (!$socket) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => "Falha ao conectar na impressora {$ip}:{$porta} - {$errstr} ({$errno})"
        ]);
        exit;
    }

    // Envia recibo de teste com corte
    $teste = "\x1B\x40"; // Reset
    $teste .= "\x1B\x61\x01"; // Centro
    $teste .= "\x1B\x45\x01"; // Negrito
    $teste .= "\nTESTE DE IMPRESSAO\n";
    $teste .= "AGENTE LOCAL CONECTADO!\n";
    $teste .= "\x1B\x45\x00";
    $teste .= date('d/m/Y H:i:s') . "\n";
    $teste .= "IP: {$ip}:{$porta}\n";
    $teste .= str_repeat('-', 32) . "\n\n\n";
    $teste .= "\x1D\x56\x41\x10"; // Corte parcial

    fwrite($socket, $teste);
    fclose($socket);

    echo json_encode([
        'success' => true,
        'message' => "Conexão com a impressora {$ip}:{$porta} realizada com sucesso! Teste impresso."
    ]);
    exit;
}

// Endpoint de Impressão Direta ESC/POS
if ($uri === '/print') {
    $rawBody = file_get_contents('php://input');
    $input   = json_decode($rawBody, true) ?? [];
    if (empty($input)) {
        $input = !empty($_POST) ? $_POST : $_GET;
    }
    $ip    = trim($input['ip'] ?? '');
    $porta = (int)($input['porta'] ?? 9100);
    $data  = $input['data'] ?? '';

    if (empty($ip) || empty($data)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Parâmetros inválidos: IP e dados de impressão são obrigatórios.'
        ]);
        exit;
    }

    // Decodifica payload base64 se aplicável
    $rawBytes = base64_decode($data, true);
    if ($rawBytes === false) {
        $rawBytes = $data;
    }

    $socket = @fsockopen($ip, $porta, $errno, $errstr, 4);
    if (!$socket) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => "Erro ao conectar com a impressora em {$ip}:{$porta} - {$errstr}"
        ]);
        exit;
    }

    fwrite($socket, $rawBytes);
    fflush($socket);
    fclose($socket);

    echo json_encode([
        'success' => true,
        'message' => 'Impresso com sucesso via Agente Local!'
    ]);
    exit;
}

// 404 para outras rotas
http_response_code(404);
echo json_encode(['error' => 'Rota não encontrada']);
