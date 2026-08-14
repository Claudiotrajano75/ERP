<?php

namespace App\Policies;

use App\Models\PreVenda;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PreVendaPolicy
{
    use HandlesAuthorization;

    /**
     * Permite visualizar apenas pré-vendas da empresa do contexto atual.
     */
    public function view(User $user, PreVenda $preVenda): bool
    {
        return $user->hasPermissionTo('pre_venda_view')
            && $preVenda->empresa_id == request()->empresa_id;
    }

    /**
     * Permite editar apenas pré-vendas da empresa do contexto atual.
     */
    public function update(User $user, PreVenda $preVenda): bool
    {
        return $user->hasPermissionTo('pre_venda_edit')
            && $preVenda->empresa_id == request()->empresa_id;
    }

    /**
     * Permite excluir apenas pré-vendas da empresa do contexto atual.
     */
    public function delete(User $user, PreVenda $preVenda): bool
    {
        return $user->hasPermissionTo('pre_venda_delete')
            && $preVenda->empresa_id == request()->empresa_id;
    }
}
