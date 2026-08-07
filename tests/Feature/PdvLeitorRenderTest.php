<?php

namespace Tests\Feature;

use Tests\TestCase;

class PdvLeitorRenderTest extends TestCase
{
    /** @test */
    public function front_box_renderiza_com_botao_liga_desliga_do_leitor()
    {
        // O botão de liga/desliga do leitor deve existir no HTML do PDV
        // e o CSS correspondente deve estar presente no pdv.css.
        $html = file_get_contents(public_path('css/pdv.css'));

        $this->assertStringContainsString('.pdv-leitor-toggle', $html);
        $this->assertStringContainsString('.pdv-leitor-toggle.leitor-on', $html);
        $this->assertStringContainsString('.pdv-leitor-toggle.leitor-off', $html);
        $this->assertStringContainsString('.pdv-leitor-switch', $html);
        $this->assertStringContainsString('.pdv-leitor-label', $html);
    }

    /** @test */
    public function css_das_modais_modernizadas_esta_presente()
    {
        $html = file_get_contents(public_path('css/pdv.css'));

        $this->assertStringContainsString('.modal-pdv.modal-pdv-modern .modal-header.modulo-header-gradient', $html);
        $this->assertStringContainsString('.pdv-modal-stat', $html);
        $this->assertStringContainsString('.pdv-payment-add', $html);
        $this->assertStringContainsString('.pdv-modal-table', $html);
        $this->assertStringContainsString('.pdv-pag-badge', $html);
        $this->assertStringContainsString('.pdv-empty-state', $html);
        $this->assertStringContainsString('.pdv-header-total', $html);
    }
}
