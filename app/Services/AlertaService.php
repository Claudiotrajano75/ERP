<?php

namespace App\Services;

use App\Models\Empresa;
use App\Models\Produto;
use App\Models\Estoque;
use App\Models\ContaPagar;
use App\Models\ContaReceber;
use App\Models\Notificacao;
use App\Models\Nfe;
use App\Models\PlanoEmpresa;
use NFePHP\Common\Certificate;
use Carbon\Carbon;

class AlertaService
{
    /**
     * Sincroniza e gera alertas inteligentes para uma empresa em tempo real.
     */
    public static function sincronizarEmpresa($empresaId)
    {
        try {
            $empresa = Empresa::find($empresaId);
            if (!$empresa || $empresa->status == 0) {
                return;
            }

            // 1. Alerta de Estoque Mínimo / Crítico
            self::sincronizarEstoqueMinimo($empresa);

            // 2. Validade de Lotes / Produtos
            self::sincronizarValidadeLotes($empresa);

            // 3. Contas a Pagar Vencendo Hoje e Atrasadas
            self::sincronizarContasPagar($empresa);

            // 4. Contas a Receber Vencendo Hoje
            self::sincronizarContasReceber($empresa);

            // 5. Certificado Digital
            self::sincronizarCertificado($empresa);

            // 6. NF-e Rejeitadas
            self::sincronizarNfeRejeitada($empresa);

            // 7. Plano / Assinatura Expirando
            self::sincronizarPlano($empresa);

        } catch (\Throwable $e) {
            // Silencia falhas para não quebrar requisições da UI
        }
    }

    private static function sincronizarValidadeLotes(Empresa $empresa)
    {
        $produtos = Produto::where('empresa_id', $empresa->id)
            ->where('status', 1)
            ->where('alerta_validade', '>', 0)
            ->get();

        foreach ($produtos as $produto) {
            $date = date('Y-m-d', strtotime(date('Y-m-d') . " +{$produto->alerta_validade} days"));
            $itens = \App\Models\ItemNfe::where('produto_id', $produto->id)
                ->whereDate('data_vencimento', '<=', $date)
                ->whereDate('data_vencimento', '>=', date('Y-m-d'))
                ->with('produto')
                ->get();

            foreach ($itens as $i) {
                $venc = $i->data_vencimento ? Carbon::parse($i->data_vencimento)->format('d/m/Y') : '';
                $descricaoCurta = "Lote vencerá em {$venc}: " . ($i->produto ? $i->produto->nome : 'Produto');
                self::criaNotificacao('compras', 'lote_venc_' . $i->id, $empresa, 'Alerta de validade', $descricaoCurta, $i, 'media');
            }
        }
    }

    private static function sincronizarEstoqueMinimo(Empresa $empresa)
    {
        $produtos = Produto::where('empresa_id', $empresa->id)
            ->where('status', 1)
            ->where('estoque_minimo', '>', 0)
            ->with('estoque')
            ->get();

        foreach ($produtos as $produto) {
            $qtd = $produto->estoque ? (float)$produto->estoque->quantidade : 0;
            $ref = 'prod_est_' . $produto->id;

            if ($qtd <= (float)$produto->estoque_minimo) {
                $isZerado = $qtd <= 0;
                $descricaoCurta = $produto->nome . " (Qtd: " . ($qtd == (int)$qtd ? (int)$qtd : number_format($qtd, 2)) . " / Mín: " . (int)$produto->estoque_minimo . ")";
                
                $objEstoque = $produto->estoque ?: (object)[
                    'id' => $produto->id,
                    'produto' => $produto,
                    'quantidade' => $qtd,
                ];

                self::criaNotificacao(
                    'estoques',
                    $ref,
                    $empresa,
                    $isZerado ? 'Estoque Zerado / Esgotado' : 'Alerta de estoque mínimo',
                    $descricaoCurta,
                    $objEstoque,
                    $isZerado ? 'urgente' : 'media'
                );
            } else {
                // Se o estoque foi normalizado/reposto, encerra notificações pendentes deste produto
                Notificacao::where('empresa_id', $empresa->id)
                    ->where('tabela', 'estoques')
                    ->where(function($q) use ($produto, $ref) {
                        $q->where('referencia', $ref)
                          ->orWhere('referencia', (string)$produto->id)
                          ->orWhere('referencia', 'LIKE', 'prod_est_' . $produto->id . '%')
                          ->orWhere('descricao_curta', 'LIKE', $produto->nome . '%');
                    })
                    ->where('visualizada', 0)
                    ->update(['visualizada' => 1]);
            }
        }
    }

