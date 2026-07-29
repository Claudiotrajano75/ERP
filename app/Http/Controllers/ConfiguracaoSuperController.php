<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracaoSuper;
use Illuminate\Http\Request;

class ConfiguracaoSuperController extends Controller
{
    public function index()
    {
        $item = ConfiguracaoSuper::first();
        return view('config_super.index', compact('item'));
    }

    public function logoForm()
    {
        $item = ConfiguracaoSuper::first();
        return view('config_geral_admin.index', compact('item'));
    }

    public function updateLogo(Request $request)
    {
        $rules = [
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ];
        $messages = [
            'logo.required' => 'O campo logo é obrigatório',
            'logo.image' => 'O arquivo enviado deve ser uma imagem',
            'logo.mimes' => 'A imagem deve ser do tipo jpeg, png, jpg, gif, svg ou webp',
            'logo.max' => 'A imagem não pode ser maior que 2MB',
        ];
        $this->validate($request, $rules, $messages);

        try {
            $item = ConfiguracaoSuper::first();
            if ($item == null) {
                $item = ConfiguracaoSuper::create([
                    'cpf_cnpj' => '00000000000000',
                    'name' => 'Configuração Geral',
                    'email' => 'admin@admin.com',
                    'telefone' => '0000000000',
                ]);
            }

            if ($request->hasFile('logo')) {
                // Criar pasta se não existir
                if (!file_exists(public_path('uploads/logo'))) {
                    mkdir(public_path('uploads/logo'), 0777, true);
                }

                // Remove logo antiga se existir
                if ($item->logo && file_exists(public_path('uploads/logo/' . $item->logo))) {
                    @unlink(public_path('uploads/logo/' . $item->logo));
                }

                $file = $request->file('logo');
                $ext = $file->getClientOriginalExtension();
                $fileName = 'logo_' . time() . '.' . $ext;
                $file->move(public_path('uploads/logo'), $fileName);

                $item->logo = $fileName;
                $item->save();

                session()->flash("flash_success", "Logo atualizada com sucesso!");
            }
        } catch (\Exception $e) {
            session()->flash("flash_error", "Erro ao fazer upload da logo: " . $e->getMessage());
        }

        return redirect()->back();
    }

    public function store(Request $request)
    {
        $this->__validate($request);
        $item = ConfiguracaoSuper::first();
        $request->merge([
            'timeout_nfe' => $request->timeout_nfe ?? 8,
            'timeout_nfce' => $request->timeout_nfe ?? 8,
            'timeout_cte' => $request->timeout_nfe ?? 8,
            'timeout_mdfe' => $request->timeout_nfe ?? 8,
        ]);
        try {
            if ($item == null) {
                ConfiguracaoSuper::create($request->all());
                session()->flash("flash_success", "Dados cadastrado com sucesso!");
            } else {
                $item->fill($request->all())->save();
                session()->flash("flash_success", "Dados alterados com sucesso!");
            }
        } catch (\Exception $e) {
            session()->flash("flash_error", "Algo deu errado: " . $e->getMessage());
        }
        return redirect()->back();
    }

    private function __validate(Request $request)
    {
        $rules = [
            'cpf_cnpj' => 'required',
            'name' => 'required',
            'email' => 'required',
            'telefone' => 'required',
        ];
        $messages = [
            'cpf_cnpj.required' => 'Campo obrigatório',
            'name.required' => 'Campo obrigatório',
            'email.required' => 'Campo obrigatório',
            'telefone.required' => 'Campo obrigatório'
        ];
        $this->validate($request, $rules, $messages);
    }
}
