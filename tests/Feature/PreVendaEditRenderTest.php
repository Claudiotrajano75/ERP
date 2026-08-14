<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class PreVendaEditRenderTest extends TestCase
{
    private function actingAsUserBasico()
    {
        $user = new User([
            'id' => 997,
            'name' => 'Operador Teste',
            'email' => 'prevendaedit@teste.com',
            'password' => bcrypt('123456'),
        ]);
        $user->id = 997;

        // Habilita todas as permissões (@can) para renderização dos botões de ação
        Gate::before(function () {
            return true;
        });

        $this->actingAs($user);
        return $user;
    }

    private function makeItem()
    {
        $produto = new \stdClass();
        $produto->id = 10;
        $produto->nome = 'Produto Teste';
        $produto->img = '';

        // Usa o model real (ArrayAccess) porque o FormBuilder lê $formData[$name] via fill()
        $item = new \App\Models\PreVenda([
            'id' => 1,
            'codigo' => 'ABC123',
            'cliente_id' => null,
            'status' => 1,
            'valor_total' => 150.5,
            'tipo_pagamento' => '01',
            'observacao' => '',
            'usuario_id' => 997,
            'empresa_id' => 1,
        ]);
        $item->id = 1;
        $item->cliente = null;
        $item->vendedor = null;
        $item->fatura = collect([
            (object)['tipo_pagamento' => '01', 'data_vencimento' => '2026-08-13', 'valor' => 150.5, 'obs_row' => ''],
        ]);

        $item->itens = collect([
            (object)[
                'key' => 0,
                'produto' => $produto,
                'quantidade' => 2,
                'valor_unitario' => 75.25,
            ],
        ]);

        return $item;
    }

    /** @test */
    public function tela_de_edicao_renderiza_formulario_preenchido()
    {
        $this->actingAsUserBasico();

        $item = $this->makeItem();

        // $abertura precisa ser um model Eloquent: o _forms faz {{ $abertura }} (serializa como JSON)
        $abertura = new \App\Models\Caixa();
        $abertura->id = 1;

        $html = view('pre_venda.edit', [
            'item' => $item,
            'itens' => $item->itens,
            'cliente' => null,
            'funcionario' => null,
            'abertura' => $abertura,
            'categorias' => collect([(object)['id' => 1, 'nome' => 'Geral']]),
            'funcionarios' => collect([(object)['id' => 1, 'nome' => 'Vendedor Teste']]),
            'naturezas' => collect([(object)['id' => 1]]),
            'caixa' => null,
            'tiposPagamento' => ['01' => 'Dinheiro', '17' => 'PIX'],
        ])->render();

        // Formulário envia PUT para pre-venda.update
        $this->assertStringContainsString('form-pre-venda', $html);
        $this->assertStringContainsString(route('pre-venda.update', 1), $html);

        // Banner deixando claro que é uma PRÉ-VENDA
        $this->assertStringContainsString('Editando PRÉ-VENDA', $html);
        $this->assertStringContainsString('auditoria', $html);

        // Itens da pré-venda pré-preenchidos
        $this->assertStringContainsString('Produto Teste', $html);
        $this->assertStringContainsString('75,25', $html);

        // Total e tipo de pagamento preenchidos
        $this->assertStringContainsString('150,50', $html);

        // JS de pré-venda carregado
        $this->assertStringContainsString('pre_venda.js', $html);
    }
}
