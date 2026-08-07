<?php
/**
 * Converte a paleta "Luxe" (marrom/dourado) para a paleta oficial do ERP
 * (índigo #4254ba, azul #6379c3, texto #49526b, teal #17a497) nas views do e-commerce.
 * Também converte a fonte Playfair Display para Roboto (fonte padrão do ERP).
 */
$dirs = [
    __DIR__ . '/../resources/views/loja',
];

// Ordem importa: hex primeiro, depois rgba
$replacements = [
    // Cores hex
    '#2b1d16' => '#4254ba',
    '#5a3928' => '#49526b',
    '#d4a762' => '#6379c3',
    '#c49550' => '#596db0',
    '#16a34a' => '#17a497',
    '#3d2a1e' => '#5a6fc0',
    // Cores rgba (marrom/creme/dourado)
    'rgba(90,57,40,'   => 'rgba(73,82,107,',
    'rgba(43,29,22,'   => 'rgba(66,84,186,',
    'rgba(212,167,98,' => 'rgba(99,121,195,',
    'rgba(248,246,243,' => 'rgba(243,243,248,',
    // Textos de apoio inline -> variável (funciona no claro e escuro)
    'rgba(73,82,107,' => 'var(--luxe-tan)',
    // Fundos brancos inline -> variável
    'background:white' => 'background:var(--luxe-white)',
    'background: #fff' => 'background: var(--luxe-white)',
    'background:#fff' => 'background:var(--luxe-white)',
    // Fontes
    "'Playfair Display', serif" => "'Roboto', sans-serif",
    "'Playfair Display'" => "'Roboto'",
    '"Playfair Display", serif' => '"Roboto", sans-serif',
    '"Playfair Display"' => '"Roboto"',
    // JS confirm colors
    "confirmButtonColor: '#2b1d16'" => "confirmButtonColor: '#4254ba'",
    'confirmButtonColor: "#0f172a"' => 'confirmButtonColor: "#4254ba"',
];

$changed = [];

foreach ($dirs as $dir) {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($it as $file) {
        if (!$file->isFile()) continue;
        $ext = $file->getExtension();
        if (!in_array($ext, ['blade.php', 'php'])) continue;

        $path = $file->getPathname();
        $content = file_get_contents($path);
        $new = $content;
        $fileChanges = [];

        foreach ($replacements as $from => $to) {
            if (strpos($new, $from) !== false) {
                $count = 0;
                $new = str_replace($from, $to, $new, $count);
                if ($count > 0) {
                    $fileChanges[] = "$from -> $to (x$count)";
                }
            }
        }

        if ($new !== $content) {
            file_put_contents($path, $new);
            $changed[$path] = $fileChanges;
        }
    }
}

if (empty($changed)) {
    echo "Nenhum arquivo alterado.\n";
} else {
    foreach ($changed as $path => $changes) {
        echo str_replace('\\', '/', $path) . "\n";
        foreach ($changes as $c) {
            echo "   " . $c . "\n";
        }
    }
}
echo "\nConcluído. " . count($changed) . " arquivo(s) alterado(s).\n";
