@extends('layouts.app', ['title' => 'Editar Vídeo de Suporte'])

@section('content')
<div class="mt-3">
    <div class="row">
        <div class="card border-0 shadow-sm">

            <div class="card-header modulo-header-gradient py-3 px-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1 modulo-title d-flex align-items-center gap-2 text-white">
                            <i class="ri-edit-2-line"></i> Editar Vídeo de Suporte #{{ $item->id }}
                        </h4>
                        <p class="text-white-50 mb-0 modulo-subtitle fs-13">Atualize os dados do tutorial ou substitua o arquivo MP4 do vídeo.</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('video-suporte.index') }}" class="dash-btn dash-btn-light"><i class="ri-arrow-left-line"></i> Voltar</a>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <form id="form-video" action="{{ route('video-suporte.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('video_suporte._forms')
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
