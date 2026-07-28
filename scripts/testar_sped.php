<?php

/**
 * Script de teste para geração do SPED Fiscal
 * 
 * Uso: php scripts/testar_sped.php {empresa_id}
 * 
 * Exemplo: php scripts/testar_sped.php 2
 * 
 * Flags opcionais:
 *   --mes=MMAAAA   Mês de referência (ex: 052026)
 *   --dados        Mostrar dados encontrados sem gerar SPED
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// ─── Parsing args ───────────────────────────────────────
$empresaId = $argv[1] ?? null;
$flags = [];
for ($i = 2; $i < count($argv); $i++) {
    if (strpos($argv[$i], '--') === 0) {
        $parts = explode('=', $argv[$i], 2);
        $flags[substr($parts[0], 2)] = $parts[1] ?? true;
    }
}

if (!$empresaId) {
    die("Uso: php scripts/testar_sped.php {empresa_id} [--dados] [--mes=MMAAAA]\n");
}

echo "═══════════════════════════════════════════════════\n";
echo "  TESTE DE GERAÇÃO DO SPED FISCAL\n";
echo "═══════════════════════════════════════════════════\n\n";

// ─── 1. Empresa ────────────────────────────────────────
$empresa = DB::table('empresas')->where('id', $empresaId)->first();
if (!$empresa) {
    die("❌ Empresa ID {$empresaId} não encontrada!\n");
}
echo "✅ Empresa: {$empresa->nome} (CPF/CNPJ: {$empresa->cpf_cnpj})\n";

// ─── 2. Escritório Contábil ──────────────────────────
$contador = DB::table('escritorio_contabils')->where('empresa_id', $empresaId)->first();
if (!$contador) {
    echo "❌ Escritório Contábil NÃO configurado!\n";
    echo "   → Acesse /escritorio-contabil para configurar.\n\n";
} else {
    echo "✅ Escritório Contábil: {$contador->razao_social} (CRC: {$contador->crc})\n";
}

// ─── 3. SPED Config ──────────────────────────────────
$spedConfig = DB::table('sped_configs')->where('empresa_id', $empresaId)->first();
if (!$spedConfig) {
    echo "❌ Configuração SPED NÃO configurada!\n";
    echo "   → Acesse /sped-config para configurar.\n\n";
} else {
    echo "✅ Config SPED: código conta={$spedConfig->codigo_conta_analitica}, código receita={$spedConfig->codigo_receita}\n";
}

// ─── 4. Dados disponíveis ────────────────────────────
$dataFinal = \Carbon\Carbon::now();
if (isset($flags['mes'])) {
    $mesRef = $flags['mes'];
    $mes = substr($mesRef, 0, 2);
    $ano = substr($mesRef, 2, 4);
    $dataFinal = \Carbon\Carbon::createFromDate($ano, $mes, 1)->endOfMonth();
}

$dataInicial = $dataFinal->copy()->startOfMonth();
$dInicial = $dataInicial->format('Y-m-d');
$dFinal = $dataFinal->format('Y-m-d');

echo "\n📅 Período: {$dInicial} a {$dFinal}\n\n";

// NFe Saídas (tpNF=1)
$nfeSaida = DB::table('nves')
    ->where('empresa_id', $empresaId)
    ->where('tpNF', 1)
    ->where('estado', 'aprovado')
    ->whereDate('created_at', '>=', $dInicial)
    ->whereDate('created_at', '<=', $dFinal)
    ->count();

// NFe Entradas (tpNF=0)
$nfeEntrada = DB::table('nves')
    ->where('empresa_id', $empresaId)
    ->where('tpNF', 0)
    ->where('estado', 'aprovado')
    ->whereDate('created_at', '>=', $dInicial)
    ->whereDate('created_at', '<=', $dFinal)
    ->count();

// NFCe
$nfce = DB::table('nfces')
    ->where('empresa_id', $empresaId)
    ->where('estado', 'aprovado')
    ->whereDate('created_at', '>=', $dInicial)
    ->whereDate('created_at', '<=', $dFinal)
    ->count();

echo "📊 DADOS ENCONTRADOS:\n";
echo "   ├── NF-e Saídas (vendas):      {$nfeSaida}\n";
echo "   ├── NF-e Entradas (compras):   {$nfeEntrada}\n";
echo "   └── NFC-e (PDV):               {$nfce}\n\n";

// ─── Verificar XMLs ──────────────────────────────────
$totalComXml = 0;
$totalSemXml = 0;

if ($nfeSaida > 0) {
    $nves = DB::table('nves')
        ->where('empresa_id', $empresaId)
        ->where('tpNF', 1)
        ->where('estado', 'aprovado')
        ->whereDate('created_at', '>=', $dInicial)
        ->whereDate('created_at', '<=', $dFinal)
        ->get();
    foreach ($nves as $n) {
        $path = public_path("xml_nfe/{$n->chave}.xml");
        if (file_exists($path)) $totalComXml++; else $totalSemXml++;
    }
}

if ($nfce > 0) {
    $nfces = DB::table('nfces')
        ->where('empresa_id', $empresaId)
        ->where('estado', 'aprovado')
        ->whereDate('created_at', '>=', $dInicial)
        ->whereDate('created_at', '<=', $dFinal)
        ->get();
    foreach ($nfces as $n) {
        $path = public_path("xml_nfce/{$n->chave}.xml");
        if (file_exists($path)) $totalComXml++; else $totalSemXml++;
    }
}

if ($nfeEntrada > 0) {
    $nves = DB::table('nves')
        ->where('empresa_id', $empresaId)
        ->where('tpNF', 0)
        ->where('estado', 'aprovado')
        ->whereDate('created_at', '>=', $dInicial)
        ->whereDate('created_at', '<=', $dFinal)
        ->get();
    foreach ($nves as $n) {
        // Tenta múltiplos caminhos
        $paths = [
            public_path("xml_entrada/{$n->chave}.xml"),
            public_path("xml_entrada_emitida/{$n->chave}.xml"),
            public_path("xml_dfe/{$n->chave}.xml"),
        ];
        $found = false;
        foreach ($paths as $p) {
            if (file_exists($p)) { $found = true; break; }
        }
        if ($found) $totalComXml++; else $totalSemXml++;
    }
}

echo "📂 XMLs:\n";
echo "   ├── Com XML disponível:  {$totalComXml}\n";
echo "   ├── Sem XML:             {$totalSemXml}\n";

// ─── 5. Rota que o usuário deve acessar ──────────────
echo "\n═══════════════════════════════════════════════════\n";
echo "  PRÓXIMOS PASSOS\n";
echo "═══════════════════════════════════════════════════\n\n";

$podeGerar = true;

if (!$contador) {
    echo "1️⃣  Configure o Escritório Contábil:\n";
    echo "   → Acesse: http://localhost:8000/escritorio-contabil\n";
    echo "   → Preencha os dados do contador (CNPJ, CRC, etc.)\n\n";
    $podeGerar = false;
}

if (!$spedConfig) {
    echo "2️⃣  Configure o SPED:\n";
    echo "   → Acesse: http://localhost:8000/sped-config\n";
    echo "   → Preencha código da conta analítica, código da receita, etc.\n\n";
    $podeGerar = false;
}

if ($totalComXml == 0) {
    echo "⚠️  Nenhum XML encontrado. O SPED precisará de XMLs para gerar os registros.\n";
    echo "   → Certifique-se de que as NF-e/NFC-e foram transmitidas e os XMLs salvos.\n\n";
}

if ($podeGerar) {
    echo "✅ Tudo pronto! Acesse para gerar o SPED:\n";
    echo "   → http://localhost:8000/sped\n\n";
} else {
    echo "⚠️  Complete as configurações pendentes antes de gerar o SPED.\n\n";
}

echo "───────────────────────────────────────────────────\n";
