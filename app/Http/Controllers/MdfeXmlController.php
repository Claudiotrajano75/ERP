<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mdfe;
use App\Models\Empresa;

class MdfeXmlController extends Controller
{
    public function index(Request $request){
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $data = [];
        $stats = [
            'total' => 0,
            'com_xml' => 0,
            'sem_xml' => 0,
            'valor' => 0,
        ];

        if($start_date || $end_date){
            $data = Mdfe::where('empresa_id', request()->empresa_id)
            ->when(!empty($start_date), function ($query) use ($start_date) {
                return $query->whereDate('created_at', '>=', $start_date);
            })
            ->when(!empty($end_date), function ($query) use ($end_date) {
                return $query->whereDate('created_at', '<=', $end_date);
            })
            ->where('estado_emissao', 'aprovado')
            ->get();

            $comXml = 0;
            $semXml = 0;
            $valorTotal = 0;

            foreach($data as $item){
                $valorTotal += $item->valor_carga;
                if(file_exists(public_path("xml_mdfe/").$item->chave.".xml")){
                    $comXml++;
                } else {
                    $semXml++;
                }
            }

            $stats = [
                'total' => count($data),
                'com_xml' => $comXml,
                'sem_xml' => $semXml,
                'valor' => $valorTotal,
            ];
        }

        return view('mdfe.arquivos_xml', compact('data', 'stats'));
    }


    public function download(Request $request){
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $empresa = Empresa::findOrFail($request->empresa_id);
        $doc = preg_replace('/[^0-9]/', '', $empresa->cpf_cnpj);
        
        $data = Mdfe::where('empresa_id', request()->empresa_id)
        ->when(!empty($start_date), function ($query) use ($start_date) {
            return $query->whereDate('created_at', '>=', $start_date);
        })
        ->when(!empty($end_date), function ($query) use ($end_date,) {
            return $query->whereDate('created_at', '<=', $end_date);
        })
        ->where('estado_emissao', 'aprovado')
        ->get();

        $zip = new \ZipArchive();
        $zip_file = public_path('zips') . '/xml-mdfe-'.$doc.'.zip';
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        foreach($data as $item){
            if (file_exists(public_path('xml_mdfe/') . $item->chave . '.xml')) {
                $filename = public_path('xml_mdfe/') . $item->chave . '.xml';
                $zip->addFile($filename, $item->chave . '.xml');
            }
        }

        $zip->close();
        if (file_exists($zip_file)){
            return response()->download($zip_file, 'mdfe_'.$doc.'.zip');
        }else{
            session()->flash("flash_error", "Não foi possível gerar o arquivo");
            return redirect()->back();
        }
    }
}
