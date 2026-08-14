<?php

namespace Tests\Unit;

use App\Models\PreVendaAuditoria;
use PHPUnit\Framework\TestCase;

class PreVendaAuditoriaDiffTest extends TestCase
{
    /** @test */
    public function detecta_item_adicionado()
    {
        $operacoes = PreVendaAuditoria::diffItens([], [
            ['item_id' => null, 'produto_id' => 10, 'quantidade' => 2, 'valor' => 10.0],
        ]);

        $this->assertCount(1, $operacoes);
        $this->assertEquals('ADD_ITEM', $operacoes[0]['tipo_operacao']);
        $this->assertNull($operacoes[0]['valores_antes']);
        $this->assertEquals(10, $operacoes[0]['valores_depois']['produto_id']);
    }

    /** @test */
    public function detecta_item_removido()
    {
        $operacoes = PreVendaAuditoria::diffItens([
            ['item_id' => 5, 'produto_id' => 10, 'quantidade' => 2, 'valor' => 10.0],
        ], []);

        $this->assertCount(1, $operacoes);
        $this->assertEquals('REMOVE_ITEM', $operacoes[0]['tipo_operacao']);
        $this->assertEquals(5, $operacoes[0]['item_id']);
        $this->assertNull($operacoes[0]['valores_depois']);
    }

    /** @test */
    public function detecta_mudanca_de_quantidade()
    {
        $operacoes = PreVendaAuditoria::diffItens(
            [['item_id' => 5, 'produto_id' => 10, 'quantidade' => 2, 'valor' => 10.0]],
            [['item_id' => null, 'produto_id' => 10, 'quantidade' => 5, 'valor' => 10.0]]
        );

        $this->assertCount(1, $operacoes);
        $this->assertEquals('UPDATE_QTD', $operacoes[0]['tipo_operacao']);
        $this->assertEquals(['quantidade' => 2], $operacoes[0]['valores_antes']);
        $this->assertEquals(['quantidade' => 5], $operacoes[0]['valores_depois']);
    }

    /** @test */
    public function detecta_mudanca_de_valor_unitario()
    {
        $operacoes = PreVendaAuditoria::diffItens(
            [['item_id' => 5, 'produto_id' => 10, 'quantidade' => 2, 'valor' => 10.0]],
            [['item_id' => null, 'produto_id' => 10, 'quantidade' => 2, 'valor' => 12.5]]
        );

        $this->assertCount(1, $operacoes);
        $this->assertEquals('UPDATE_VALOR_ITEM', $operacoes[0]['tipo_operacao']);
        $this->assertEquals(['valor' => 10.0], $operacoes[0]['valores_antes']);
        $this->assertEquals(['valor' => 12.5], $operacoes[0]['valores_depois']);
    }

    /** @test */
    public function nao_gera_operacao_quando_item_nao_mudou()
    {
        $antes = [['item_id' => 5, 'produto_id' => 10, 'quantidade' => 2, 'valor' => 10.0]];
        $depois = [['item_id' => null, 'produto_id' => 10, 'quantidade' => 2, 'valor' => 10.0]];

        $this->assertSame([], PreVendaAuditoria::diffItens($antes, $depois));
    }

    /** @test */
    public function detecta_alteracoes_no_cabecalho()
    {
        $operacoes = PreVendaAuditoria::diffCabecalho(
            ['desconto' => 10.0, 'acrescimo' => 0, 'valor_total' => 100.0, 'cliente_id' => 1, 'tipo_pagamento' => '01', 'observacao' => 'obs'],
            ['desconto' => 5.0, 'acrescimo' => 2.0, 'valor_total' => 100.0, 'cliente_id' => 2, 'tipo_pagamento' => '01', 'observacao' => 'obs']
        );

        $tipos = array_column($operacoes, 'tipo_operacao');
        $this->assertContains('UPDATE_DESCONTO', $tipos);
        $this->assertContains('UPDATE_ACRESCIMO', $tipos);
        $this->assertContains('UPDATE_CLIENTE', $tipos);

        // Sem alteração não gera operação
        $this->assertNotContains('UPDATE_VALOR_TOTAL', $tipos);
        $this->assertNotContains('UPDATE_PAGAMENTO', $tipos);
        $this->assertNotContains('UPDATE_OBSERVACAO', $tipos);

        $desconto = $operacoes[0];
        $this->assertEquals(['desconto' => 10.0], $desconto['valores_antes']);
        $this->assertEquals(['desconto' => 5.0], $desconto['valores_depois']);
        $this->assertNull($desconto['item_id']);
    }
}
