<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ConferenciaPadraoTributacaoExport implements FromView, ShouldAutoSize
{
    protected $dados;
    protected $empresa;

    public function __construct($dados, $empresa = null)
    {
        $this->dados   = $dados;
        $this->empresa = $empresa;
    }

    public function view(): View
    {
        return view('exports.conferencia_padrao_tributacao', [
            'gruposPadroes' => $this->dados['grupos'],
            'stats'         => $this->dados['stats'],
            'totalExibidos' => $this->dados['total_exibidos'],
            'empresa'       => $this->empresa,
        ]);
    }
}
