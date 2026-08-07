<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Empresa;
use App\Services\NFeService;
use NFePHP\Common\Keys;
use NFePHP\Common\Standardize;

$empresa = Empresa::find(2);
$empresa = __objetoParaEmissao($empresa, 1);

$cnpj = preg_replace('/[^0-9]/', '', $empresa->cpf_cnpj);
$cUF = $empresa->cidade->uf;
$ano = '2608'; // AAMM atual

$svc = new NFeService([
    "atualizacao" => date('Y-m-d h:i:s'),
    "tpAmb" => (int)$empresa->ambiente,
    "razaosocial" => $empresa->nome,
    "siglaUF" => $cUF,
    "cnpj" => $cnpj,
    "schemes" => "PL_010",
    "versao" => "4.00",
], $empresa);

function ufParaCodigo($uf)
{
    $ufs = ['RO'=>11,'AC'=>12,'AM'=>13,'RR'=>14,'PA'=>15,'AP'=>16,'TO'=>17,'MA'=>21,'PI'=>22,'CE'=>23,'RN'=>24,'PB'=>25,'PE'=>26,'AL'=>27,'SE'=>28,'BA'=>29,'MG'=>31,'ES'=>32,'RJ'=>33,'SP'=>35,'PR'=>41,'SC'=>42,'RS'=>43,'MS'=>50,'MT'=>51,'GO'=>52,'DF'=>53];
    return $ufs[$uf];
}

$codUF = ufParaCodigo($cUF);
$numeros = isset($argv[1]) ? array_map('intval', explode(',', $argv[1])) : [8, 9, 10, 11, 26];

foreach ($numeros as $nNF) {
    $serie = '1';
    try {
        // Monta a chave com número e série (tpEmis=1, cNF aleatório)
        $cNF = str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
        $chave = Keys::build($codUF, $ano, $cnpj, '55', $serie, str_pad($nNF, 9, '0', STR_PAD_LEFT), '1', $cNF);
        echo "== Número $nNF (série $serie): consultando chave $chave ...\n";
        $nfeFake = new \App\Models\Nfe();
        $nfeFake->chave = $chave;
        $res = $svc->consultar($nfeFake);
        $arr = json_decode(json_encode($res), true);
        echo "   -> cStat: " . ($arr['cStat'] ?? '?') . " | " . ($arr['xMotivo'] ?? '?') . "\n";
    } catch (\Exception $e) {
        echo "   -> ERRO: " . $e->getMessage() . "\n";
    }
    usleep(500000);
}
