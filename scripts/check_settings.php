<?php

use App\Models\Empresa;

$empresas = Empresa::all();
foreach($empresas as $e){
    echo "Empresa: " . $e->nome . " | CNPJ: " . $e->cpf_cnpj . " | Ambiente: " . ($e->ambiente == 1 ? "Produção" : "Homologação") . " | CSC: " . $e->csc . " | CSC ID: " . $e->csc_id . "\n";
}
