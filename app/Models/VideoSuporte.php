<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VideoSuporte extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'video_suportes';

    protected $fillable = [
        'titulo',
        'descricao',
        'categoria_id',
        'categoria',
        'tags',
        'arquivo_original',
        'arquivo_path',
        'arquivo_url',
        'mime_type',
        'tamanho_arquivo',
        'duracao_segundos',
        'thumbnail_path',
        'status',
        'ordem_exibicao',
        'tipo_publicacao',
        'empresa_id',
        'usuario_criacao_id',
        'usuario_atualizacao_id',
        // Campos legados para compatibilidade
        'pagina',
        'url_video',
        'url_servidor',
    ];

    protected $casts = [
        'ordem_exibicao' => 'integer',
        'tamanho_arquivo' => 'integer',
        'duracao_segundos' => 'integer',
    ];

    public function categoriaRelacionada()
    {
        return $this->belongsTo(VideoSuporteCategoria::class, 'categoria_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function usuarioCriacao()
    {
        return $this->belongsTo(User::class, 'usuario_criacao_id');
    }

    public function usuarioAtualizacao()
    {
        return $this->belongsTo(User::class, 'usuario_atualizacao_id');
    }

    public function scopeAtivo($query)
    {
        return $query->where('status', 'ATIVO');
    }

    public function scopeDisponivelPara($query, $empresaId = null)
    {
        return $query->where(function ($q) use ($empresaId) {
            $q->where('tipo_publicacao', 'GLOBAL');
            if ($empresaId) {
                $q->orWhere(function ($sub) use ($empresaId) {
                    $sub->where('tipo_publicacao', 'EMPRESA_ESPECIFICA')
                        ->where('empresa_id', $empresaId);
                });
            }
        });
    }

    public function getTagsArrayAttribute()
    {
        if (empty($this->tags)) {
            return [];
        }
        return array_values(array_filter(array_map('trim', explode(',', $this->tags))));
    }

    public function getTamanhoFormatadoAttribute()
    {
        $bytes = $this->tamanho_arquivo;
        if (!$bytes || $bytes <= 0) {
            return '—';
        }
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = $bytes > 0 ? floor(log($bytes, 1024)) : 0;
        return number_format($bytes / pow(1024, $power), 1, ',', '.') . ' ' . ($units[$power] ?? 'B');
    }

    public function getDuracaoFormatadaAttribute()
    {
        if (!$this->duracao_segundos) {
            return null;
        }
        $min = floor($this->duracao_segundos / 60);
        $sec = $this->duracao_segundos % 60;
        return sprintf('%02d:%02d', $min, $sec);
    }

    public function getStreamUrlAttribute()
    {
        if (!empty($this->arquivo_path)) {
            return '/central-ajuda/' . $this->id . '/stream';
        }
        return $this->url_video ?? '';
    }
}
