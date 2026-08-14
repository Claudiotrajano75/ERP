<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class HomeCaixaRenderTest extends TestCase
{
    private function actingAsUsuarioCaixa()
    {
        $user = new User([
            'id' => 997,
            'name' => 'Caixa Teste',
            'email' => 'caixa@teste.com',
            'password' => bcrypt('123456'),
        ]);
        $user->id = 997;
        $user->admin = 0; // não-admin => painel com 4 botões
        $user->tipo_contador = 0;

        $this->actingAs($user);
        return $user;
    }

    private function dadosHome()
    {
        return [
            'empresa' => new \stdClass(),
            'totalEmitidoMes' => 0,
            'totalNfeCount' => 0,
            'totalNfceCount' => 0,
            'msgPlano' => '',
            'totalCteCount' => 0,
            'totalMdfeCount' => 0,
            'totalVendasMes' => 0,
            'mes' => 'Agosto',
            'somaVendasMesesAnteriores' => [],
            'totalComprasMes' => 0,
            'somaComprasMesesAnteriores' => [],
        ];
    }

    /** @test */
    public function home_do_caixa_renderiza_com_painel_modernizado()
    {
        $this->actingAsUsuarioCaixa();

        $html = view('home', $this->dadosHome())->render();

        // Header premium
        $this->assertStringContainsString('modulo-header-gradient', $html);
        $this->assertStringContainsString('Painel Inicial', $html);

        // Saudação com o nome do usuário
        $this->assertStringContainsString('Caixa Teste', $html);

        // Cards de ação modernizados
        $this->assertStringContainsString('home-action-card', $html);
        $this->assertStringContainsString('home-action-blue', $html);
        $this->assertStringContainsString('home-action-green', $html);
        $this->assertStringContainsString('home-action-orange', $html);
        $this->assertStringContainsString('home-action-purple', $html);

        // Os 4 botões/ações presentes
        $this->assertStringContainsString('Nova Venda', $html);
        $this->assertStringContainsString('Nova Pré-Venda', $html);
        $this->assertStringContainsString('Novo Produto', $html);
        $this->assertStringContainsString('Novo Cliente', $html);

        // Card Nova Pré-Venda aponta direto para o create
        $this->assertStringContainsString(route('pre-venda.create'), $html);
    }
}
