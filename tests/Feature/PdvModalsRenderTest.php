<?php

namespace Tests\Feature;

use Tests\TestCase;

class PdvModalsRenderTest extends TestCase
{
    /** @test */
    public function pagina_pdv_renderiza_com_as_modais_modernizadas()
    {
        // Renderiza cada modal isoladamente para garantir que compilam
        // sem erros de Blade e carregam as classes do padrão premium.
        $modais = [
            'modals._funcionario',
            'modals._cartao_credito',
            'modals._variacao',
            'modals._tef_consulta',
            'modals._novo_cliente',
            'modals._cpf_nota',
        ];

        foreach ($modais as $modal) {
            $html = view($modal)->render();
            $this->assertStringContainsString('modal-pdv-modern', $html, "Modal {$modal} deve ter a classe modal-pdv-modern");
            $this->assertStringContainsString('modulo-header-gradient', $html, "Modal {$modal} deve ter header com modulo-header-gradient");
        }
    }

    /** @test */
    public function modais_modernizadas_renderizam_com_ganchos_js()
    {
        // Funcionário — ganchos usados em frente_caixa.js
        $html = view('modals._funcionario')->render();
        $this->assertStringContainsString('inp-funcionario_id', $html);
        $this->assertStringContainsString('funcionario-venda', $html);

        // Cartão de crédito — campos e botão salvar
        $html = view('modals._cartao_credito')->render();
        $this->assertStringContainsString('bandeira_cartao', $html);
        $this->assertStringContainsString('cAut_cartao', $html);
        $this->assertStringContainsString('cliente-venda', $html);

        // Variação — corpo preenchido por AJAX
        $html = view('modals._variacao')->render();
        $this->assertStringContainsString('modal_variacao', $html);

        // TEF — status e loading
        $html = view('modals._tef_consulta')->render();
        $this->assertStringContainsString('status-tef', $html);
        $this->assertStringContainsString('loading-tef', $html);

        // CPF na nota — botão emitir
        $html = view('modals._cpf_nota')->render();
        $this->assertStringContainsString('btn_fiscal', $html);
        $this->assertStringContainsString('cliente_cpf_cnpj', $html);

        // Novo cliente — botão salvar (novo_cliente.js)
        $html = view('modals._novo_cliente')->render();
        $this->assertStringContainsString('btn-store-cliente', $html);
        $this->assertStringContainsString('novo_cpf_cnpj', $html);
        $this->assertStringContainsString('novo_cidade_id', $html);
    }

    /** @test */
    public function modais_ja_modernizadas_mantem_ganchos_js()
    {
        // Pagamento múltiplo — ganchos usados em frente_caixa.js
        $html = view('modals._pagamento_multiplo', [
            'tiposPagamento' => \App\Models\Nfce::tiposPagamento(),
        ])->render();
        $this->assertStringContainsString('inp-tipo_pagamento_row', $html);
        $this->assertStringContainsString('inp-valor_row', $html);
        $this->assertStringContainsString('btn-add-payment', $html);
        $this->assertStringContainsString('sum-payment', $html);
        $this->assertStringContainsString('sum-restante', $html);
        $this->assertStringContainsString('btn-modal-multiplo', $html);

        // Lista de preços — função selecionaLista
        $html = view('modals._lista_precos', [
            'funcionarios' => collect(),
        ])->render();
        $this->assertStringContainsString('selecionaLista', $html);

        // Vendas suspensas — tabela preenchida via AJAX
        $html = view('modals._vendas_suspensas')->render();
        $this->assertStringContainsString('table-vendas-suspensas', $html);

        // Observação — textarea
        $html = view('modals._observacao_pdv')->render();
        $this->assertStringContainsString('observacao', $html);
    }

    /** @test */
    public function modal_selecionar_cliente_renderiza_com_cashback()
    {
        $html = view('modals._cliente', [
            'cashback' => 1,
        ])->render();

        $this->assertStringContainsString('inp-cliente_id', $html);
        $this->assertStringContainsString('cashback-div', $html);
        $this->assertStringContainsString('valor_cashback', $html);
        $this->assertStringContainsString('permitir_credito', $html);
        $this->assertStringContainsString('modal-select-cliente', $html);

        // O botão de novo cliente é renderizado condicionalmente por @can('clientes_create'),
        // mas o gancho JS deve estar preservado no código-fonte.
        $source = file_get_contents(resource_path('views/modals/_cliente.blade.php'));
        $this->assertStringContainsString('btn-novo-cliente', $source);
    }

    /** @test */
    public function modais_de_suprimento_e_sangria_renderizam_com_rotas()
    {
        $abertura = new \stdClass();
        $abertura->id = 1;

        $html = view('modals._suprimento_caixa', ['abertura' => $abertura])->render();
        $this->assertStringContainsString('suprimento_caixa', $html);
        $this->assertStringContainsString('<form', $html);
        $this->assertStringContainsString('conta_empresa', $html);

        $html = view('modals._sangria_caixa', ['abertura' => $abertura])->render();
        $this->assertStringContainsString('sangria_caixa', $html);
        $this->assertStringContainsString('conta_empresa', $html);
    }
}
