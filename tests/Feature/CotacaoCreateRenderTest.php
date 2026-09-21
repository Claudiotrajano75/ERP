<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class CotacaoCreateRenderTest extends TestCase
{
    private function actingAsUserBasico()
    {
        $user = new User([
            'id' => 997,
            'name' => 'Operador Teste',
            'email' => 'teste@teste.com',
            'password' => bcrypt('123456'),
        ]);
        $user->id = 997;

        Gate::before(function () {
            return true;
        });

        $this->actingAs($user);
        return $user;
    }

    public function test_tela_cotacao_create_renderiza_corretamente()
    {
        $this->actingAsUserBasico();

        $fornecedores = collect([
            (object)['id' => 1, 'info' => 'Fornecedor Teste LTDA - 00.000.000/0001-91']
        ]);

        $view = $this->view('cotacoes.create', compact('fornecedores'));

        $view->assertSee('Criar Nova Cotação de Preços');
        $view->assertSee('Grade de Produtos Solicitados');
        $view->assertSee('Adicionar Produto');
        $view->assertSee('Adicionar Mais um Item');
        $view->assertSee('table-produtos');
    }
}
