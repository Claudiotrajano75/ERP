<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilaImpressao extends Model
{
    use HasFactory;

    protected $table = 'fila_impressoes';

    protected $fillable = [
        'empresa_id',
        'tipo',
        'documento_id',
        'printer_ip',
        'printer_porta',
        'payload_base64',
        'status',
        'erro_mensagem',
        'impresso_em',
    ];

    protected $casts = [
        'impresso_em' => 'datetime',
    ];
}
