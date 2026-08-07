<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class CaixaIndexRenderTest extends TestCase
{
    private function actingAsUserBasico()
    {
        // Sem relação empresa setada: o layout usa isset(Auth::user()->empresa->empresa)
        // e o helper __countLocalAtivo() trata null retornando 0.
        $user = new User([
            'id' => 999,
            'name' => 'Operador Teste',
            'email' => 'caixa@teste.com',
            'password' => bcrypt('123456'),
        ]);
        $user->id = 999;

        $this->actingAs($user);
        return $user;
    }

    private function makeCaixa()
    {
        $usuario = new \stdClass();
        $usuario->name = 'Operador Teste';

        $item = new \stdClass();
        $item->id = 1;
        $item->status = 1;
        $item->valor_abertura = 100.0;
        $item->created_at = now();
        $item->observacao = '';
        $item->usuario = $usuario;
        $item->localizacao = null;
        $item->contaEmpresa = null;

        return $item;
    }

    private function makeVenda($tipo)
    {
        $v = new \stdClass();
        $v->tipo = $tipo;
        $v->created_at = now();
        $v->total = 50.0;
        $v->valor = 50.0;
        return $v;
    }

    /** @test */
    public function tela_caixa_renderiza_com_cards_coloridos_e_componentes_modernos()
    {
        $this->actingAsUserBasico();
        $item = $this->makeCaixa();

        $html = view('caixa.index', [
            'item' => $item,
            'vendas' => [$this->makeVenda('PDV'), $this->makeVenda('NFe')],
            'somaTiposPagamento' => ['01' => 50.0, '17' => 50.0],
            'valor_abertura' => 100.0,
            'somaServicos' => 0,
            'suprimentos' => collect([]),
            'sangrias' => collect([]),
            'contas' => [],
            'somaTiposContas' => [],
            'receber' => collect([]),
            'pagar' => collect([]),
            'contasEmpresa' => collect([]),
        ])->render();

        // Cards de KPI coloridos (padrão widget-icon-box da skill)
        $this->assertStringContainsString('widget-icon-box', $html);
        $this->assertStringContainsString('text-bg-success', $html);
        $this->assertStringContainsString('text-bg-info', $html);
        $this->assertStringContainsString('text-bg-danger', $html);
        $this->assertStringContainsString('text-bg-warning', $html);

        // Tabelas no padrão modulo-table-wrap
        $this->assertStringContainsString('modulo-table-wrap', $html);

        // Abas preservadas
        $this->assertStringContainsString('id="caixaTabs"', $html);
        $this->assertStringContainsString('id="vendas-pane"', $html);
        $this->assertStringContainsString('id="contas-pane"', $html);
        $this->assertStringContainsString('id="suprimentos-pane"', $html);

        // Modal de fechamento modernizada
        $this->assertStringContainsString('id="fechamento_caixa"', $html);
        $this->assertStringContainsString('modal-pdv-modern', $html);
    }

    /** @test */
    public function tela_caixa_renderiza_estado_fechado()
    {
        $this->actingAsUserBasico();
        $item = $this->makeCaixa();
        $item->status = 0;

        $html = view('caixa.index', [
            'item' => $item,
            'vendas' => [],
            'somaTiposPagamento' => [],
            'valor_abertura' => 0,
            'somaServicos' => 0,
            'suprimentos' => collect([]),
            'sangrias' => collect([]),
            'contas' => [],
            'somaTiposContas' => [],
            'receber' => collect([]),
            'pagar' => collect([]),
            'contasEmpresa' => collect([]),
        ])->render();

        $this->assertStringContainsString('Caixa Fechado', $html);
        $this->assertStringContainsString('Abrir Caixa', $html);
        $this->assertStringContainsString('/caixa/create', $html);
    }
}
