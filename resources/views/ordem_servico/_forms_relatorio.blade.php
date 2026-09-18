<style>
/* ─── Campos ─── */
.uf-field label, .uf-field .form-label { display: block; font-size: 13px !important; font-weight: 600 !important; color: #374151 !important; margin-bottom: 4px !important; }
.uf-field .form-label i, .uf-field label i { color: #64748b; font-size: 13px; }
.uf-field textarea.form-control { border-radius: 10px; border: 1px solid #dcdce9; font-size: 13.5px; color: #1f2937; background: #fcfdfe; transition: all .15s ease; }
.uf-field textarea.form-control:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); background: #fff; }

/* ─── Rodapé de ações ─── */
.uf-actions { display: flex; align-items: center; justify-content: flex-end; gap: 10px; border-top: 1px solid #eef0f6; padding-top: 16px; margin-top: 20px; }
</style>

<input type="hidden" value="{{$ordem->id}}" name="ordem_servico_id">
<div class="row g-3">
    <div class="col-12 uf-field">
        <label class="form-label required"><i class="ri-file-text-line"></i> Descrição Detalhada do Relatório</label>
        {!! Form::textarea('texto', '')->required()->attrs(['class' => 'form-control', 'rows' => '6', 'placeholder' => 'Informe as ações tomadas, testes efetuados ou laudo deste período...']) !!}
    </div>
    
    <div class="col-12">
        <div class="uf-actions">
            <a href="{{ route('ordem-servico.show', $ordem->id) }}" class="dash-btn dash-btn-light px-4">
                <i class="ri-close-line"></i> Cancelar
            </a>
            <button type="submit" class="dash-btn dash-btn-primary px-4">
                <i class="ri-save-line"></i> Salvar Relatório
            </button>
        </div>
    </div>
</div>

