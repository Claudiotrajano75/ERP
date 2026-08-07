<?php
$sql = file_get_contents('C:/u544033777_erp.sql');
echo "dump: " . round(strlen($sql)/1024/1024, 1) . " MB\n";

// ---- Tabela nves (NFe) ----
preg_match('/INSERT INTO `nves` \(([^)]+)\) VALUES\n(.*?);\n/s', $sql, $m);
if (isset($m[2])) {
    $rows = preg_split("/\)\s*,\s*\(/", $m[2]);
    echo "nves (NFe): " . count($rows) . " linhas\n";
    $emp2 = [];
    foreach ($rows as $r) {
        $r = trim($r, "\n \r");
        $r = preg_replace('/\);\s*$/', '', $r);
        $vals = str_getcsv($r, ',', "'", '\\');
        foreach ($vals as &$v) {
            $v = trim($v);
            if (strlen($v) >= 2 && $v[0] === "'" && substr($v, -1) === "'") {
                $v = substr($v, 1, -1);
            } elseif ($v === 'NULL') {
                $v = null;
            }
        }
        if (isset($vals[1]) && (int)$vals[1] === 2) {
            $emp2[] = $vals;
        }
    }
    echo "NFe empresa 2: " . count($emp2) . "\n";
    foreach ($emp2 as $r) {
        printf("  id=%s | num=%s | serie=%s | seq=%s | %s | chave=%s | emissao=%s\n",
            $r[0], var_export($r[2], true), var_export($r[3], true), var_export($r[4], true),
            $r[5], ($r[6] ? substr($r[6], 0, 25) . '...' : '-'), var_export($r[8], true));
    }
    // maiores numeros
    $nums = array_map(fn($r) => (int)$r[2], $emp2);
    if ($nums) {
        rsort($nums);
        echo "Maiores numeros NFe emp2: " . implode(', ', array_slice($nums, 0, 5)) . "\n";
    }
} else {
    echo "nves: nao parseou\n";
}

// ---- Tabela nfces ----
preg_match('/INSERT INTO `nfces` \(([^)]+)\) VALUES\n(.*?);\n/s', $sql, $m2);
if (isset($m2[2])) {
    $rows2 = preg_split("/\)\s*,\s*\(/", $m2[2]);
    echo "\nnfces (NFCe): " . count($rows2) . " linhas\n";
    $emp2 = [];
    foreach ($rows2 as $r) {
        $r = trim($r, "\n \r");
        $r = preg_replace('/\);\s*$/', '', $r);
        $vals = str_getcsv($r, ',', "'", '\\');
        foreach ($vals as &$v) {
            $v = trim($v);
            if (strlen($v) >= 2 && $v[0] === "'" && substr($v, -1) === "'") {
                $v = substr($v, 1, -1);
            } elseif ($v === 'NULL') {
                $v = null;
            }
        }
        if (isset($vals[1]) && (int)$vals[1] === 2) {
            $emp2[] = $vals;
        }
    }
    echo "NFCe empresa 2: " . count($emp2) . "\n";
    // achar colunas numero/chave/estado
    preg_match('/INSERT INTO `nfces` \(([^)]+)\) VALUES/', $m2[0], $cm);
    $cols = array_map(fn($c) => trim($c, '` '), explode(',', $cm[1]));
    $iNum = array_search('numero', $cols);
    $iSerie = array_search('numero_serie', $cols);
    $iChave = array_search('chave', $cols);
    $iEstado = array_search('estado', $cols);
    echo "colunas: numero@$iNum serie@$iSerie chave@$iChave estado@$iEstado\n";
    $nums = [];
    foreach (array_slice($emp2, -20) as $r) {
        $num = isset($r[$iNum]) ? $r[$iNum] : '?';
        $nums[] = (int)$num;
        printf("  num=%s | serie=%s | %s | chave=%s\n",
            var_export($num, true),
            isset($r[$iSerie]) ? var_export($r[$iSerie], true) : '?',
            isset($r[$iEstado]) ? $r[$iEstado] : '?',
            isset($r[$iChave]) && $r[$iChave] ? substr($r[$iChave], 0, 25) . '...' : '-');
    }
    if ($nums) {
        rsort($nums);
        echo "Maiores numeros NFCe emp2: " . implode(', ', array_slice($nums, 0, 8)) . "\n";
    }
} else {
    echo "nfces: nao parseou\n";
}
