<style>
/* ─── Seções do formulário ─── */
.uf-section { margin-bottom: 24px; }
.uf-section-title { display: flex; align-items: center; gap: 10px; font-size: 13.5px; font-weight: 700; color: #1f2937; border-bottom: 1px solid #eef0f6; padding-bottom: 10px; margin-bottom: 16px; }
.uf-section-title .uf-ico { width: 28px; height: 28px; border-radius: 8px; background: #eef0ff; color: #4f46e5; display: inline-flex; align-items: center; justify-content: center; font-size: 14px; }
.uf-section-title small { font-weight: 500; color: #94a3b8; font-size: 11.5px; margin-left: auto; }

/* ─── Campos ─── */
.uf-field label, .uf-field .form-label, label { display: block; font-size: 12.5px !important; font-weight: 600 !important; color: #374151 !important; margin-bottom: 4px !important; }
.uf-field .form-control, .uf-field .form-select, .form-control, .form-select { height: 40px; border-radius: 10px; border: 1px solid #dcdce9; font-size: 13px; color: #1f2937; background: #fcfdfe; transition: all .15s ease; }
.uf-field .form-control:focus, .uf-field .form-select:focus, .form-control:focus, .form-select:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }

/* ─── Rodapé de ações ─── */
.uf-actions { display: flex; align-items: center; justify-content: flex-end; gap: 10px; border-top: 1px solid #eef0f6; padding-top: 16px; margin-top: 24px; }
</style>

<div class="row g-4">
    <div class="col-12 uf-section">
        <div class="uf-section-title">
            <span class="uf-ico"><i class="ri-price-tag-3-line"></i></span>
            Dados do Tipo de Despesa de Frete
            <small>informações e status de disponibilidade</small>
        </div>
        <div class="row g-3">
            <div class="col-md-8 col-12 uf-field">
                {!!Form::text('nome', 'Nome / Descrição da Despesa')->placeholder('Ex: Pedágio, Combustível, Ajudante, Chapa...')->required()->attrs(['class' => 'form-control'])!!}
            </div>

            <div class="col-md-4 col-12 uf-field">
                {!!Form::select('status', 'Status de Ativação', ['1' => 'Sim (Ativo)', '0' => 'Não (Inativo)'])->required()->attrs(['class' => 'form-select'])!!}
            </div>
        </div>
    </div>

    <!-- ═══ RODAPÉ DE AÇÕES ═══ -->
    <div class="col-12">
        <div class="uf-actions">
            <a href="{{ route('tipo-despesa-frete.index') }}" class="dash-btn dash-btn-light px-4">
                <i class="ri-close-line"></i> Cancelar
            </a>
            <button type="submit" class="dash-btn dash-btn-primary px-5" id="btn-store">
                <i class="ri-save-line"></i>
                {{ isset($item) ? 'Salvar Alterações' : 'Cadastrar Tipo de Despesa' }}
            </button>
        </div>
    </div>
</div>


