<style>
/* ─── Seções do formulário ─── */
.uf-section-title { display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 700; color: #1f2937; border-bottom: 1px solid #eef0f6; padding-bottom: 10px; margin-bottom: 16px; }
.uf-section-title .uf-ico { width: 30px; height: 30px; border-radius: 9px; background: #eef0ff; color: #4f46e5; display: inline-flex; align-items: center; justify-content: center; font-size: 15px; }
.uf-section-title small { font-weight: 500; color: #94a3b8; font-size: 12px; margin-left: auto; }

/* ─── Campos ─── */
.uf-field label, .uf-field .form-label { display: block; font-size: 13px !important; font-weight: 600 !important; color: #374151 !important; margin-bottom: 4px !important; }
.uf-field .form-label i, .uf-field label i { color: #64748b; font-size: 13px; }
.uf-field .form-control, .uf-field .form-select { height: 40px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13.5px; color: #1f2937; background: #fcfdfe; transition: all .15s ease; }
.uf-field .form-control:focus, .uf-field .form-select:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }

/* ─── Rodapé de ações ─── */
.uf-actions { display: flex; align-items: center; justify-content: flex-end; gap: 10px; border-top: 1px solid #eef0f6; padding-top: 16px; margin-top: 20px; }
</style>

<div class="row g-4">

    <!-- ═══ SEÇÃO: INFORMAÇÕES BÁSICAS ═══ -->
    <div class="col-12 uf-section">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-folder-open-line"></i></span>
            1. Informações da Categoria
            <small>dados cadastrais e visibilidade</small>
        </div>
        <div class="row g-3">
            <div class="col-md-6 col-12 uf-field">
                <label class="form-label" for="inp-nome"><i class="ri-price-tag-3-line"></i> Nome da Categoria</label>
                {!!Form::text('nome', '')
                ->placeholder('Ex: Manutenção, Consultoria, Estética, Banho e Tosa')
                ->required()
                ->attrs(['class' => 'form-control', 'id' => 'inp-nome'])!!}
            </div>

            @if(__isActivePlan(Auth::user()->empresa, 'Delivery'))
            <div class="col-md-4 col-12 uf-field">
                <label class="form-label"><i class="ri-store-2-line"></i> Visível no Marketplace / Delivery</label>
                {!!Form::select('marketplace', '', [0 => 'Não', 1 => 'Sim'])
                ->attrs(['class' => 'form-select'])!!}
                <div class="form-text text-muted fs-11 mt-1">
                    Marque "Sim" para disponibilizar esta categoria nos serviços do Delivery/Marketplace.
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- ═══ RODAPÉ COM BOTÕES ═══ -->
    <div class="col-12">
        <div class="uf-actions">
            <a href="{{ route('categoria-servico.index') }}" class="dash-btn dash-btn-light px-4">
                <i class="ri-close-line"></i> Cancelar
            </a>
            <button type="submit" class="dash-btn dash-btn-primary px-4" id="btn-store">
                <i class="ri-save-line"></i> {{ ($formType ?? '') === 'edit' ? 'Salvar Alterações' : 'Salvar Categoria' }}
            </button>
        </div>
    </div>

</div>

