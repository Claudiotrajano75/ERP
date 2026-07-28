<?php

use App\Models\Empresa;
use NFePHP\Common\Certificate;

$empresas = Empresa::whereNotNull('arquivo')->get();
foreach($empresas as $e){
    try {
        $c = Certificate::readPfx($e->arquivo, $e->senha);
        $cnpjCert = preg_replace('/[^0-9]/', '', $c->getCNPJ());
        $cnpjDb = preg_replace('/[^0-9]/', '', $e->cpf_cnpj);
        echo "Empresa: " . $e->nome . "\n";
        echo "  - CNPJ no Banco: " . $cnpjDb . "\n";
        echo "  - CNPJ no Certificado: " . $cnpjCert . "\n";
        if($cnpjCert != $cnpjDb){
            echo "  !!! DIVERGÊNCIA DE CNPJ !!!\n";
        }
    } catch(\Exception $ex) {
        echo "Empresa: " . $e->nome . " - Erro ao ler: " . $ex->getMessage() . "\n";
    }
}
