<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class PreVendaAuditoriaRenderTest extends TestCase
{
    private function actingAsUserBasico()
    {
        $user = new User([
            'id' => 995,
            'name' => 'Operador Teste',
            'email' => 'prevendaaudit@teste.com',
            'password' => bcrypt('123456'),
        ]);
        $user->id = 995;

        Gate::before(function () {
            return true;
        });

        $this->actingAs($user);
        return $user;
    }

    private function makeAuditoria($tipo = 'UPDATE_QTD', $antes = null, $depois = null)
    {
        $a = new \stdClass();
        $a->id = 1;
        $a->data_hora = now();
        $a->tipo_operacao = $tipo;
        $a->item_id = 5;
        $a->valores_antes = $antes !== null ? json_encode($antes) : null;
        $a->valores_depois = $depois !== null ? json_encode($depois) : null;
        $a->usuario = (object)['name' => 'Operador Teste'];
        return $a;
    }

    /** @test */
    public function tela_de_auditoria_renderiza_operacoes()
    {
        $this->actingAsUserBasico();

        $item = new \stdClass();
        $item->id = 1;
        $item->codigo = 'ABC123';
        $item->status = 1;

        $auditorias = collect([
            $this->makeAuditoria('ADD_ITEM', null, ['produto_id' => 10, 'quantidade' => 2, 'valor' => 10.0]),
            $this->makeAuditoria('UPDATE_QTD', ['quantidade' => 2], ['quantidade' => 5]),
            $this->makeAuditoria('REMOVE_ITEM', ['produto_id' => 11], null),
            $this->makeAuditoria('UPDATE_DESCONTO', ['desconto' => 10.0], ['desconto' => 5.0]),
        ]);

        $html = view('pre_venda.auditoria', compact('item', 'auditorias'))->render();

        // Header premium + título
        $this->assertStringContainsString('modulo-header-gradient', $html);
        $this->assertStringContainsString('Histórico de Alterações', $html);
        $this->assertStringContainsString('ABC123', $html);

        // Tabela com operações
        $this->assertStringContainsString('ADD_ITEM', $html);
        $this->assertStringContainsString('UPDATE_QTD', $html);
        $this->assertStringContainsString('REMOVE_ITEM', $html);
        $this->assertStringContainsString('UPDATE_DESCONTO', $html);

        // Usuário responsável
        $this->assertStringContainsString('Operador Teste', $html);

        // Valores antes/depois decodificados
        $this->assertStringContainsString('quantidade: 5', $html);
        $this->assertStringContainsString('desconto: 5', $html);
    }

    /** @test */
    public function tela_de_auditoria_renderiza_vazio()
    {
        $this->actingAsUserBasico();

        $item = new \stdClass();
        $item->id = 1;
        $item->codigo = 'ABC123';

        $html = view('pre_venda.auditoria', [
            'item' => $item,
            'auditorias' => collect([]),
        ])->render();

        $this->assertStringContainsString('Nenhuma alteração registrada', $html);
        $this->assertStringContainsString('modulo-empty', $html);
    }
}
