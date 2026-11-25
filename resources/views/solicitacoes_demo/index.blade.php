@extends('layouts.app')

@section('title', 'Demos Solicitadas')

@section('content')

<div class="modulo-header-gradient" style="background:linear-gradient(135deg,#4f46e5 0%,#6366f1 100%);border-radius:16px;padding:28px 32px;margin-bottom:28px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
    <div>
        <h1 style="color:#fff;font-size:1.6rem;font-weight:700;margin:0;letter-spacing:-0.5px;">
            <i class="fa fa-paper-plane" style="margin-right:10px;opacity:.85;"></i>Demos Solicitadas
        </h1>
        <p style="color:rgba(255,255,255,.75);margin:4px 0 0;font-size:.93rem;">Leads que solicitaram demonstração pela landing page</p>
    </div>
</div>

{{-- KPI Cards --}}
@php
    $total      = $kpis['total'] ?? 0;
    $novos      = $kpis['novos'] ?? 0;
    $emAnd      = $kpis['em_atendimento'] ?? 0;
    $concluidos = $kpis['concluidos'] ?? 0;
@endphp
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:16px;margin-bottom:28px;">
    <div style="background:linear-gradient(135deg,#4f46e5,#818cf8);border-radius:14px;padding:20px 22px;color:#fff;box-shadow:0 4px 15px rgba(79,70,229,.25);">
        <div style="font-size:2rem;font-weight:800;">{{ $total }}</div>
        <div style="font-size:.85rem;opacity:.85;margin-top:4px;">Total de Leads</div>
    </div>
    <div style="background:linear-gradient(135deg,#2563eb,#60a5fa);border-radius:14px;padding:20px 22px;color:#fff;box-shadow:0 4px 15px rgba(37,99,235,.25);">
        <div style="font-size:2rem;font-weight:800;">{{ $novos }}</div>
        <div style="font-size:.85rem;opacity:.85;margin-top:4px;">Novos</div>
    </div>
    <div style="background:linear-gradient(135deg,#d97706,#fbbf24);border-radius:14px;padding:20px 22px;color:#fff;box-shadow:0 4px 15px rgba(217,119,6,.25);">
        <div style="font-size:2rem;font-weight:800;">{{ $emAnd }}</div>
        <div style="font-size:.85rem;opacity:.85;margin-top:4px;">Em Atendimento</div>
    </div>
    <div style="background:linear-gradient(135deg,#109f61,#34d399);border-radius:14px;padding:20px 22px;color:#fff;box-shadow:0 4px 15px rgba(16,159,97,.25);">
        <div style="font-size:2rem;font-weight:800;">{{ $concluidos }}</div>
        <div style="font-size:.85rem;opacity:.85;margin-top:4px;">Concluídos</div>
    </div>
</div>

{{-- Filtros --}}
<div style="background:#fff;border-radius:14px;padding:18px 22px;margin-bottom:22px;box-shadow:0 2px 8px rgba(0,0,0,.06);">
    <form method="GET" action="{{ route('solicitacoes-demo.index') }}" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
        <div style="flex:1;min-width:200px;">
            <label style="font-size:.8rem;font-weight:600;color:#64748b;display:block;margin-bottom:5px;">BUSCAR</label>
            <input type="text" name="q" value="{{ request('q') }}"
                   placeholder="Nome, empresa, e-mail ou telefone..."
                   style="width:100%;padding:9px 13px;border:1.5px solid #e2e8f0;border-radius:9px;font-size:.9rem;outline:none;transition:border .2s;"
                   onfocus="this.style.borderColor='#4f46e5'" onblur="this.style.borderColor='#e2e8f0'">
        </div>
        <div style="min-width:180px;">
            <label style="font-size:.8rem;font-weight:600;color:#64748b;display:block;margin-bottom:5px;">STATUS</label>
            <select name="status" style="width:100%;padding:9px 13px;border:1.5px solid #e2e8f0;border-radius:9px;font-size:.9rem;background:#fff;">
                <option value="">Todos</option>
                <option value="novo"           {{ request('status')=='novo'           ? 'selected':'' }}>Novo</option>
                <option value="em_atendimento" {{ request('status')=='em_atendimento' ? 'selected':'' }}>Em Atendimento</option>
                <option value="concluido"      {{ request('status')=='concluido'      ? 'selected':'' }}>Concluído</option>
            </select>
        </div>
        <button type="submit" style="padding:9px 20px;background:#4f46e5;color:#fff;border:none;border-radius:9px;font-weight:600;cursor:pointer;transition:background .2s;" onmouseover="this.style.background='#4338ca'" onmouseout="this.style.background='#4f46e5'">
            <i class="fa fa-search"></i> Buscar
        </button>
        <a href="{{ route('solicitacoes-demo.index') }}" style="padding:9px 18px;background:#f1f5f9;color:#64748b;border-radius:9px;font-weight:600;text-decoration:none;font-size:.9rem;">
            Limpar
        </a>
    </form>
</div>

