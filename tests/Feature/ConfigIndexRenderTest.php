<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Empresa;

class ConfigIndexRenderTest extends TestCase
{
    private function actingAsUserBasico()
    {
        $user = new User([
            'id' => 996,
            'name' => 'Operador Teste',
            'email' => 'config@teste.com',
            'password' => bcrypt('123456'),
        ]);
        $user->id = 996;
        $user->admin = 0;
        $user->tipo_contador = 0;

        $this->actingAs($user);
        return $user;
    }

    private function dadosBase()
    {
        return [
            'usuario' => auth()->user(),
            'dadosCertificado' => null,
            'naturezas' => collect([]),
        ];
    }

    /** @test */
    public function pagina_config_renderiza_edicao_com_wizard_e_padrao_da_skill()
    {
        $usuario = $this->actingAsUserBasico();

        $empresa = new \stdClass();
        $empresa->id = 1;

        $item = new Empresa();
        $item->id = 1;
        $item->logo = '';
        $item->cidade_id = null;
        $item->token = '';
        $cidade = new \stdClass();
        $cidade->info = 'Cidade Teste - 0000';
        $item->setRelation('cidade', $cidade);

        $html = view('config.index', array_merge($this->dadosBase(), [
            'empresa' => $empresa,
            'item' => $item,
        ]))->render();

        // Header premium no gradiente padrão da skill
        $this->assertStringContainsString('modulo-header-gradient', $html);
        $this->assertStringContainsString('#0f0c29', $html);
        $this->assertStringContainsString('Configuração da Empresa', $html);

        // Botão Voltar aponta para a home (rota acessível a todos)
        $this->assertStringContainsString(route('home'), $html);

        // Wizard com abas + cards de seção no padrão
        $this->assertStringContainsString('modulo-wizard', $html);
        $this->assertStringContainsString('modulo-section-card', $html);
        $this->assertStringContainsString('Certificado A1', $html);

        // Form de update (método PUT)
        $this->assertStringContainsString('method="post"', $html);
    }

    /** @test */
    public function pagina_config_renderiza_cadastro_quando_sem_empresa()
    {
        $this->actingAsUserBasico();

        $html = view('config.index', array_merge($this->dadosBase(), [
            'empresa' => null,
            'item' => null,
        ]))->render();

        // Form de store presente
        $this->assertStringContainsString('Salvar Empresa', $html);
        $this->assertStringContainsString('Identificação da Empresa', $html);
        $this->assertStringContainsString('modulo-header-gradient', $html);
    }
}
