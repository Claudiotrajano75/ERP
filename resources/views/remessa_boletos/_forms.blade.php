<div class="row g-3 text-dark">
    <!-- Tabela de Boletos para Seleção -->
    <div class="col-12 mt-2">
        <div class="table-responsive">
            <table class="table table-centered table-hover align-middle mb-0 text-dark">
                <thead class="table-light">
                    <tr>
                        <th style="width: 40px;">
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="checkbox" id="select-all-checkbox">
                            </div>
                        </th>
                        <th>Cliente Pagador</th>
                        <th>Valor</th>
                        <th>Data de Emissão</th>
                        <th>Vencimento</th>
                        <th>Banco / Carteira</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                    <tr>
                        <td>
                            <div class="form-check mb-0">
                                <input class="form-check-input check-delete" type="checkbox" name="boleto_id[]" value="{{ $item->id }}">
                            </div>
                        </td>
                        <td class="fw-semibold text-dark">{{ $item->contaReceber->cliente->info }}</td>
                        <td class="fw-bold text-dark">R$ {{ __moeda($item->valor) }}</td>
                        <td class="text-muted fs-13">{{ __data_pt($item->created_at) }}</td>
                        <td>{{ __data_pt($item->vencimento, 0) }}</td>
                        <td>
                            <span class="badge bg-light text-dark border px-2 py-1">
                                {{ $item->contaBoleto->banco }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Nenhum boleto pendente de remessa encontrado para os parâmetros informados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Botões de Ações -->
    <div class="col-12 mt-4">
        <hr class="text-muted opacity-25">
        <div class="d-flex align-items-center justify-content-end gap-2">
            <a href="{{ route('remessa-boleto.index') }}" class="btn btn-light px-4">Cancelar</a>
            <button type="submit" class="btn btn-success px-4" id="btn-store">
                <i class="ri-file-zip-line align-middle me-1"></i> Gerar Remessa
            </button>
        </div>
    </div>
</div>

@section('js')
<script type="text/javascript">
    $("#select-all-checkbox").on("click", function (e) {
        if($(this).is(':checked')){
            $('.check-delete').prop('checked', true);
        } else {
            $('.check-delete').prop('checked', false);
        }
    });
</script>
@endsection
