<?php
/**
 * Script de verificação e correção automática de AUTO_INCREMENT
 * Corrige tabelas que perderam o AUTO_INCREMENT devido a exportações incompletas do phpMyAdmin.
 */

$host = getenv('DB_HOST') ?: '127.0.0.1';
$dbname = getenv('DB_DATABASE') ?: 'u544033777_erp';
$user = getenv('DB_USERNAME') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: '';

try {
    $pdo = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (PDOException $e) {
    echo "[ERRO] Não foi possível conectar ao banco {$dbname}: " . $e->getMessage() . "\n";
    exit(1);
}

// Busca colunas que são Chave Primária mas estão sem AUTO_INCREMENT
$query = "
    SELECT TABLE_NAME, COLUMN_NAME, COLUMN_TYPE 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = :dbname 
      AND COLUMN_NAME = 'id' 
      AND COLUMN_KEY = 'PRI' 
      AND EXTRA NOT LIKE '%auto_increment%'
";

$stmt = $pdo->prepare($query);
$stmt->execute(['dbname' => $dbname]);
$tabelas = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($tabelas)) {
    echo "[OK] Todas as tabelas já possuem AUTO_INCREMENT devidamente configurado!\n";
    exit(0);
}

echo "[AVISO] Foram encontradas " . count($tabelas) . " tabelas sem AUTO_INCREMENT na chave primária (id).\n";
echo "Aplicando correções automaticamente...\n";

$corrigidas = 0;
foreach ($tabelas as $tab) {
    $nomeTabela = $tab['TABLE_NAME'];
    $tipoColuna = $tab['COLUMN_TYPE'];

    try {
        $pdo->exec("ALTER TABLE `{$nomeTabela}` MODIFY `id` {$tipoColuna} NOT NULL AUTO_INCREMENT");
        echo "  -> [CORRIGIDO] {$nomeTabela} (id {$tipoColuna} AUTO_INCREMENT)\n";
        $corrigidas++;
    } catch (PDOException $e) {
        echo "  -> [FALHA] {$nomeTabela}: " . $e->getMessage() . "\n";
    }
}

echo "[SUCESSO] {$corrigidas} tabela(s) corrigida(s) com sucesso!\n";
exit(0);
