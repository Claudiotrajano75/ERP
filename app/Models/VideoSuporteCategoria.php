<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoSuporteCategoria extends Model
{
    use HasFactory;

    protected $table = 'video_suporte_categorias';

    protected $fillable = [
        'nome',
        'slug',
        'ordem_exibicao',
        'status',
    ];

    public function videos()
    {
        return $this->hasMany(VideoSuporte::class, 'categoria_id');
    }

    public function videosAtivos()
    {
        return $this->hasMany(VideoSuporte::class, 'categoria_id')
                    ->where('status', 'ATIVO')
                    ->orderBy('ordem_exibicao', 'asc')
                    ->orderBy('created_at', 'desc');
    }
}
