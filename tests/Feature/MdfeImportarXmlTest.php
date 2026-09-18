<?php

namespace Tests\Feature;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class MdfeImportarXmlTest extends TestCase
{
    use DatabaseTransactions;

    private function actingAsOperador()
    {
        $user = new User([
            'id' => 998,
            'name' => 'Operador MDF-e',
            'email' => 'mdfe@teste.com',
            'password' => bcrypt('123456'),
        ]);
        $user->id = 998;

        $this->actingAs($user);

        return $user;
    }

    /** @test */
    public function tela_de_emissao_renderiza_com_o_bloco_de_importacao_do_xml()
    {
        $this->actingAsOperador();

        $html = view('mdfe.create', [
            'veiculos' => collect([]),
            'cidades' => collect([]),
            'numeroMDFe' => 10,
        ])->render();

        $this->assertStringContainsString('Emitir Nova MDF-e', $html);
        $this->assertStringContainsString('Emissão automática pelo XML da NF-e', $html);
        $this->assertStringContainsString('id="mdfe-xml-drop"', $html);
        $this->assertStringContainsString('id="inp-mdfe-xml"', $html);
        $this->assertStringContainsString('/js/mdfe_auto.js', $html);
        $this->assertStringContainsString('id="inp-quantidade_carga"', $html);
        $this->assertStringContainsString('id="inp-valor_carga"', $html);
    }

    /** @test */
    public function endpoint_de_importacao_devolve_os_campos_preenchidos()
    {
        $empresa = Empresa::query()->first();

        if ($empresa == null) {
            $this->markTestSkipped('Nenhuma empresa cadastrada para testar a importação do XML.');
        }

        $this->actingAsOperador();

        $xml = new UploadedFile(
            __DIR__ . '/../Fixtures/nfce_sample.xml',
            'nfe.xml',
            'text/xml',
            null,
            true
        );

        $resposta = $this->withoutMiddleware()->postJson('/mdfe/importar-xml', [
            'empresa_id' => $empresa->id,
            'xml' => [$xml],
        ]);

        $resposta->assertOk();

        $this->assertSame(1, $resposta->json('notas'));
        $this->assertSame('23260141556663000174650010001000011000665849', $resposta->json('chaves.0'));
        $this->assertSame('50,00', $resposta->json('campos.valor_carga'));
        $this->assertSame('2,000', $resposta->json('campos.quantidade_carga'));
        $this->assertSame('KG', $resposta->json('campos.unidade_medida'));
        $this->assertSame('52054200', $resposta->json('campos.produto_pred_ncm'));
        $this->assertContains($resposta->json('campos.tp_emit'), ['1', '2']);
        $this->assertIsString($resposta->json('campos.cnpj_contratante'));
        $this->assertIsArray($resposta->json('avisos'));
        $this->assertNotEmpty($resposta->json('avisos'));
    }
}
