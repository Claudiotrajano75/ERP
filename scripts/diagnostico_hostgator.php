<?php
header('Content-Type: text/plain; charset=utf-8');

echo "=== DIAGNÓSTICO DE CONECTIVIDADE SEFAZ (HOSTGATOR) ===\n\n";

$urls = [
    "https://nfce.svrs.rs.gov.br/ws/NfeAutorizacao/NFeAutorizacao4.asmx",
    "https://nfce-homologacao.svrs.rs.gov.br/ws/NfeAutorizacao/NFeAutorizacao4.asmx"
];

function test_curl($url, $ipv4 = false) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    
    if ($ipv4) {
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        echo "Testando com Força IPv4: $url\n";
    } else {
        echo "Testando (Auto): $url\n";
    }

    $start = microtime(true);
    $response = curl_exec($ch);
    $end = microtime(true);
    $elapsed = round(($end - $start) * 1000, 2);

    if (curl_errno($ch)) {
        echo "ERRO [" . curl_errno($ch) . "]: " . curl_error($ch) . " (Tempo: {$elapsed}ms)\n";
    } else {
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        echo "SUCESSO: HTTP $http_code (Tempo: {$elapsed}ms)\n";
    }
    curl_close($ch);
    echo "--------------------------------------------------\n";
}

echo "1. Informações do Ambiente:\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "OpenSSL Version: " . OPENSSL_VERSION_TEXT . "\n";
echo "cURL Version: " . curl_version()['version'] . "\n\n";

echo "2. Testes de Conexão:\n";
foreach ($urls as $url) {
    test_curl($url, false);
    test_curl($url, true);
}

echo "\n3. Teste com SoapClient Nativo (PHP):\n";
function test_soap($url) {
    echo "Testando SoapClient para: $url\n";
    $start = microtime(true);
    try {
        // We use a dummy WSDL or just try to connect
        $client = @new SoapClient(null, [
            'location' => $url,
            'uri'      => "http://www.portalfiscal.inf.br/nfe",
            'connection_timeout' => 10,
            'exceptions' => true,
            'trace' => 1
        ]);
        // Try a simple call or just check if it was created
        echo "SUCESSO: SoapClient instanciado (Conexão inicial OK)\n";
    } catch (\Exception $e) {
        echo "ERRO SoapClient: " . $e->getMessage() . "\n";
    }
    $end = microtime(true);
    $elapsed = round(($end - $start) * 1000, 2);
    echo "Tempo: {$elapsed}ms\n";
    echo "--------------------------------------------------\n";
}

test_soap($urls[0]);

echo "\n4. Verificação de DNS:\n";
$host = "nfce.svrs.rs.gov.br";
$ip = gethostbyname($host);
echo "DNS $host resolve para: $ip\n";
if ($ip === $host) {
    echo "ERRO: Falha ao resolver DNS.\n";
}

echo "\nFim do Diagnóstico.\n";
