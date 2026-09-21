<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Produto;
use App\Models\MovimentacaoProduto;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;

class EstoqueAjusteRenderTest extends TestCase
{
    private function actingAsUserBasico()
    {
        // Sem relação empresa setada: o helper __countLocalAtivo() trata null retornando 0,
        // então o modal de ajuste renderiza sem o select de localização.
        $user = new User([
            'id' => 997,
            'name' => 'Operador Teste',
            'email' => 'estoque@teste.com',
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

    private function makeMovimentacao($tipo = 'incremento', $tipoTransacao = 'alteracao_estoque', $observacao = null)
    {
        $item = new MovimentacaoProduto([
            'produto_id' => 1,
            'quantidade' => 2.5,
            'tipo' => $tipo,
            'codigo_transacao' => 0,
            'tipo_transacao' => $tipoTransacao,
            'user_id' => 997,
            'estoque_atual' => 10,
        ]);
        if ($observacao !== null) {
            $item->observacao = $observacao;
        }

        // Relações pré-carregadas para evitar consulta ao banco durante o render
        $item->setRelation('produto', new Produto(['nome' => 'Produto Teste Ajuste']));
        $item->setRelation('produtoVariacao', null);
        $item->setRelation('user', new User(['name' => 'Operador Teste']));
        $item->created_at = now();

        return $item;
    }

    /** @test */
    public function tela_estoque_renderiza_modal_entrada_saida_completo()
    {
        $this->actingAsUserBasico();

        $data = new LengthAwarePaginator(collect([]), 0, 10, 1);

        $html = view('estoque.index', ['data' => $data, 'stats' => [
            'total_produtos' => 0,
            'total_itens' => 0,
            'valor_estoque' => 0,
            'estoque_baixo' => 0,
        ]])->render();

        // Botão de gatilho do modal no cabeçalho
        $this->assertStringContainsString('data-bs-target="#modal_estoque_ajuste"', $html);
        $this->assertStringContainsString('Entrada/Saída', $html);

        // Modal incluído com formulário apontando para a rota de ajuste
        $this->assertStringContainsString('id="modal_estoque_ajuste"', $html);
        $this->assertStringContainsString('action="' . route('estoque.ajuste') . '"', $html);

        // Campos obrigatórios: tipo, produto (select2) e quantidade
        $this->assertStringContainsString('name="tipo"', $html);
        $this->assertStringContainsString('value="entrada"', $html);
        $this->assertStringContainsString('value="saida"', $html);
        $this->assertStringContainsString('id="estoque_ajuste_produto_id"', $html);
        $this->assertStringContainsString('name="quantidade"', $html);

        // Campo de observação (novo) e botão de lançamento
        $this->assertStringContainsString('name="observacao"', $html);
        $this->assertStringContainsString('Lançar Movimentação', $html);

        // KPI cards do módulo
        $this->assertStringContainsString('stat-card stat-indigo', $html);
        $this->assertStringContainsString('stat-card stat-green', $html);
        $this->assertStringContainsString('stat-card stat-amber', $html);
        $this->assertStringContainsString('stat-card stat-red', $html);
    }

    /** @test */
    public function modal_ajuste_omite_select_de_localizacao_quando_usuario_nao_tem_empresa()
    {
        $this->actingAsUserBasico();

        $data = new LengthAwarePaginator(collect([]), 0, 10, 1);

        $html = view('estoque.index', ['data' => $data, 'stats' => [
            'total_produtos' => 0,
            'total_itens' => 0,
            'valor_estoque' => 0,
            'estoque_baixo' => 0,
        ]])->render();

        // Com __countLocalAtivo() == 0 o select de localização não deve aparecer
        $this->assertStringNotContainsString('name="local_id"', $html);
    }

    /** @test */
    public function tela_movimentacoes_renderiza_observacao_tipo_e_saldo()
    {
        $this->actingAsUserBasico();

        $items = [
            $this->makeMovimentacao('incremento', 'alteracao_estoque', 'Ajuste de inventário'),
            $this->makeMovimentacao('reducao', 'venda_nfce', null),
            $this->makeMovimentacao('incremento', 'compra', null),
        ];
        $data = new LengthAwarePaginator(collect($items), 3, 10, 1);

        $html = view('estoque.movimentacoes', ['data' => $data, 'stats' => [
            'total' => 3,
            'entradas' => 2.5,
            'saidas' => 2.5,
            'ajustes' => 1,
        ]])->render();

        // Header e KPI cards
        $this->assertStringContainsString('Movimentações de Estoque', $html);
        $this->assertStringContainsString('stat-card stat-indigo', $html);
        $this->assertStringContainsString('Ajustes Manuais', $html);

        // Coluna de observação exibindo o valor lançado
        $this->assertStringContainsString('<th>Observação</th>', $html);
        $this->assertStringContainsString('Ajuste de inventário', $html);

        // Pills de entrada/saída e origem
        $this->assertStringContainsString('pill-ok', $html);
        $this->assertStringContainsString('pill-red', $html);
        $this->assertStringContainsString('Venda NFCe', $html);
        $this->assertStringContainsString('Compra', $html);

        // Quantidade com sinal e saldo após movimentação
        $this->assertStringContainsString('+2,500', $html);
        $this->assertStringContainsString('-2,500', $html);
        $this->assertStringContainsString('Saldo Após', $html);

        // Filtros de tipo e origem presentes
        $this->assertStringContainsString('value="alteracao_estoque"', $html);
        $this->assertStringContainsString('value="venda_nfe"', $html);
    }

    /** @test */
    public function tela_movimentacoes_renderiza_estado_vazio()
    {
        $this->actingAsUserBasico();

        $data = new LengthAwarePaginator(collect([]), 0, 10, 1);

        $html = view('estoque.movimentacoes', ['data' => $data, 'stats' => [
            'total' => 0,
            'entradas' => 0,
            'saidas' => 0,
            'ajustes' => 0,
        ]])->render();

        $this->assertStringContainsString('Nenhuma movimentação encontrada', $html);
    }

    /** @test */
    public function observacao_longa_e_truncada_na_listagem()
    {
        $this->actingAsUserBasico();

        $longa = str_repeat('A', 100);
        $items = [$this->makeMovimentacao('incremento', 'alteracao_estoque', $longa)];
        $data = new LengthAwarePaginator(collect($items), 1, 10, 1);

        $html = view('estoque.movimentacoes', ['data' => $data, 'stats' => [
            'total' => 1, 'entradas' => 2.5, 'saidas' => 0, 'ajustes' => 1,
        ]])->render();

        // Str::limit corta em 40 caracteres na tabela
        $this->assertStringContainsString(str_repeat('A', 40), $html);
        $this->assertStringNotContainsString(str_repeat('A', 41), $html);
    }

    /** @test */
    public function helper_converte_quantidade_formatada_para_padrao_bd()
    {
        // Mesma conversão usada pelo EstoqueController@ajuste
        $this->assertEquals('1.500', __convert_value_bd('1,500'));
        $this->assertEquals('1234.56', __convert_value_bd('1.234,56'));
        $this->assertEquals('10', __convert_value_bd('10'));
    }
}