{{-- Tabela --}}
<div class="tb-wrap" style="background:#fff;border-radius:14px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.06);">
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:#f8fafc;border-bottom:2px solid #e2e8f0;">
                <th style="padding:13px 16px;text-align:left;font-size:.78rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.6px;">#</th>
                <th style="padding:13px 16px;text-align:left;font-size:.78rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.6px;">Nome</th>
                <th style="padding:13px 16px;text-align:left;font-size:.78rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.6px;">Empresa</th>
                <th style="padding:13px 16px;text-align:left;font-size:.78rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.6px;">Telefone</th>
                <th style="padding:13px 16px;text-align:left;font-size:.78rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.6px;">E-mail</th>
                <th style="padding:13px 16px;text-align:left;font-size:.78rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.6px;">Mensagem</th>
                <th style="padding:13px 16px;text-align:center;font-size:.78rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.6px;">Status</th>
                <th style="padding:13px 16px;text-align:left;font-size:.78rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.6px;">Data</th>
                <th style="padding:13px 16px;text-align:center;font-size:.78rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.6px;">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $item)
            <tr style="border-bottom:1px solid #f1f5f9;transition:background .15s;" onmouseover="this.style.background='#fafbff'" onmouseout="this.style.background='transparent'">
                <td style="padding:13px 16px;font-size:.82rem;color:#94a3b8;font-weight:600;">{{ $item->id }}</td>
                <td style="padding:13px 16px;">
                    <div style="font-weight:600;color:#1e293b;font-size:.9rem;">{{ $item->nome }}</div>
                </td>
                <td style="padding:13px 16px;color:#475569;font-size:.88rem;">{{ $item->empresa ?: '—' }}</td>
                <td style="padding:13px 16px;color:#475569;font-size:.88rem;">{{ $item->telefone }}</td>
                <td style="padding:13px 16px;color:#475569;font-size:.85rem;">{{ $item->email }}</td>
                <td style="padding:13px 16px;color:#64748b;font-size:.85rem;max-width:200px;">
                    <span title="{{ $item->mensagem }}">{{ Str::limit($item->mensagem, 55) ?: '—' }}</span>
                </td>
                <td style="padding:13px 16px;text-align:center;">
                    @if($item->status == 'novo')
                        <span style="background:#eff6ff;color:#2563eb;border:1px solid #bfdbfe;border-radius:999px;padding:3px 12px;font-size:.78rem;font-weight:700;">Novo</span>
                    @elseif($item->status == 'em_atendimento')
                        <span style="background:#fffbeb;color:#d97706;border:1px solid #fde68a;border-radius:999px;padding:3px 12px;font-size:.78rem;font-weight:700;">Em Atendimento</span>
                    @else
                        <span style="background:#f0fdf4;color:#109f61;border:1px solid #bbf7d0;border-radius:999px;padding:3px 12px;font-size:.78rem;font-weight:700;">Concluído</span>
                    @endif
                </td>
                <td style="padding:13px 16px;color:#64748b;font-size:.83rem;white-space:nowrap;">
                    {{ $item->created_at->format('d/m/Y H:i') }}
                </td>
                <td style="padding:13px 16px;text-align:center;">
                    <div style="display:flex;gap:6px;justify-content:center;align-items:center;flex-wrap:wrap;">
                        {{-- WhatsApp --}}
                        <a href="{{ $item->whatsapp_link }}" target="_blank"
                           style="display:inline-flex;align-items:center;gap:5px;padding:6px 12px;background:#25d366;color:#fff;border-radius:8px;font-size:.8rem;font-weight:600;text-decoration:none;white-space:nowrap;"
                           title="Falar no WhatsApp">
                            <i class="fa fa-whatsapp"></i> WhatsApp
                        </a>

                        {{-- Alterar Status --}}
                        <div style="position:relative;" x-data="{ open: false }">
                            <button @click="open = !open"
                                    style="padding:6px 12px;background:#f1f5f9;border:1.5px solid #e2e8f0;border-radius:8px;font-size:.8rem;font-weight:600;color:#475569;cursor:pointer;white-space:nowrap;">
                                Status <i class="fa fa-chevron-down" style="font-size:.7rem;"></i>
                            </button>
                            <div x-show="open" @click.away="open = false"
                                 style="position:absolute;right:0;top:110%;background:#fff;border:1px solid #e2e8f0;border-radius:10px;min-width:170px;box-shadow:0 8px 24px rgba(0,0,0,.12);z-index:50;padding:6px 0;">
                                @foreach(\App\Models\SolicitacaoDemonstracao::statusList() as $key => $label)
                                    @if($key !== $item->status)
                                    <form method="POST" action="{{ route('solicitacoes-demo.update-status', $item->id) }}">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="{{ $key }}">
                                        <button type="submit"
                                                style="width:100%;text-align:left;padding:8px 16px;background:none;border:none;font-size:.85rem;color:#334155;cursor:pointer;transition:background .15s;"
                                                onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='none'">
                                            {{ $label }}
                                        </button>
                                    </form>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        {{-- Excluir --}}
                        <form method="POST" action="{{ route('solicitacoes-demo.destroy', $item->id) }}"
                              onsubmit="return confirm('Excluir este lead permanentemente?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    style="padding:6px 10px;background:#fef2f2;border:1.5px solid #fecaca;border-radius:8px;color:#dc2626;cursor:pointer;font-size:.82rem;"
                                    title="Excluir">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="padding:60px 16px;text-align:center;color:#94a3b8;">
                    <i class="fa fa-inbox" style="font-size:2.5rem;display:block;margin-bottom:12px;opacity:.4;"></i>
                    Nenhuma solicitação encontrada.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Rodapé paginação --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-top:18px;flex-wrap:wrap;gap:10px;">
    <span style="font-size:.85rem;color:#64748b;">
        {{ $data->total() }} registro(s) encontrado(s)
    </span>
    {!! $data->appends(request()->all())->links() !!}
</div>

@endsection

