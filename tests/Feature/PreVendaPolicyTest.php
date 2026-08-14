<?php

namespace Tests\Feature;

use App\Models\PreVenda;
use App\Models\User;
use App\Policies\PreVendaPolicy;
use Tests\TestCase;

class PreVendaPolicyTest extends TestCase
{
    private function makeUser(bool $hasPermission)
    {
        $user = \Mockery::mock(User::class)->makePartial();
        $user->shouldReceive('hasPermissionTo')->andReturn($hasPermission);
        return $user;
    }

    private function makePreVenda(int $empresaId)
    {
        return new PreVenda(['empresa_id' => $empresaId]);
    }

    /** @test */
    public function update_permite_com_permissao_e_mesma_empresa()
    {
        request()->merge(['empresa_id' => 5]);

        $policy = new PreVendaPolicy();

        $this->assertTrue($policy->update($this->makeUser(true), $this->makePreVenda(5)));
    }

    /** @test */
    public function update_nega_para_pre_venda_de_outra_empresa()
    {
        request()->merge(['empresa_id' => 5]);

        $policy = new PreVendaPolicy();

        $this->assertFalse($policy->update($this->makeUser(true), $this->makePreVenda(99)));
    }

    /** @test */
    public function update_nega_sem_permissao()
    {
        request()->merge(['empresa_id' => 5]);

        $policy = new PreVendaPolicy();

        $this->assertFalse($policy->update($this->makeUser(false), $this->makePreVenda(5)));
    }

    /** @test */
    public function delete_respeita_permissao_e_empresa()
    {
        request()->merge(['empresa_id' => 5]);

        $policy = new PreVendaPolicy();

        $this->assertTrue($policy->delete($this->makeUser(true), $this->makePreVenda(5)));
        $this->assertFalse($policy->delete($this->makeUser(true), $this->makePreVenda(10)));
        $this->assertFalse($policy->delete($this->makeUser(false), $this->makePreVenda(5)));
    }
}
