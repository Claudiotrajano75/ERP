<?php

namespace Tests\Feature;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Caixa;
use App\Models\Empresa;
use App\Models\ItemPreVenda;
use App\Models\NaturezaOperacao;
use App\Models\Plano;
use App\Models\PlanoEmpresa;
use App\Models\PreVenda;
use App\Models\PreVendaAuditoria;
use App\Models\Produto;
use App\Models\User;
use App\Models\UsuarioEmpresa;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PreVendaUpdateIntegrationTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        // O ambiente de teste roda com env=local (o APP_ENV do phpunit.xml não sobrescreve o .env),
        // então o middleware de CSRF continua ativo — desativamos apenas neste arquivo de integração.
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }

    /**
     * Monta o ambiente completo (empresa, plano, usuário vinculado, natureza)
     * para passar pelos middlewares auth/verificaEmpresa/validaPlano.
     */
    private function ambienteCompleto(): array
    {
        $empresa = Empresa::create([
            'nome' => 'Empresa Teste',
            'cpf_cnpj' => '12345678000190',
        ]);

        $user = User::create([
            'name' => 'Usuário Teste',
            'email' => Str::random(6) . '@teste.com',
            'password' => bcrypt('123456'),
        ]);

        UsuarioEmpresa::create([
            'usuario_id' => $user->id,
            'empresa_id' => $empresa->id,
        ]);

        $plano = Plano::create([
            'nome' => 'Plano Teste',
            'descricao' => 'Plano de teste',
            'maximo_nfes' => 999,
            'maximo_nfces' => 999,
            'maximo_ctes' => 999,
            'maximo_mdfes' => 999,
            'maximo_usuarios' => 10,
            'maximo_locais' => 10,
            'imagem' => '',
            'valor' => 100,
            'intervalo_dias' => 30,
            'modulos' => '[]',
            'auto_cadastro' => 0,
            'fiscal' => 1,
        ]);

        PlanoEmpresa::create([
            'empresa_id' => $empresa->id,
            'plano_id' => $plano->id,
            'data_expiracao' => now()->addYear()->format('Y-m-d'),
            'valor' => 100,
            'forma_pagamento' => 'boleto',
        ]);

        $natureza = NaturezaOperacao::create([
            'empresa_id' => $empresa->id,
            'descricao' => 'Venda',
        ]);

        return compact('empresa', 'user', 'natureza');
    }

    private function makePreVenda(array $ambiente, array $attrs = []): PreVenda
    {
        $preVenda = new PreVenda(array_merge([
            'empresa_id' => $ambiente['empresa']->id,
            'usuario_id' => $ambiente['user']->id,
            'natureza_id' => $ambiente['natureza']->id,
            'valor_total' => 100,
            'desconto' => 0,
            'acrescimo' => 0,
            'observacao' => '',
            'codigo' => Str::random(8),
            'status' => 1,
        ], $attrs));

        // Coluna NOT NULL fora do $fillable do model
        $preVenda->tipo_finalizado = 'nfce';
        $preVenda->save();

        return $preVenda;
    }

    private function darPermissao(User $user, string $permissao): void
    {
        $permission = Permission::firstOrCreate(['name' => $permissao, 'guard_name' => 'web']);
        $user->givePermissionTo($permission);
    }

    private function abrirCaixa(array $ambiente): Caixa
    {
        return Caixa::create([
            'empresa_id' => $ambiente['empresa']->id,
            'usuario_id' => $ambiente['user']->id,
            'valor_abertura' => 0,
            'observacao' => '',
            'status' => 1,
        ]);
    }

    /** @test */
    public function usuario_sem_permissao_nao_consegue_editar()
    {
        $ambiente = $this->ambienteCompleto();
        // A permissão existe no sistema, mas NÃO foi concedida ao usuário
        Permission::firstOrCreate(['name' => 'pre_venda_edit', 'guard_name' => 'web']);

        $this->actingAs($ambiente['user'])
            ->get(route('pre-venda.edit', 1))
            ->assertForbidden();
    }

    /** @test */
    public function prevenda_recebida_nao_pode_ser_alterada()
    {
        $ambiente = $this->ambienteCompleto();
        $this->darPermissao($ambiente['user'], 'pre_venda_edit');
        $this->abrirCaixa($ambiente); // a tela de edição exige caixa aberto antes de validar o status
        $preVenda = $this->makePreVenda($ambiente, ['status' => 0]);

        // GET da tela de edição redireciona
        $this->actingAs($ambiente['user'])
            ->get(route('pre-venda.edit', $preVenda->id))
            ->assertRedirect(route('pre-venda.index'))
            ->assertSessionHas('flash_error');

        // PUT do update também redireciona, sem tocar nos dados
        $this->actingAs($ambiente['user'])
            ->put(route('pre-venda.update', $preVenda->id), ['produto_id' => [1]])
            ->assertRedirect(route('pre-venda.index'))
            ->assertSessionHas('flash_error');

        $this->assertDatabaseMissing('pre_venda_auditorias', ['pre_venda_id' => $preVenda->id]);
    }

    /** @test */
    public function prevenda_convertida_em_venda_nao_pode_ser_alterada()
    {
        $ambiente = $this->ambienteCompleto();
        $this->darPermissao($ambiente['user'], 'pre_venda_edit');
        $preVenda = $this->makePreVenda($ambiente, ['status' => 1, 'venda_id' => 999]);

        $this->actingAs($ambiente['user'])
            ->put(route('pre-venda.update', $preVenda->id), ['produto_id' => [1]])
            ->assertRedirect(route('pre-venda.index'))
            ->assertSessionHas('flash_error');

        $this->assertDatabaseMissing('pre_venda_auditorias', ['pre_venda_id' => $preVenda->id]);
    }

    /** @test */
    public function registro_de_auditoria_e_criado_corretamente()
    {
        $ambiente = $this->ambienteCompleto();
        $preVenda = $this->makePreVenda($ambiente);

        PreVendaAuditoria::registrar(
            $preVenda->id,
            'UPDATE_DESCONTO',
            null,
            ['desconto' => 10.0],
            ['desconto' => 5.0],
            $ambiente['empresa']->id,
            $ambiente['user']->id
        );

        $this->assertDatabaseHas('pre_venda_auditorias', [
            'pre_venda_id' => $preVenda->id,
            'tipo_operacao' => 'UPDATE_DESCONTO',
            'usuario_id' => $ambiente['user']->id,
            'empresa_id' => $ambiente['empresa']->id,
        ]);

        $registro = PreVendaAuditoria::where('pre_venda_id', $preVenda->id)->first();
        $this->assertNull($registro->item_id);
        $this->assertEquals(['desconto' => 10.0], json_decode($registro->valores_antes, true));
        $this->assertEquals(['desconto' => 5.0], json_decode($registro->valores_depois, true));
    }

    /** @test */
    public function update_completo_gera_registros_de_auditoria()
    {
        $ambiente = $this->ambienteCompleto();
        $this->darPermissao($ambiente['user'], 'pre_venda_edit');

        $produto = Produto::create([
            'empresa_id' => $ambiente['empresa']->id,
            'nome' => 'Produto Teste',
            'ncm' => '00000000',
            'unidade' => 'UN',
            'valor_unitario' => 10,
        ]);

        $preVenda = $this->makePreVenda($ambiente);
        ItemPreVenda::create([
            'pre_venda_id' => $preVenda->id,
            'produto_id' => $produto->id,
            'quantidade' => 2,
            'valor' => 10,
            'observacao' => '',
            'cfop' => 0,
        ]);

        // Altera a quantidade (2 → 3) e aplica desconto (0 → 5)
        $this->actingAs($ambiente['user'])
            ->put(route('pre-venda.update', $preVenda->id), [
                'produto_id' => [$produto->id],
                'quantidade' => ['3,00'],
                'valor_unitario' => ['10,00'],
                'subtotal_item' => ['30,00'],
                'valor_total' => '30,00',
                'desconto' => '5,00',
                'acrescimo' => '0,00',
                'tipo_pagamento' => '01',
                'data_vencimento' => '2026-08-13',
                'observacao' => '',
                'cliente_id' => null,
                'funcionario_id' => null,
            ])
            ->assertRedirect(route('pre-venda.index'))
            ->assertSessionHas('flash_success');

        $tipos = PreVendaAuditoria::where('pre_venda_id', $preVenda->id)
            ->pluck('tipo_operacao')
            ->all();

        $this->assertContains('UPDATE_QTD', $tipos);
        $this->assertContains('UPDATE_DESCONTO', $tipos);
        $this->assertContains('UPDATE_VALOR_TOTAL', $tipos);

        // Registro de quantidade guarda antes/depois corretos
        $qtd = PreVendaAuditoria::where('pre_venda_id', $preVenda->id)
            ->where('tipo_operacao', 'UPDATE_QTD')
            ->first();
        $this->assertEquals(['quantidade' => 2], json_decode($qtd->valores_antes, true));
        $this->assertEquals(['quantidade' => 3], json_decode($qtd->valores_depois, true));

        // Dados persistidos na pré-venda
        $this->assertEquals(3, $preVenda->fresh()->itens()->first()->quantidade);
        $this->assertEquals(5, $preVenda->fresh()->desconto);
    }

    /** @test */
    public function model_pode_ser_editada_respeita_status_e_venda()
    {
        $ambiente = $this->ambienteCompleto();

        $aberta = $this->makePreVenda($ambiente, ['status' => 1]);
        $recebida = $this->makePreVenda($ambiente, ['status' => 0]);
        $convertida = $this->makePreVenda($ambiente, ['status' => 1, 'venda_id' => 999]);

        $this->assertTrue($aberta->podeSerEditada());
        $this->assertFalse($recebida->podeSerEditada());
        $this->assertFalse($convertida->podeSerEditada());
    }
}
