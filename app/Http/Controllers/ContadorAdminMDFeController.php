<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mdfe;
use App\Models\Empresa;
use NFePHP\DA\MDFe\Damdfe;

class ContadorAdminMDFeController extends Controller
{
    public function __construct(){
        if (!is_dir(public_path('zips'))) {
            mkdir(public_path('zips'), 0777, true);
        }
    }

    public function mdfe(Request $request){
        $contador = Empresa::findOrFail(request()->empresa_id);
        $empresaSelecionada = $contador->empresa_selecionada;
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $data = Mdfe::where('empresa_id', $empresaSelecionada)
        ->when(!empty($start_date), function ($query) use ($start_date) {
            return $query->whereDate('created_at', '>=', $start_date);
        })
        ->when(!empty($end_date), function ($query) use ($end_date,) {
            return $query->whereDate('created_at', '<=', $end_date);
        })
        ->where('estado_emissao', 'aprovado')
        ->orderBy('created_at', 'desc')
        ->paginate(env("PAGINACAO"));

        $contXml = $this->preparaXmls($start_date, $end_date, $empresaSelecionada);
        return view('contador.mdfe', compact('data', 'contXml'));
    }

    private function preparaXmls($start_date, $end_date, $empresaSelecionada){
        $data = Mdfe::where('empresa_id', $empresaSelecionada)
        ->when(!empty($start_date), function ($query) use ($start_date) {
            return $query->whereDate('created_at', '>=', $start_date);
        })
        ->when(!empty($end_date), function ($query) use ($end_date,) {
            return $query->whereDate('created_at', '<=', $end_date);
        })
        ->where('estado_emissao', 'aprovado')
        ->get();

        $cont = 0;
        foreach($data as $item){
            if (file_exists(public_path('xml_mdfe/') . $item->chave . '.xml')) {
                $cont++;
            }
        }
        return $cont;

    }

    public function downloadMDFe($id){
        $item = Mdfe::findOrFail($id);

        if (file_exists(public_path('xml_mdfe/') . $item->chave . '.xml')) {
            return response()->download(public_path('xml_mdfe/') . $item->chave . '.xml');
        } else {
            session()->flash("flash_error", "Arquivo não encontrado");
            return redirect()->back();
        }
    }

    public function damdfe($id){
        $item = Mdfe::findOrFail($id);

        if (file_exists(public_path('xml_mdfe/') . $item->chave . '.xml')) {
            $xml = file_get_contents(public_path('xml_mdfe/') . $item->chave . '.xml');

            $danfe = new Damdfe($xml);
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

        $data = Mdfe::where('empresa_id', $empresaSelecionada)
        ->when(!empty($start_date), function ($query) use ($start_date) {
            return $query->whereDate('created_at', '>=', $start_date);
        })
        ->when(!empty($end_date), function ($query) use ($end_date) {
            return $query->whereDate('created_at', '<=', $end_date);
        })
        ->when(!empty($estado), function ($query) use ($estado) {
            return $query->where('estado_emissao', $estado);
        })
        ->where('estado_emissao', '!=', 'novo')
        ->get();

        $zip = new \ZipArchive();
        $zip_file = public_path('zips') . '/mdfe-'.$doc.'-'.time().'.zip';
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $adicionados = 0;

        foreach($data as $item){
            $chave = $item->chave;
            if (!$chave) continue;

            if ($item->estado_emissao == 'aprovado' && file_exists(public_path('xml_mdfe/') . $chave . '.xml')) {
                $zip->addFile(public_path('xml_mdfe/') . $chave . '.xml', "Autorizadas/{$chave}.xml");
                $adicionados++;
            } elseif ($item->estado_emissao == 'cancelado') {
                if (file_exists(public_path('xml_mdfe_cancelada/') . $chave . '.xml')) {
                    $zip->addFile(public_path('xml_mdfe_cancelada/') . $chave . '.xml', "Canceladas/{$chave}-canc.xml");
                    $adicionados++;
                } elseif (file_exists(public_path('xml_mdfe/') . $chave . '.xml')) {
                    $zip->addFile(public_path('xml_mdfe/') . $chave . '.xml', "Canceladas/{$chave}.xml");
                    $adicionados++;
                }
            }
        }
        $zip->close();

        if ($adicionados > 0 && file_exists($zip_file)){
            return response()->download($zip_file, 'mdfe_'.$doc.'.zip');
        }else{
            session()->flash("flash_error", "Nenhum arquivo XML de MDFe encontrado para os filtros selecionados.");
            return redirect()->back();
        }
    }
}
