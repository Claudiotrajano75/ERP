<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;

class PreVendaIndexRenderTest extends TestCase
{
    private function actingAsUserBasico()
    {
        // Sem relação empresa setada: o helper __countLocalAtivo() trata null retornando 0.
        $user = new User([
            'id' => 998,
            'name' => 'Operador Teste',
            'email' => 'prevenda@teste.com',
            'password' => bcrypt('123456'),
        ]);
        $user->id = 998;

        // Habilita todas as permissões (@can) para renderização dos botões de ação
        Gate::before(function () {
            return true;
        });

        $this->actingAs($user);
        return $user;
    }

    private function makePreVenda($status = 1, $codigo = 'ABC123')
    {
        $item = new \stdClass();
        $item->id = 1;
        $item->codigo = $codigo;
        $item->cliente_id = null;
        $item->cliente = null;
        $item->status = $status;
        $item->venda_id = null;
        $item->tipo_finalizado = null;
        $item->nfce = null;
        $item->localizacao = null;
        $item->created_at = now();
        $item->valor_total = 150.5;

        return $item;
    }

    /** @test */
    public function pagina_pre_venda_renderiza_no_padrao_modulo()
    {
        $this->actingAsUserBasico();

        $items = [$this->makePreVenda(1), $this->makePreVenda(0, 'XYZ789')];
        $data = new LengthAwarePaginator(collect($items), 2, 10, 1);

        $html = view('pre_venda.index', compact('data'))->render();

        // Header premium
        $this->assertStringContainsString('modulo-header-gradient', $html);
        $this->assertStringContainsString('Pré-vendas', $html);

        // KPI cards no padrão widget-icon-box
        $this->assertStringContainsString('widget-icon-box', $html);
        $this->assertStringContainsString('text-bg-info', $html);
        $this->assertStringContainsString('text-bg-success', $html);
        $this->assertStringContainsString('text-bg-warning', $html);
        $this->assertStringContainsString('text-bg-primary', $html);

        // Tudo dentro do card-body p-4 (painéis alinhados ao grid)
        $this->assertStringContainsString('card-body p-4', $html);
        $this->assertStringContainsString('modulo-glass-filter', $html);
        $this->assertStringContainsString('modulo-table-wrap', $html);
        $this->assertStringContainsString('modulo-footer', $html);

        // Badges e ações no padrão
        $this->assertStringContainsString('modulo-badge', $html);
        $this->assertStringContainsString('modulo-action-group', $html);

        // Linhas da tabela renderizadas
        $this->assertStringContainsString('ABC123', $html);
        $this->assertStringContainsString('XYZ789', $html);
        $this->assertStringContainsString('Consumidor Final', $html);
    }

    /** @test */
    public function pagina_pre_venda_renderiza_estado_vazio()
    {
        $this->actingAsUserBasico();

        $data = new LengthAwarePaginator(collect([]), 0, 10, 1);

        $html = view('pre_venda.index', compact('data'))->render();

        $this->assertStringContainsString('Nenhuma pré-venda encontrada', $html);
        $this->assertStringContainsString('modulo-empty', $html);
    }

    /** @test */
    public function pre_venda_pendente_renderiza_botao_de_edicao()
    {
        $this->actingAsUserBasico();

        $items = [$this->makePreVenda(1)];
        $data = new LengthAwarePaginator(collect($items), 1, 10, 1);

        $html = view('pre_venda.index', compact('data'))->render();

        // Botão de edição no padrão do PDV: amarelo com lápis, apontando para pre-venda.edit
        $this->assertStringContainsString('Editar pré-venda', $html);
        $this->assertStringContainsString('ri-pencil-line', $html);
        $this->assertStringContainsString('href="' . route('pre-venda.edit', 1) . '"', $html);
    }

    /** @test */
    public function pre_venda_recebida_nao_renderiza_botao_de_edicao()
    {
        $this->actingAsUserBasico();

        $items = [$this->makePreVenda(0, 'XYZ789')];
        $data = new LengthAwarePaginator(collect($items), 1, 10, 1);

        $html = view('pre_venda.index', compact('data'))->render();

        // Pré-venda recebida (status 0) não pode ser editada — nenhum link de edição
        $this->assertStringNotContainsString('Editar pré-venda', $html);
        $this->assertStringNotContainsString(route('pre-venda.edit', 1), $html);
    }

    /** @test */
    public function historico_de_auditoria_renderiza_para_todas_as_prevendas()
    {
        $this->actingAsUserBasico();

        $items = [$this->makePreVenda(1), $this->makePreVenda(0, 'XYZ789')];
        $data = new LengthAwarePaginator(collect($items), 2, 10, 1);

        $html = view('pre_venda.index', compact('data'))->render();

        // Botão de histórico aparece tanto para pendente quanto para recebida (leitura sempre permitida)
        $this->assertEquals(2, substr_count($html, route('pre-venda.auditoria', 1)));
        $this->assertStringContainsString('ri-history-line', $html);
    }
}
