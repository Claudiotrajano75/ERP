<?php

use App\Models\Empresa;

$empresas = Empresa::all();
foreach($empresas as $e){
    echo "Empresa: " . $e->nome . " | UF: " . ($e->cidade ? $e->cidade->uf : 'N/A') . "\n";
}
