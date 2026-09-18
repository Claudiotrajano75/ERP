@forelse ($itens as $item)
<tr>
    <td>
        <div class="d-flex align-items-center gap-2">
            @if(!empty($item->lote))
                <span class="badge" style="background: #eef2ff; color: #4338ca; font-size: 12.5px; font-weight: 700; padding: 6px 12px; border-radius: 6px; border: 1px solid #c7d2fe;">
                    <i class="ri-barcode-line me-1"></i>{{ $item->lote }}
                </span>
            @else
                <span class="badge" style="background: #f1f5f9; color: #64748b; font-size: 12px; font-weight: 500; padding: 6px 10px; border-radius: 6px;">
                    Sem Lote
                </span>
            @endif
        </div>
    </td>
    <td>
        @php
            $dataVenc = !empty($item->data_vencimento) ? \Carbon\Carbon::parse($item->data_vencimento) : null;
            $hoje = \Carbon\Carbon::today();
            $diasParaVencer = $dataVenc ? $hoje->diffInDays($dataVenc, false) : null;
        @endphp
        @if($dataVenc)
            @if($diasParaVencer < 0)
                <span class="badge" style="background: #fef2f2; color: #dc2626; font-size: 12px; font-weight: 600; padding: 6px 10px; border-radius: 6px; border: 1px solid #fecaca;">
                    <i class="ri-error-warning-line me-1"></i>{{ $dataVenc->format('d/m/Y') }} <span style="font-size: 10.5px; opacity: 0.85;">(Vencido)</span>
                </span>
            @elseif($diasParaVencer <= 30)
                <span class="badge" style="background: #fffbeb; color: #b45309; font-size: 12px; font-weight: 600; padding: 6px 10px; border-radius: 6px; border: 1px solid #fde68a;">
                    <i class="ri-alarm-warning-line me-1"></i>{{ $dataVenc->format('d/m/Y') }} <span style="font-size: 10.5px; opacity: 0.85;">(Próx. Vencimento)</span>
                </span>
            @else
                <span class="badge" style="background: #f0fdf4; color: #15803d; font-size: 12px; font-weight: 600; padding: 6px 10px; border-radius: 6px; border: 1px solid #bbf7d0;">
                    <i class="ri-checkbox-circle-line me-1"></i>{{ $dataVenc->format('d/m/Y') }}
                </span>
            @endif
        @else
            <span class="text-muted" style="font-size: 12px;">--</span>
        @endif
    </td>
    <td>
        <div class="d-flex align-items-center gap-1">
            <span class="fw-bold text-dark" style="font-size: 13.5px;">
                {{ number_format($item->quantidade, 2, ',', '.') }}
            </span>
            <span class="text-muted fs-11">un</span>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="3" class="text-center py-4 text-muted">
        <div style="padding: 16px;">
            <i class="ri-inbox-2-line" style="font-size: 36px; color: #cbd5e1; display: block; margin-bottom: 6px;"></i>
            <span style="font-size: 13px; color: #64748b;">Nenhum registro de lote ou vencimento cadastrado para este produto.</span>
        </div>
    </td>
</tr>
@endforelse
