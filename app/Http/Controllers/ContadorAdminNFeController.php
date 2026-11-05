<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nfe;
use App\Models\Empresa;
use NFePHP\DA\NFe\Danfe;

class ContadorAdminNFeController extends Controller
{
    public function __construct(){
        if (!is_dir(public_path('zips'))) {
            mkdir(public_path('zips'), 0777, true);
        }
    }

    public function nfe(Request $request){
        $contador = Empresa::findOrFail(request()->empresa_id);
        $empresaSelecionada = $contador->empresa_selecionada;
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $tpNF = $request->get('tpNF');
        $estado = $request->get('estado');

        $data = Nfe::where('empresa_id', $empresaSelecionada)->where('orcamento', 0)
        ->when(!empty($start_date), function ($query) use ($start_date) {
            return $query->whereDate('created_at', '>=', $start_date);
        })
        ->when(!empty($end_date), function ($query) use ($end_date,) {
            return $query->whereDate('created_at', '<=', $end_date);
        })
        ->when($tpNF != "", function ($query) use ($tpNF) {
            return $query->where('tpNF', $tpNF);
        })
        ->when($estado != "", function ($query) use ($estado) {
            return $query->where('estado', $estado);
        })
        ->orderBy('created_at', 'desc')
        ->paginate(env("PAGINACAO"));

        $contXml = $this->preparaXmls($start_date, $end_date, $tpNF, $empresaSelecionada);
        return view('contador.nfe', compact('data', 'contXml'));
    }

    private function preparaXmls($start_date, $end_date, $tpNF, $empresaSelecionada){
        $data = Nfe::where('empresa_id', $empresaSelecionada)->where('orcamento', 0)
        ->when(!empty($start_date), function ($query) use ($start_date) {
            return $query->whereDate('created_at', '>=', $start_date);
        })
        ->when(!empty($end_date), function ($query) use ($end_date) {
            return $query->whereDate('created_at', '<=', $end_date);
        })
        ->when($tpNF != "", function ($query) use ($tpNF) {
            return $query->where('tpNF', $tpNF);
        })
        ->where('estado', '!=', 'novo')
        ->get();

        $cont = 0;
        foreach($data as $item){
            $chave = $item->chave_importada ?: $item->chave;
            if (!$chave) continue;

            $encontrado = false;
            $paths = [
                public_path('xml_nfe/') . $chave . '.xml',
                public_path('xml_entrada/') . $chave . '.xml',
                public_path('xml_dfe/') . $chave . '.xml',
                public_path('xml_entrada_emitida/') . $chave . '.xml',
                public_path('xml_nfe_cancelada/') . $chave . '.xml'
            ];
            foreach ($paths as $p) {
                if (file_exists($p)) {
                    $encontrado = true;
                    break;
                }
            }
            if ($encontrado) $cont++;
        }
        return $cont;
    }

    public function downloadNFe($id){
        $item = Nfe::findOrFail($id);
        $chave = $item->chave_importada ?: $item->chave;

        $paths = [
            public_path('xml_nfe/') . $chave . '.xml',
            public_path('xml_entrada/') . $chave . '.xml',
            public_path('xml_dfe/') . $chave . '.xml',
            public_path('xml_entrada_emitida/') . $chave . '.xml'
        ];

        foreach ($paths as $p) {
            if (file_exists($p)) {
                return response()->download($p);
            }
        }

        session()->flash("flash_error", "Arquivo XML não encontrado!");
        return redirect()->back();
    }

    public function danfe($id){
        $item = Nfe::findOrFail($id);
        $chave = $item->chave_importada ?: $item->chave;

        $paths = [
            public_path('xml_nfe/') . $chave . '.xml',
            public_path('xml_entrada/') . $chave . '.xml',
            public_path('xml_dfe/') . $chave . '.xml',
            public_path('xml_entrada_emitida/') . $chave . '.xml'
        ];

        foreach ($paths as $p) {
            if (file_exists($p)) {
                $xml = file_get_contents($p);
                $danfe = new Danfe($xml);
                $pdf = $danfe->render();
                return response($pdf)->header('Content-Type', 'application/pdf');
            }
        }

        session()->flash("flash_error", "Arquivo XML não encontrado para gerar o DANFE");
        return redirect()->back();
    }

