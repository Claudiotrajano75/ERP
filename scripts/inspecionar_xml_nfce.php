<?php

/**
 * Script para inspecionar estrutura de XMLs NFC-e
 * 
 * Uso: php scripts/inspecionar_xml_nfce.php {empresa_id}
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$empresaId = $argv[1] ?? 2;

// Buscar NFC-es aprovadas
$nfces = DB::table('nfces')
    ->where('empresa_id', $empresaId)
    ->where('estado', 'aprovado')
    ->orderBy('id', 'desc')
    ->limit(3)
    ->get();

if ($nfces->isEmpty()) {
    die("❌ Nenhuma NFC-e encontrada para empresa $empresaId\n");
}

echo "═══════════════════════════════════════════════════\n";
echo "  INSPEÇÃO DE XML NFC-e\n";
echo "═══════════════════════════════════════════════════\n\n";

foreach ($nfces as $nfce) {
    echo "───────────────────────────────────────────────\n";
    echo "NFC-e #{$nfce->numero} - Chave: {$nfce->chave}\n";
    echo "───────────────────────────────────────────────\n";

    $filePath = public_path("xml_nfce/{$nfce->chave}.xml");
    if (!file_exists($filePath)) {
        echo "  ❌ XML não encontrado: {$filePath}\n\n";
        continue;
    }

    echo "  ✅ XML encontrado!\n\n";

    // Carregar XML
    $content = file_get_contents($filePath);
    // Remover namespaces
    $content = preg_replace('/<(\/?)[a-zA-Z0-9_]+:([a-zA-Z0-9_]+)/', '<$1$2', $content);
    $xml = simplexml_load_string($content);
    $obj = json_decode(json_encode($xml));

    // Navegar até os itens
    $infNFe = null;
    if (isset($obj->NFe->infNFe)) {
        $infNFe = $obj->NFe->infNFe;
    } elseif (isset($obj->infNFe)) {
        $infNFe = $obj->infNFe;
    }

    if (!$infNFe || !isset($infNFe->det)) {
        echo "  ❌ Não foi possível encontrar itens no XML\n\n";
        continue;
    }

    $dets = is_array($infNFe->det) ? $infNFe->det : [$infNFe->det];

    foreach ($dets as $i => $det) {
        $prod = $det->prod;
        $imposto = $det->imposto;

        echo "  Item " . ($i + 1) . ": {$prod->xProd} (cProd: {$prod->cProd})\n";
        echo "  CFOP: {$prod->CFOP} | NCM: {$prod->NCM}\n";
        echo "  Valor: {$prod->vProd}\n\n";

        // Mostrar estrutura completa do ICMS
        echo "  Estrutura do ICMS:\n";
        if (isset($imposto->ICMS)) {
            $icmsArr = (array)$imposto->ICMS;
            echo "    Chaves disponíveis: " . implode(', ', array_keys($icmsArr)) . "\n";
            foreach ($icmsArr as $key => $val) {
                echo "    [$key]:\n";
                foreach ((array)$val as $k => $v) {
                    echo "      $k => $v\n";
                }
            }
        } else {
            echo "    ❌ ICMS não encontrado no imposto!\n";
            echo "    Estrutura do imposto:\n";
            foreach ((array)$imposto as $k => $v) {
                echo "      $k => " . (is_object($v) ? get_class($v) : $v) . "\n";
            }
        }

        // Mostrar estrutura do PIS
        echo "\n  Estrutura do PIS:\n";
        if (isset($imposto->PIS)) {
            $pisArr = (array)$imposto->PIS;
            echo "    Chaves: " . implode(', ', array_keys($pisArr)) . "\n";
        } else {
            echo "    ❌ PIS não encontrado\n";
        }

        // Mostrar estrutura do COFINS
        echo "\n  Estrutura do COFINS:\n";
        if (isset($imposto->COFINS)) {
            $confinsArr = (array)$imposto->COFINS;
            echo "    Chaves: " . implode(', ', array_keys($confinsArr)) . "\n";
        } else {
            echo "    ❌ COFINS não encontrado\n";
        }

        echo "\n";
    }
}

echo "═══════════════════════════════════════════════════\n";
