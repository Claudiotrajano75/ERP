<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Nfe;
use App\Models\Nfce;
use App\Models\Sped;
use App\Models\EscritorioContabil;
use App\Models\SpedConfig;

class ContadorAdminSpedController extends Controller
{
    private function getEmpresaSelecionada()
    {
        $contador = Empresa::find(request()->empresa_id);
        if (!$contador || !$contador->empresa_selecionada) {
            return null;
        }
        return Empresa::with('cidade')->find($contador->empresa_selecionada);
    }

    public function index(Request $request)
    {
        $empresa = $this->getEmpresaSelecionada();
        if (!$empresa) {
            session()->flash("flash_warning", "Selecione uma empresa no painel do contador primeiro!");
            return redirect()->route('home');
        }

        $firstDate = $request->get('data_inicial', date('Y-m-01'));
        $lastDate = $request->get('data_final', date('Y-m-t'));

        $empresaId = $empresa->id;

        $stats = [
            'nfe_saida' => Nfe::where('empresa_id', $empresaId)
                ->where('tpNF', 1)
                ->where('estado', 'aprovado')
                ->whereDate('data_emissao_saida', '>=', $firstDate)
                ->whereDate('data_emissao_saida', '<=', $lastDate)
                ->count(),
            'nfce' => Nfce::where('empresa_id', $empresaId)
                ->where('estado', 'aprovado')
                ->whereDate('created_at', '>=', $firstDate)
                ->whereDate('created_at', '<=', $lastDate)
                ->count(),
            'nfe_entrada' => Nfe::where('empresa_id', $empresaId)
                ->where('tpNF', 0)
                ->where('estado', '!=', 'cancelado')
                ->whereDate('created_at', '>=', $firstDate)
                ->whereDate('created_at', '<=', $lastDate)
                ->count(),
            'escritorio_configurado' => EscritorioContabil::where('empresa_id', $empresaId)->exists(),
            'sped_configurado' => SpedConfig::where('empresa_id', $empresaId)->exists(),
        ];

        $cnpj = preg_replace('/[^0-9]/', '', $empresa->cpf_cnpj);
        $spedFilePath = public_path("sped_files/SPED-EFD-{$cnpj}.txt");
        $spedFileInfo = null;
        if (file_exists($spedFilePath)) {
            $spedFileInfo = [
                'data' => date("d/m/Y H:i", filemtime($spedFilePath)),
                'tamanho' => filesize($spedFilePath),
                'linhas' => count(file($spedFilePath)),
                'url' => asset("sped_files/SPED-EFD-{$cnpj}.txt")
            ];
        }

        return view('contador.sped', compact('empresa', 'firstDate', 'lastDate', 'stats', 'spedFileInfo'));
    }

    public function store(Request $request)
    {
        $empresa = $this->getEmpresaSelecionada();
        if (!$empresa) {
            session()->flash("flash_warning", "Selecione uma empresa primeiro!");
            return redirect()->route('home');
        }

        $request->merge(['empresa_id' => $empresa->id]);
        $spedController = app(SpedController::class);
        return $spedController->store($request);
    }
}
