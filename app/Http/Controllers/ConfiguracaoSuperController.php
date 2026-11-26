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
                $ext = strtolower($file->getClientOriginalExtension());
                $fileName = 'logo_' . time() . '.' . $ext;
                $destPath = public_path('uploads/logo/' . $fileName);
                $file->move(public_path('uploads/logo'), $fileName);

                $this->recortarMargensLogo($destPath, $ext);

                $item->logo = $fileName;
                $item->save();

                session()->flash("flash_success", "Logo atualizada com sucesso!");
            }
        } catch (\Exception $e) {
            session()->flash("flash_error", "Erro ao fazer upload da logo: " . $e->getMessage());
        }

        return redirect()->back();
    }

    public function updateLoginBanner(Request $request)
    {
        $rules = [
            'login_banner' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ];
        $messages = [
            'login_banner.required' => 'O campo imagem é obrigatório',
            'login_banner.image' => 'O arquivo enviado deve ser uma imagem',
            'login_banner.mimes' => 'A imagem deve ser do tipo jpeg, png, jpg, gif ou webp',
            'login_banner.max' => 'A imagem não pode ser maior que 5MB',
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

            if ($request->hasFile('login_banner')) {
                if (!file_exists(public_path('uploads/login'))) {
                    mkdir(public_path('uploads/login'), 0777, true);
                }
                if ($item->login_banner && file_exists(public_path('uploads/login/' . $item->login_banner))) {
                    @unlink(public_path('uploads/login/' . $item->login_banner));
                }
                $file = $request->file('login_banner');
                $ext = $file->getClientOriginalExtension();
                $fileName = 'login_' . time() . '.' . $ext;
                $file->move(public_path('uploads/login'), $fileName);

                $item->login_banner = $fileName;
                $item->save();
                session()->flash("flash_success", "Imagem do login atualizada com sucesso!");
            }
        } catch (\Exception $e) {
            session()->flash("flash_error", "Erro ao fazer upload da imagem: " . $e->getMessage());
        }
        return redirect()->back();
    }

    public function store(Request $request)
    {
        $this->__validate($request);
        $item = ConfiguracaoSuper::first();
        $request->merge([
            'name' => $request->name ?? '',
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
            'email' => 'required',
            'telefone' => 'required',
        ];
        $messages = [
            'cpf_cnpj.required' => 'Campo obrigatório',
            'email.required' => 'Campo obrigatório',
            'telefone.required' => 'Campo obrigatório'
        ];
        $this->validate($request, $rules, $messages);
    }

    /**
     * Remove automaticamente o excesso de margem branca/transparente ao redor da logo.
     */
    private function recortarMargensLogo(string $path, string $ext): void
    {
        if (!function_exists('imagecreatefrompng') || !file_exists($path)) {
            return;
        }

        $img = match ($ext) {
            'png' => @imagecreatefrompng($path),
            'jpg', 'jpeg' => @imagecreatefromjpeg($path),
            'webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : null,
            default => null,
        };

        if (!$img) {
            return;
        }

        $w = imagesx($img);
        $h = imagesy($img);
        $minX = $w;
        $minY = $h;
        $maxX = 0;
        $maxY = 0;

        for ($y = 0; $y < $h; $y++) {
            for ($x = 0; $x < $w; $x++) {
                $rgba = imagecolorat($img, $x, $y);
                $a = ($rgba >> 24) & 0x7F;
                $r = ($rgba >> 16) & 0xFF;
                $g = ($rgba >> 8) & 0xFF;
                $b = $rgba & 0xFF;
                if ($a < 120 && ($r < 245 || $g < 245 || $b < 245)) {
                    if ($x < $minX) $minX = $x;
                    if ($x > $maxX) $maxX = $x;
                    if ($y < $minY) $minY = $y;
                    if ($y > $maxY) $maxY = $y;
                }
            }
        }

        if ($maxX > $minX && $maxY > $minY) {
            $pad = 20;
            $cropX = max(0, $minX - $pad);
            $cropY = max(0, $minY - $pad);
            $cropW = min($w - $cropX, ($maxX - $minX + 1) + $pad * 2);
            $cropH = min($h - $cropY, ($maxY - $minY + 1) + $pad * 2);

            $cropped = imagecrop($img, ['x' => $cropX, 'y' => $cropY, 'width' => $cropW, 'height' => $cropH]);
            if ($cropped) {
                if ($ext === 'png') {
                    imagesavealpha($cropped, true);
                    imagepng($cropped, $path, 6);
                } elseif ($ext === 'webp' && function_exists('imagewebp')) {
                    imagewebp($cropped, $path, 90);
                } else {
                    imagejpeg($cropped, $path, 92);
                }
                imagedestroy($cropped);
            }
        }

        imagedestroy($img);
    }
}
