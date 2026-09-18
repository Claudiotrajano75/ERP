<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('produtos:vincular-locais {empresa_id=48}', function ($empresa_id) {
    $localizacao = \App\Models\Localizacao::where('empresa_id', $empresa_id)->first();
    if (!$localizacao) {
        $this->error("Nenhuma localização encontrada para a empresa {$empresa_id}!");
        return;
    }

    $produtos = \App\Models\Produto::where('empresa_id', $empresa_id)->get();
    $vinculados = 0;

    foreach ($produtos as $p) {
        $existe = \App\Models\ProdutoLocalizacao::where('produto_id', $p->id)
            ->where('localizacao_id', $localizacao->id)
            ->first();

        if (!$existe) {
            \App\Models\ProdutoLocalizacao::create([
                'produto_id' => $p->id,
                'localizacao_id' => $localizacao->id
            ]);
            $vinculados++;
        }
    }

    $this->info("Sucesso! {$vinculados} produtos vinculados à localização ID {$localizacao->id} ({$localizacao->nome}).");
});




