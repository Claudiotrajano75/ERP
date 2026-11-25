<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plano;
use App\Models\ConfiguracaoSuper;
use App\Models\SolicitacaoDemonstracao;
use Illuminate\Support\Facades\Log;

class LandingController extends Controller
{
    /**
     * Exibe a Landing Page inicial do sistema ERP.
     */
    public function index()
    {
        $config = ConfiguracaoSuper::first();

        $planos = Plano::where('status', 1)
            ->where('visivel_clientes', 1)
            ->orderBy('valor', 'asc')
            ->get();

        if ($planos->isEmpty()) {
            $planos = Plano::where('status', 1)
                ->orderBy('valor', 'asc')
                ->limit(3)
                ->get();
        }

        return view('landing.index', compact('config', 'planos'));
    }

    /**
     * Recebe solicitações de demonstração / contato da Landing Page.
     */
    public function enviarContato(Request $request)
    {
        $validated = $request->validate([
            'nome'     => 'required|string|max:120',
            'email'    => 'required|email|max:150',
            'empresa'  => 'nullable|string|max:150',
            'telefone' => 'required|string|max:40',
            'mensagem' => 'nullable|string|max:1000',
        ], [
            'nome.required'     => 'Por favor, informe seu nome.',
            'email.required'    => 'Por favor, informe seu e-mail.',
            'email.email'       => 'Informe um e-mail válido.',
            'telefone.required' => 'Por favor, informe seu telefone ou WhatsApp.',
        ]);

        $solicitacao = SolicitacaoDemonstracao::create($validated);

        Log::channel('daily')->info('Novo lead de demonstracao via Landing Page', $validated);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Solicitação enviada com sucesso! Nossa equipe entrará em contato em breve.'
            ]);
        }

        return redirect('/#contato')->with('landing_success', 'Solicitação enviada com sucesso! Nossa equipe entrará em contato em breve.');
    }
}

