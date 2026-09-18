<?php

namespace Tests\Unit;

use App\Services\MdfeAutoFillService;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class MdfeAutoFillServiceTest extends TestCase
{
    private MdfeAutoFillService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new MdfeAutoFillService();
    }

    /** @test */
    public function le_nfce_dentro_de_nfeproc_sem_destinatario()
    {
        $xml = file_get_contents(__DIR__ . '/../Fixtures/nfce_sample.xml');

        $nota = $this->service->lerXml($xml);

        $this->assertSame('23260141556663000174650010001000011000665849', $nota['chave']);
        $this->assertSame('100001', $nota['numero']);
        $this->assertSame('41556663000174', $nota['emitente']['documento']);
        $this->assertSame('CE', $nota['emitente']['uf']);
        $this->assertSame(50.00, $nota['valor']);

        // NFC-e sem volumes: cai no fallback da soma das quantidades dos itens
        $this->assertSame('quantidade_itens', $nota['quantidade_origem']);
        $this->assertSame(2.0, $nota['quantidade_itens']);
        $this->assertSame(2.0, $nota['quantidade']);

        // Sem <dest> o destinatário fica vazio em vez de quebrar a leitura
        $this->assertSame('', $nota['destinatario']['uf']);
        $this->assertSame('', $nota['destinatario']['codigo_municipio']);

        $this->assertSame('Linha Bella Fashion Pingouin 150g 9634', $nota['produto_predominante']['nome']);
        $this->assertSame('52054200', $nota['produto_predominante']['ncm']);
        $this->assertSame('7891000668603', $nota['produto_predominante']['cod_barras']);
    }

    /** @test */
    public function le_nfe_com_destinatario_e_peso_dos_volumes()
    {
        $nota = $this->service->lerXml($this->xmlNfeCompleta());

        $this->assertSame('35240612345678000199550010000001231000001234', $nota['chave']);
        $this->assertSame(1234.56, $nota['valor']);
        $this->assertSame('peso_bruto', $nota['quantidade_origem']);
        $this->assertSame(12500.5, $nota['quantidade']);
        $this->assertSame('SP', $nota['emitente']['uf']);
        $this->assertSame('GO', $nota['destinatario']['uf']);
        $this->assertSame('5208707', $nota['destinatario']['codigo_municipio']);
        $this->assertSame('84713012', $nota['produto_predominante']['ncm']);
    }

    /** @test */
    public function ignora_codigo_de_barras_sem_gtin()
    {
        $nota = $this->service->lerXml($this->xmlNfeCompleta());

        $this->assertSame('', $nota['produto_predominante']['cod_barras']);
    }

    /** @test */
    public function recusa_xml_que_nao_e_nfe()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Este XML não é uma NF-e válida.');

        $this->service->lerXml('<?xml version="1.0"?><outro><documento/></outro>');
    }

    private function xmlNfeCompleta(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>'
            . '<nfeProc versao="4.00" xmlns="http://www.portalfiscal.inf.br/nfe">'
            . '<NFe xmlns="http://www.portalfiscal.inf.br/nfe">'
            . '<infNFe Id="NFe35240612345678000199550010000001231000001234" versao="4.00">'
            . '<ide><cUF>35</cUF><nNF>123</nNF><serie>1</serie><mod>55</mod></ide>'
            . '<emit><CNPJ>12345678000199</CNPJ><xNome>TRANSPORTADORA TESTE LTDA</xNome>'
            . '<enderEmit><xLgr>RUA A</xLgr><nro>1</nro><xBairro>CENTRO</xBairro>'
            . '<cMun>3550308</cMun><xMun>SAO PAULO</xMun><UF>SP</UF><CEP>01001000</CEP></enderEmit>'
            . '<IE>111111111</IE></emit>'
            . '<dest><CNPJ>98765432000188</CNPJ><xNome>CLIENTE TESTE LTDA</xNome>'
            . '<enderDest><xLgr>AV B</xLgr><nro>2</nro><xBairro>SETOR</xBairro>'
            . '<cMun>5208707</cMun><xMun>GOIANIA</xMun><UF>GO</UF><CEP>74000000</CEP></enderDest>'
            . '<IE>222222222</IE></dest>'
            . '<det nItem="1"><prod><cProd>1</cProd><cEAN>SEM GTIN</cEAN>'
            . '<xProd>NOTEBOOK 15 POLEGADAS</xProd><NCM>84713012</NCM><CFOP>6102</CFOP>'
            . '<uCom>UN</uCom><qCom>3.0000</qCom><vUnCom>480.0000000000</vUnCom>'
            . '<vProd>1440.00</vProd></prod></det>'
            . '<det nItem="2"><prod><cProd>2</cProd><cEAN>7891234567895</cEAN>'
            . '<xProd>MOUSE USB</xProd><NCM>84716053</NCM><CFOP>6102</CFOP>'
            . '<uCom>UN</uCom><qCom>2.0000</qCom><vUnCom>25.0000000000</vUnCom>'
            . '<vProd>50.00</vProd></prod></det>'
            . '<total><ICMSTot><vProd>1490.00</vProd><vNF>1234.56</vNF></ICMSTot></total>'
            . '<transp><modFrete>1</modFrete>'
            . '<vol><qVol>5</qVol><esp>CAIXA</esp><pesoL>12000.000</pesoL><pesoB>12500.500</pesoB></vol>'
            . '</transp>'
            . '</infNFe></NFe></nfeProc>';
    }
}
