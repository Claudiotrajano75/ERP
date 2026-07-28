<?php

namespace Tests\Unit;

use App\Services\SpeedService;
use Tests\TestCase;

class SpeedServiceTest extends TestCase
{
    private SpeedService $service;
    private string $fixturePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SpeedService();
        $this->fixturePath = __DIR__ . '/../Fixtures/nfce_sample.xml';
    }

    private function loadSampleXml(): \stdClass
    {
        $this->assertFileExists($this->fixturePath, 'Fixture XML não encontrada');

        $content = file_get_contents($this->fixturePath);

        // Remove namespaces (mesma lógica do SpeedService)
        $content = preg_replace('/<(\/?)[a-zA-Z0-9_]+:([a-zA-Z0-9_]+)/', '<$1$2', $content);

        $xml = simplexml_load_string($content);
        $this->assertNotFalse($xml, 'Falha ao fazer parse do XML fixture');

        return json_decode(json_encode($xml));
    }

    /** @test */
    public function it_gets_emitente_from_xml()
    {
        $xml = $this->loadSampleXml();
        $emitente = $this->service->getEmitente($xml);

        $this->assertNotNull($emitente, 'Emitente não encontrado no XML');
        $this->assertTrue(isset($emitente->CNPJ) || isset($emitente->CPF));
        $this->assertTrue(isset($emitente->xNome));
        $this->assertTrue(isset($emitente->enderEmit));
    }

    /** @test */
    public function it_gets_destinatario_from_xml()
    {
        $xml = $this->loadSampleXml();
        $destinatario = $this->service->getDestinatario($xml);

        // NFC-e para consumidor final NÃO identificado não tem destinatário — é válido
        if ($destinatario) {
            $this->assertTrue(isset($destinatario->xNome) || isset($destinatario->CPF));
            $this->assertTrue(isset($destinatario->enderDest));
        } else {
            $this->assertNull($destinatario, 'NFC-e sem destinatário é válido (consumidor final)');
        }
    }

    /** @test */
    public function it_gets_ide_from_xml()
    {
        $xml = $this->loadSampleXml();
        $ide = $this->service->getIde($xml);

        $this->assertNotNull($ide);
        $this->assertTrue(isset($ide->mod));
        $this->assertTrue(isset($ide->serie));
        $this->assertTrue(isset($ide->nNF));
        $this->assertTrue(isset($ide->dhEmi));
        $this->assertTrue(isset($ide->cUF));
    }

    /** @test */
    public function it_identifies_nfce_model()
    {
        $xml = $this->loadSampleXml();
        $ide = $this->service->getIde($xml);

        $this->assertEquals('65', (string)$ide->mod, 'Esperado modelo 65 para NFC-e');
    }

    /** @test */
    public function it_gets_chave_from_xml()
    {
        $xml = $this->loadSampleXml();
        $chave = $this->service->getChave($xml);

        $this->assertNotNull($chave);
        // Chave de acesso NFe tem 44 dígitos
        $this->assertEquals(44, strlen(preg_replace('/[^0-9]/', '', $chave)));
    }

    /** @test */
    public function it_gets_total_from_xml()
    {
        $xml = $this->loadSampleXml();
        $total = $this->service->getTotal($xml);

        $this->assertNotNull($total);
        $this->assertTrue(isset($total->vNF));
        $this->assertTrue(isset($total->vProd));
        $this->assertTrue(isset($total->vBC));
    }

    /** @test */
    public function it_gets_items_from_xml()
    {
        $xml = $this->loadSampleXml();
        $items = $this->service->getItemNfe($xml);

        $this->assertNotEmpty($items, 'Nenhum item encontrado no XML');
        $this->assertIsArray($items);

        foreach ($items as $item) {
            $this->assertTrue(isset($item->prod), 'Item sem nó prod');
            $this->assertTrue(isset($item->imposto), 'Item sem nó imposto');
            $this->assertTrue(isset($item->prod->cProd), 'Item sem código do produto');
            $this->assertTrue(isset($item->prod->xProd), 'Item sem descrição');
            $this->assertTrue(isset($item->prod->qCom), 'Item sem quantidade');
            $this->assertTrue(isset($item->prod->vProd), 'Item sem valor');
        }
    }

    /** @test */
    public function it_gets_icms_from_items()
    {
        $xml = $this->loadSampleXml();
        $items = $this->service->getItemNfe($xml);

        $this->assertNotEmpty($items);

        foreach ($items as $item) {
            $imposto = $item->imposto;
            $this->assertTrue(isset($imposto->ICMS), 'Item sem ICMS');

            $icmsArr = array_values((array)$imposto->ICMS);
            $this->assertNotEmpty($icmsArr);

            $firstIcms = $icmsArr[0];
            // NFC-e do Simples Nacional usa CSOSN (102, 300, 400, etc.)
            $this->assertTrue(
                isset($firstIcms->CST) || isset($firstIcms->CSOSN),
                'ICMS sem CST ou CSOSN'
            );
        }
    }

    /** @test */
    public function it_returns_null_for_nonexistent_xml()
    {
        $model = new \stdClass();
        $model->chave = '00000000000000000000000000000000000000000000';

        $result = $this->service->getXml($model, 'xml_nfce/');
        $this->assertNull($result);
    }
}