    public function downloadZip(Request $request){
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $tpNF = $request->get('tpNF');
        $estado = $request->get('estado');

        $contador = Empresa::findOrFail(request()->empresa_id);
        $empresaSelecionada = $contador->empresa_selecionada;
        $empresa = Empresa::find($empresaSelecionada);
        $doc = $empresa ? preg_replace('/[^0-9]/', '', $empresa->cpf_cnpj) : 'empresa';

        $data = Nfe::where('empresa_id', $empresaSelecionada)->where('orcamento', 0)
        ->when(!empty($start_date), function ($query) use ($start_date) {
            return $query->whereDate('created_at', '>=', $start_date);
        })
        ->when(!empty($end_date), function ($query) use ($end_date) {
            return $query->whereDate('created_at', '<=', $end_date);
        })
        ->when($tpNF != "", function ($query) use ($tpNF) {
            return $query->where('tpNF', $tpNF);
        })
        ->when(!empty($estado), function ($query) use ($estado) {
            return $query->where('estado', $estado);
        })
        ->where('estado', '!=', 'novo')
        ->get();

        $zip = new \ZipArchive();
        $zip_file = public_path('zips') . '/nfe-'.$doc.'-'.time().'.zip';
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $adicionados = 0;

        foreach($data as $item){
            $chave = $item->chave_importada ?: $item->chave;
            if (!$chave) continue;

            if ($item->tpNF == 0) {
                // Entrada (Compra)
                $paths = [
                    public_path('xml_entrada/') . $chave . '.xml',
                    public_path('xml_dfe/') . $chave . '.xml',
                    public_path('xml_entrada_emitida/') . $chave . '.xml',
                    public_path('xml_nfe/') . $chave . '.xml',
                ];
                foreach ($paths as $p) {
                    if (file_exists($p)) {
                        $zip->addFile($p, "Entradas_Compras/{$chave}.xml");
                        $adicionados++;
                        break;
                    }
                }
            } else {
                // Saída
                if ($item->estado == 'aprovado') {
                    if (file_exists(public_path('xml_nfe/') . $chave . '.xml')) {
                        $zip->addFile(public_path('xml_nfe/') . $chave . '.xml', "Saidas_Autorizadas/{$chave}.xml");
                        $adicionados++;
                    }
                } elseif ($item->estado == 'cancelado') {
                    if (file_exists(public_path('xml_nfe_cancelada/') . $chave . '.xml')) {
                        $zip->addFile(public_path('xml_nfe_cancelada/') . $chave . '.xml', "Saidas_Canceladas/{$chave}-canc.xml");
                        $adicionados++;
                    } elseif (file_exists(public_path('xml_nfe/') . $chave . '.xml')) {
                        $zip->addFile(public_path('xml_nfe/') . $chave . '.xml', "Saidas_Canceladas/{$chave}.xml");
                        $adicionados++;
                    }
                }

                // Carta de Correção
                if (file_exists(public_path('xml_nfe_correcao/') . $chave . '.xml')) {
                    $zip->addFile(public_path('xml_nfe_correcao/') . $chave . '.xml', "Cartas_Correcao/{$chave}-cce.xml");
                    $adicionados++;
                }
            }
        }
        $zip->close();

        if ($adicionados > 0 && file_exists($zip_file)){
            return response()->download($zip_file, 'nfe_'.$doc.'.zip');
        }else{
            session()->flash("flash_error", "Nenhum arquivo XML encontrado para os filtros selecionados.");
            return redirect()->back();
        }
    }
}