    private static function sincronizarContasPagar(Empresa $empresa)
    {
        $hoje = date('Y-m-d');

        // Vencendo hoje
        $contasHoje = ContaPagar::where('empresa_id', $empresa->id)
            ->where('status', 0)
            ->whereDate('data_vencimento', $hoje)
            ->with('fornecedor')
            ->get();

        foreach ($contasHoje as $conta) {
            $fornecedorNome = $conta->fornecedor ? ($conta->fornecedor->razao_social ?: $conta->fornecedor->nome_fantasia) : 'Fornecedor';
            $descCurta = "Vence Hoje: " . $fornecedorNome . " - R$ " . __moeda($conta->valor_integral);
            self::criaNotificacao('conta_pagars', 'pagar_hoje_' . $conta->id, $empresa, 'Conta a pagar Vence Hoje', $descCurta, $conta, 'media');
        }

        // Atrasadas
        $contasAtrasadas = ContaPagar::where('empresa_id', $empresa->id)
            ->where('status', 0)
            ->whereDate('data_vencimento', '<', $hoje)
            ->with('fornecedor')
            ->limit(10)
            ->get();

        foreach ($contasAtrasadas as $conta) {
            $fornecedorNome = $conta->fornecedor ? ($conta->fornecedor->razao_social ?: $conta->fornecedor->nome_fantasia) : 'Fornecedor';
            $descCurta = "Atrasada: " . $fornecedorNome . " - R$ " . __moeda($conta->valor_integral);
            self::criaNotificacao('conta_pagars', 'pagar_atrasada_' . $conta->id, $empresa, 'Conta a pagar ATRASADA', $descCurta, $conta, 'urgente');
        }
    }

    private static function sincronizarContasReceber(Empresa $empresa)
    {
        $hoje = date('Y-m-d');

        $contasHoje = ContaReceber::where('empresa_id', $empresa->id)
            ->where('status', 0)
            ->whereDate('data_vencimento', $hoje)
            ->with('cliente')
            ->get();

        foreach ($contasHoje as $conta) {
            $clienteNome = $conta->cliente ? ($conta->cliente->razao_social ?: $conta->cliente->nome_fantasia) : 'Cliente';
            $descCurta = "Receber Hoje: " . $clienteNome . " - R$ " . __moeda($conta->valor_integral);
            self::criaNotificacao('conta_recebers', 'receber_hoje_' . $conta->id, $empresa, 'Conta a receber Vence Hoje', $descCurta, $conta, 'baixa');
        }
    }

    private static function sincronizarCertificado(Empresa $empresa)
    {
        if (!empty($empresa->arquivo) && !empty($empresa->senha)) {
            try {
                $cert = Certificate::readPfx($empresa->arquivo, $empresa->senha);
                $validTo = $cert->publicKey->validTo;
                $diasRestantes = Carbon::now()->diffInDays($validTo, false);

                if ($diasRestantes <= 30) {
                    $empresa->validade_formatada = $validTo->format('d/m/Y H:i');
                    $prioridade = $diasRestantes <= 7 ? 'urgente' : 'alta';
                    $msg = $diasRestantes < 0
                        ? "Certificado EXPIRADO em " . $validTo->format('d/m/Y')
                        : "Certificado vence em {$diasRestantes} dias (" . $validTo->format('d/m/Y') . ")";

                    $ref = 'cert_' . $empresa->id;
                    self::criaNotificacao('empresas', $ref, $empresa, 'Certificado Digital Vencendo', $msg, $empresa, $prioridade);
                }
            } catch (\Throwable $e) {
            }
        }
    }

    private static function sincronizarNfeRejeitada(Empresa $empresa)
    {
        $nfes = Nfe::where('empresa_id', $empresa->id)
            ->where('estado', 'rejeitado')
            ->where('updated_at', '>=', Carbon::now()->subHours(48))
            ->get();

        foreach ($nfes as $nfe) {
            $desc = "NF-e Nº {$nfe->numero} rejeitada: " . ($nfe->motivo_rejeicao ? substr($nfe->motivo_rejeicao, 0, 80) : 'Erro SEFAZ');
            self::criaNotificacao('nfe', 'nfe_rej_' . $nfe->id, $empresa, 'NF-e Rejeitada SEFAZ', $desc, $nfe, 'urgente');
        }
    }

