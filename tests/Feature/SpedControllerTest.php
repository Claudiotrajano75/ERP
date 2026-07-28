<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class SpedControllerTest extends TestCase
{
    /** @test */
    public function guests_cannot_access_sped_page()
    {
        $response = $this->get('/sped');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function guests_cannot_generate_sped()
    {
        $this->withoutMiddleware([
            \App\Http\Middleware\VerifyCsrfToken::class,
        ]);

        $response = $this->post('/sped', [
            'data_inicial' => '2026-06-01',
            'data_final' => '2026-06-30',
        ]);

        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_view_sped_form()
    {
        $user = new User([
            'id' => 1,
            'name' => 'Test',
            'email' => 'test@test.com',
            'password' => bcrypt('123456'),
        ]);
        $user->id = 1;

        $this->actingAs($user);

        $response = $this->get('/sped');

        $response->assertStatus(200);
        $response->assertViewIs('sped.index');
        $response->assertSee('SPED');
        $response->assertSee('data_inicial');
        $response->assertSee('data_final');
    }

    /** @test */
    public function index_shows_sped_form_with_stats()
    {
        $user = new User([
            'id' => 2,
            'name' => 'Test',
            'email' => 'test2@test.com',
            'password' => bcrypt('123456'),
        ]);
        $user->id = 2;

        $this->actingAs($user);

        $response = $this->get('/sped');

        $response->assertStatus(200);
        $response->assertViewIs('sped.index');
        $response->assertSee('Gerar SPED');
    }

    /**
     * Teste de geração do SPED via store.
     *
     * Este teste requer: banco MySQL com registros, XMLs no filesystem,
     * EscritorioContabil e SpedConfig configurados.
     * Execute manualmente via: php scripts/setup_e_gerar_sped.php {empresa_id}
     */
    public function test_store_generates_sped_with_real_data()
    {
        $this->markTestSkipped(
            'Teste de integração completa requer XMLs no filesystem. ' .
            'Execute: php scripts/setup_e_gerar_sped.php {empresa_id}'
        );
    }
}
