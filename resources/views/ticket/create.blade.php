@extends('layouts.app', ['title' => 'Nova Solicitação'])

@section('content')
<div class="mt-3 text-dark">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-12">
            <div class="card border-0 shadow-sm" style="border-radius:12px;overflow:hidden;">

                <div class="card-header py-3 px-4" style="background:linear-gradient(135deg,#0f0c29,#302b63,#24243e);border-radius:12px 12px 0 0 !important;border-bottom:none;">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 d-flex align-items-center gap-2" style="color:#fff;font-weight:700;">
                                <span style="background:rgba(255,255,255,.12);padding:8px;border-radius:10px;color:#a8b5ff;">
                                    <i class="ri-add-circle-line"></i>
                                </span>
                                Nova Solicitação
                            </h4>
                            <p class="mb-0 fs-13" style="color:rgba(255,255,255,.6);">Descreva seu problema ou dúvida para nossa equipe de suporte.</p>
                        </div>
                        <div>
                            <a href="{{ route('ticket.index') }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 18px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);color:#fff;backdrop-filter:blur(6px);">
                                <i class="ri-arrow-left-line"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    {!! Form::open()->post()->route('ticket.store')->multipart() !!}
                    @include('ticket._forms')
                    {!! Form::close() !!}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="/tinymce/tinymce.min.js"></script>
<script>
    $(function(){
        tinymce.init({ selector: 'textarea.tiny', language: 'pt_BR' })
        setTimeout(() => {
            $('.tox-promotion, .tox-statusbar__right-container').addClass('d-none')
        }, 500)
    })
</script>
@endsection

