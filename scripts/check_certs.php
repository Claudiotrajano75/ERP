<?php

use App\Models\Empresa;
use NFePHP\Common\Certificate;

$empresas = Empresa::whereNotNull('arquivo')->get();
foreach($empresas as $e){
    try {
        $c = Certificate::readPfx($e->arquivo, $e->senha);
        echo "Empresa: " . $e->nome . " - Expira em: " . $c->publicKey->validTo->format('Y-m-d H:i:s') . "\n";
    } catch(\Exception $ex) {
        echo "Empresa: " . $e->nome . " - Erro ao ler: " . $ex->getMessage() . "\n";
    }
}
