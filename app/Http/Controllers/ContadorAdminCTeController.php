<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cte;
use App\Models\Empresa;
use NFePHP\DA\CTe\Dacte;

class ContadorAdminCTeController extends Controller
{
    public function __construct(){
        if (!is_dir(public_path('zips'))) {
            mkdir(public_path('zips'), 0777, true);
        }
    }

    public function cte(Request $request){
        $contador = Empresa::findOrFail(request()->empresa_id);
        $empresaSelecionada = $contador->empresa_selecionada;
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $data = Cte::where('empresa_id', $empresaSelecionada)
        ->when(!empty($start_date), function ($query) use ($start_date) {
            return $query->whereDate('created_at', '>=', $start_date);
        })
        ->when(!empty($end_date), function ($query) use ($end_date,) {
            return $query->whereDate('created_at', '<=', $end_date);
        })
        ->where('estado', 'aprovado')
        ->orderBy('created_at', 'desc')
        ->paginate(env("PAGINACAO"));

        $contXml = $this->preparaXmls($start_date, $end_date, $empresaSelecionada);
        return view('contador.cte', compact('data', 'contXml'));
    }

    private function preparaXmls($start_date, $end_date, $empresaSelecionada){
        $data = Cte::where('empresa_id', $empresaSelecionada)
        ->when(!empty($start_date), function ($query) use ($start_date) {
            return $query->whereDate('created_at', '>=', $start_date);
        })
        ->when(!empty($end_date), function ($query) use ($end_date,) {
            return $query->whereDate('created_at', '<=', $end_date);
        })
        ->where('estado', 'aprovado')
        ->get();

        $cont = 0;
        foreach($data as $item){
            if (file_exists(public_path('xml_cte/') . $item->chave . '.xml')) {
                $cont++;
            }
        }
        return $cont;

    }

    public function downloadCTe($id){
        $item = Cte::findOrFail($id);

        if (file_exists(public_path('xml_cte/') . $item->chave . '.xml')) {
            return response()->download(public_path('xml_cte/') . $item->chave . '.xml');
        } else {
            session()->flash("flash_error", "Arquivo não encontrado");
            return redirect()->back();
        }
    }

    public function dacte($id){
        $item = Cte::findOrFail($id);

        if (file_exists(public_path('xml_cte/') . $item->chave . '.xml')) {
            $xml = file_get_contents(public_path('xml_cte/') . $item->chave . '.xml');

            $danfe = new Dacte($xml, $item->estado);
            $pdf = $danfe->render();
            return response($pdf)
            ->header('Content-Type', 'application/pdf');
        } else {
            session()->flash("flash_error", "Arquivo não encontrado");
            return redirect()->back();
        }
    }

    public function downloadZip(Request $request){
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $estado = $request->get('estado');

        $contador = Empresa::findOrFail(request()->empresa_id);
        $empresaSelecionada = $contador->empresa_selecionada;
        $empresa = Empresa::find($empresaSelecionada);
        $doc = $empresa ? preg_replace('/[^0-9]/', '', $empresa->cpf_cnpj) : 'empresa';

        $data = Cte::where('empresa_id', $empresaSelecionada)
        ->when(!empty($start_date), function ($query) use ($start_date) {
            return $query->whereDate('created_at', '>=', $start_date);
        })
        ->when(!empty($end_date), function ($query) use ($end_date) {
            return $query->whereDate('created_at', '<=', $end_date);
        })
        ->when(!empty($estado), function ($query) use ($estado) {
            return $query->where('estado', $estado);
        })
        ->where('estado', '!=', 'novo')
        ->get();

        $zip = new \ZipArchive();
        $zip_file = public_path('zips') . '/cte-'.$doc.'-'.time().'.zip';
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $adicionados = 0;

        foreach($data as $item){
            $chave = $item->chave;
            if (!$chave) continue;

            if ($item->estado == 'aprovado' && file_exists(public_path('xml_cte/') . $chave . '.xml')) {
                $zip->addFile(public_path('xml_cte/') . $chave . '.xml', "Autorizadas/{$chave}.xml");
                $adicionados++;
            } elseif ($item->estado == 'cancelado') {
                if (file_exists(public_path('xml_cte_cancelada/') . $chave . '.xml')) {
                    $zip->addFile(public_path('xml_cte_cancelada/') . $chave . '.xml', "Canceladas/{$chave}-canc.xml");
                    $adicionados++;
                } elseif (file_exists(public_path('xml_cte/') . $chave . '.xml')) {
                    $zip->addFile(public_path('xml_cte/') . $chave . '.xml', "Canceladas/{$chave}.xml");
                    $adicionados++;
                }
            }
        }
        $zip->close();

        if ($adicionados > 0 && file_exists($zip_file)){
            return response()->download($zip_file, 'cte_'.$doc.'.zip');
        }else{
            session()->flash("flash_error", "Nenhum arquivo XML de CTe encontrado para os filtros selecionados.");
            return redirect()->back();
        }
    }
}