    private static function sincronizarPlano(Empresa $empresa)
    {
        $planoEmpresa = PlanoEmpresa::where('empresa_id', $empresa->id)
            ->whereDate('data_expiracao', '<=', Carbon::now()->addDays(7)->format('Y-m-d'))
            ->whereDate('data_expiracao', '>=', Carbon::now()->subDays(5)->format('Y-m-d'))
            ->first();

        if ($planoEmpresa != null) {
            $diasPlano = Carbon::now()->diffInDays(Carbon::parse($planoEmpresa->data_expiracao), false);
            $msgPlano = $diasPlano < 0
                ? "Plano expirou em " . Carbon::parse($planoEmpresa->data_expiracao)->format('d/m/Y')
                : "Plano vence em {$diasPlano} dias";
            self::criaNotificacao('planos', 'plano_exp_' . $planoEmpresa->id, $empresa, 'Assinatura Expirando', $msgPlano, $planoEmpresa, 'alta');
        }
    }

    private static function criaNotificacao($tabela, $referencia, Empresa $empresa, $titulo, $descricaoCurta, $objeto, $prioridade = 'baixa')
    {
        $query = Notificacao::where('empresa_id', $empresa->id)
            ->where('tabela', $tabela);

        if ($tabela === 'estoques') {
            $prodId = isset($objeto->produto) ? $objeto->produto->id : ($objeto->id ?? 0);
            $query->where(function($q) use ($referencia, $prodId, $objeto, $descricaoCurta) {
                $q->where('referencia', (string)$referencia)
                  ->orWhere('referencia', (string)$prodId)
                  ->orWhere('referencia', 'LIKE', 'prod_est_' . $prodId . '%')
                  ->orWhere('referencia', (string)($objeto->id ?? -1));

                if (isset($objeto->produto->nome)) {
                    $q->orWhere('descricao_curta', 'LIKE', $objeto->produto->nome . '%');
                }
            });
        } elseif ($tabela === 'conta_pagars' || $tabela === 'conta_recebers') {
            $itemId = $objeto->id ?? 0;
            $query->where(function($q) use ($referencia, $itemId) {
                $q->where('referencia', (string)$referencia)
                  ->orWhere('referencia', (string)$itemId)
                  ->orWhere('referencia', 'LIKE', '%_' . $itemId . '%');
            });
        } else {
            $query->where('referencia', (string)$referencia);
        }

        $existentes = $query->orderBy('id', 'desc')->get();
        $existe = $existentes->first();

        // Se existirem duplicadas históricas para o mesmo item, remove os registros excedentes
        if ($existentes->count() > 1) {
            foreach ($existentes->slice(1) as $dup) {
                $dup->delete();
            }
        }

        $descricaoHtml = self::getDescricaoHtml($tabela, $objeto);

        if (!$existe) {
            Notificacao::create([
                'empresa_id'      => $empresa->id,
                'tabela'          => $tabela,
                'descricao'       => $descricaoHtml,
                'descricao_curta' => $descricaoCurta,
                'referencia'      => (string)$referencia,
                'status'          => 1,
                'por_sistema'     => 1,
                'prioridade'      => $prioridade,
                'visualizada'     => 0,
                'titulo'          => $titulo
            ]);
        } else {
            $existe->referencia = (string)$referencia;
            $existe->titulo = $titulo;
            $existe->descricao = $descricaoHtml;
            $existe->descricao_curta = $descricaoCurta;
            $existe->prioridade = $prioridade;
            $existe->status = 1;
            $existe->save();
        }
    }

    private static function getDescricaoHtml($tabela, $item)
    {
        try {
            if ($tabela == 'conta_recebers') {
                return view('notificacao.partials.conta_receber', compact('item'))->render();
            }
            if ($tabela == 'conta_pagars') {
                return view('notificacao.partials.conta_pagar', compact('item'))->render();
            }
            if ($tabela == 'estoques') {
                return view('notificacao.partials.estoques', compact('item'))->render();
            }
            if ($tabela == 'empresas') {
                return view('notificacao.partials.certificado', compact('item'))->render();
            }
            if ($tabela == 'nfe' || $tabela == 'nfce') {
                return view('notificacao.partials.nfe_rejeitada', compact('item'))->render();
            }
            if ($tabela == 'planos') {
                return view('notificacao.partials.plano_expirando', compact('item'))->render();
            }
        } catch (\Throwable $e) {
            return '';
        }
        return '';
    }
}
