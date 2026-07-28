<?php

/**
 * Script para configurar e gerar o SPED Fiscal
 * 
 * Uso: php scripts/setup_e_gerar_sped.php {empresa_id}
 * 
 * Exemplo: php scripts/setup_e_gerar_sped.php 2
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$empresaId = $argv[1] ?? null;
if (!$empresaId) {
    die("Uso: php scripts/setup_e_gerar_sped.php {empresa_id}\n");
}

echo "═══════════════════════════════════════════════════\n";
echo "  SETUP + GERAÇÃO DO SPED FISCAL\n";
echo "═══════════════════════════════════════════════════\n\n";

// ─── 1. Verificar empresa ────────────────────────────
$empresa = DB::table('empresas')->where('id', $empresaId)->first();
if (!$empresa) {
    die("❌ Empresa ID {$empresaId} não encontrada!\n");
}
echo "✅ Empresa: {$empresa->nome}\n";

// ─── 2. Pegar uma cidade para o contador ─────────────
$cidade = DB::table('cidades')->first();
if (!$cidade) {
    echo "⚠️ Nenhuma cidade encontrada. Usando código 2304400 (Fortaleza).\n";
    $codMun = '2304400';
} else {
    $codMun = $cidade->codigo;
    echo "✅ Cidade encontrada: {$cidade->nome} / {$cidade->uf} (código: {$codMun})\n";
}

// ─── 3. Criar Escritório Contábil ────────────────────
$contador = DB::table('escritorio_contabils')->where('empresa_id', $empresaId)->first();
if (!$contador) {
    DB::table('escritorio_contabils')->insert([
        'empresa_id' => $empresaId,
        'razao_social' => 'ESCRITORIO CONTABIL DE TESTE LTDA',
        'cnpj' => '00.000.000/0001-91',
        'cpf' => '',
        'crc' => 'CE000000-O',
        'cep' => '60000000',
        'rua' => 'Rua Teste',
        'numero' => '100',
        'bairro' => 'Centro',
        'telefone' => '85999999999',
        'email' => 'contador@teste.com',
        'cidade_id' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    echo "✅ Escritório Contábil criado!\n";
} else {
    echo "✅ Escritório Contábil já existe: {$contador->razao_social}\n";
}

// ─── 4. Criar Configuração SPED ──────────────────────
$spedConfig = DB::table('sped_configs')->where('empresa_id', $empresaId)->first();
if (!$spedConfig) {
    DB::table('sped_configs')->insert([
        'empresa_id' => $empresaId,
        'codigo_conta_analitica' => '1.1.01.001',
        'codigo_receita' => '106-0',
        'gerar_bloco_k' => 0,
        'layout_bloco_k' => 0,
        'codigo_obrigacao' => '000',
        'data_vencimento' => '20',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    echo "✅ Configuração SPED criada!\n";
} else {
    echo "✅ Configuração SPED já existe\n";
}

// ─── 5. Contar dados disponíveis ─────────────────────
$dFinal = date('Y-m-d');
$dInicial = date('Y-m-01');

$nfeSaida = DB::table('nves')
    ->where('empresa_id', $empresaId)
    ->where('tpNF', 1)->where('estado', 'aprovado')
    ->whereDate('created_at', '>=', $dInicial)
    ->whereDate('created_at', '<=', $dFinal)->count();

$nfeEntrada = DB::table('nves')
    ->where('empresa_id', $empresaId)
    ->where('tpNF', 0)->where('estado', 'aprovado')
    ->whereDate('created_at', '>=', $dInicial)
    ->whereDate('created_at', '<=', $dFinal)->count();

$nfce = DB::table('nfces')
    ->where('empresa_id', $empresaId)
    ->where('estado', 'aprovado')
    ->whereDate('created_at', '>=', $dInicial)
    ->whereDate('created_at', '<=', $dFinal)->count();

echo "\n📊 DADOS NO PERÍODO ({$dInicial} a {$dFinal}):\n";
echo "   NF-e Saídas: {$nfeSaida} | NF-e Entradas: {$nfeEntrada} | NFC-e: {$nfce}\n";

if ($nfce == 0 && $nfeSaida == 0) {
    echo "\n⚠️ Nenhuma nota encontrada no período atual.\n";
    echo "   Tente gerar com --mes=MMAAAA para um mês anterior.\n";
    exit(0);
}

// ─── 6. Gerar SPED via controller ────────────────────
echo "\n🔄 Gerando SPED...\n";

try {
    // Simular request
    $request = new Illuminate\Http\Request();
    $request->merge([
        'empresa_id' => $empresaId,
        'data_inicial' => $dInicial,
        'data_final' => $dFinal,
        'inventario' => '0',
    ]);
    $request->setMethod('POST');

    // Substituir request global para o controller enxergar
    app()->instance('request', $request);
    Request::enableHttpMethodParameterOverride();

    // Resolver controller
    $util = app()->make(App\Utils\SpedUtil::class);
    $controller = new App\Http\Controllers\SpedController($util);

    // Chamar store
    $response = $controller->store($request);

    echo "✅ SPED GERADO COM SUCESSO!\n";
    echo "   Arquivo: " . public_path("sped_files/SPED-EFD-" . preg_replace('/[^0-9]/', '', $empresa->cpf_cnpj) . ".txt") . "\n";

    // Mostrar primeiras linhas
    $cnpj = preg_replace('/[^0-9]/', '', $empresa->cpf_cnpj);
    $filePath = public_path("sped_files/SPED-EFD-" . $cnpj . ".txt");
    if (file_exists($filePath)) {
        $lines = file($filePath);
        echo "\n📄 Primeiras 10 linhas do SPED:\n";
        for ($i = 0; $i < min(10, count($lines)); $i++) {
            echo "   " . trim($lines[$i]) . "\n";
        }
        echo "\n📄 Total de linhas: " . count($lines) . "\n";
    }

} catch (\Exception $e) {
    echo "❌ ERRO ao gerar SPED: " . $e->getMessage() . "\n";
    echo "   Arquivo: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "   Trace:\n";
    foreach (explode("\n", $e->getTraceAsString()) as $line) {
        echo "     {$line}\n";
    }
}

echo "\n═══════════════════════════════════════════════════\n";
echo "  FIM DO TESTE\n";
echo "═══════════════════════════════════════════════════\n";
