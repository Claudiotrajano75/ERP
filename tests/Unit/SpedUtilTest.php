<?php

namespace Tests\Unit;

use App\Utils\SpedUtil;
use PHPUnit\Framework\TestCase;

class SpedUtilTest extends TestCase
{
    private SpedUtil $util;

    protected function setUp(): void
    {
        parent::setUp();
        $this->util = new SpedUtil();
    }

    /** @test */
    public function it_creates_first_c190_entry()
    {
        $std = new \stdClass();
        $std->CST_ICMS = '102';
        $std->CFOP = '5102';
        $std->VL_OPR = 100.00;
        $std->VL_ICMS = 0.00;
        $std->VL_BC_ICMS = 100.00;

        $result = $this->util->updateOrCreateC190([], $std);

        $this->assertCount(1, $result);
        $this->assertSame('102', $result[0]->CST_ICMS);
        $this->assertSame('5102', $result[0]->CFOP);
        $this->assertSame(100.00, $result[0]->VL_OPR);
    }

    /** @test */
    public function it_merges_duplicate_cst_cfop()
    {
        $std1 = new \stdClass();
        $std1->CST_ICMS = '102';
        $std1->CFOP = '5102';
        $std1->VL_OPR = 100.00;
        $std1->VL_ICMS = 0.00;
        $std1->VL_BC_ICMS = 100.00;

        $std2 = new \stdClass();
        $std2->CST_ICMS = '102';
        $std2->CFOP = '5102';
        $std2->VL_OPR = 50.00;
        $std2->VL_ICMS = 0.00;
        $std2->VL_BC_ICMS = 50.00;

        $result = $this->util->updateOrCreateC190([$std1], $std2);

        $this->assertCount(1, $result);
        $this->assertSame(150.00, $result[0]->VL_OPR);
        $this->assertSame(150.00, $result[0]->VL_BC_ICMS);
    }

    /** @test */
    public function it_separates_different_cst_or_cfop()
    {
        $std1 = new \stdClass();
        $std1->CST_ICMS = '102';
        $std1->CFOP = '5102';
        $std1->VL_OPR = 100.00;
        $std1->VL_ICMS = 0.00;
        $std1->VL_BC_ICMS = 100.00;

        $std2 = new \stdClass();
        $std2->CST_ICMS = '200';
        $std2->CFOP = '5102';
        $std2->VL_OPR = 50.00;
        $std2->VL_ICMS = 10.00;
        $std2->VL_BC_ICMS = 50.00;

        $result = $this->util->updateOrCreateC190([$std1], $std2);

        $this->assertCount(2, $result);
        $this->assertSame(100.00, $result[0]->VL_OPR);
        $this->assertSame(50.00, $result[1]->VL_OPR);
    }

    /** @test */
    public function it_returns_same_cfop_for_venda()
    {
        $result = $this->util->trataCfop('5102', 'venda', 0);
        $this->assertSame('5102', $result);

        $result = $this->util->trataCfop('5102', 'pdv', 0);
        $this->assertSame('5102', $result);
    }

    /** @test */
    public function it_returns_same_cfop_for_non_imported()
    {
        $result = $this->util->trataCfop('5102', 'compra', 0);
        $this->assertSame('5102', $result);
    }

    /** @test */
    public function it_converts_imported_cfop_to_state()
    {
        // CFOP 5xxx (interestadual) → 1xxx (entrada)
        $result = $this->util->trataCfop('5102', 'compra', 1);
        $this->assertSame('1102', $result);

        // CFOP 6xxx → 2xxx
        $result = $this->util->trataCfop('6102', 'compra', 1);
        $this->assertSame('2102', $result);
    }
}
