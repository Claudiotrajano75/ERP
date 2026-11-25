<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitacaoDemonstracao extends Model
{
    use HasFactory;

    protected $table = 'solicitacoes_demonstracao';

    protected $fillable = [
        'nome',
        'email',
        'empresa',
        'telefone',
        'mensagem',
        'status',
        'observacao_admin',
    ];

    public static function statusList(): array
    {
        return [
            'novo'           => 'Novo',
            'em_atendimento' => 'Em Atendimento',
            'concluido'      => 'Concluído',
        ];
    }

    public function getWhatsappLinkAttribute(): string
    {
        $num = preg_replace('/[^0-9]/', '', $this->telefone);
        if (!$num) {
            return '#';
        }
        if (strlen($num) <= 11) {
            $num = '55' . $num;
        }
        $texto = urlencode("Olá {$this->nome}! Recebemos sua solicitação de demonstração do nosso sistema ERP e gostaria de conversar com você.");
        return "https://wa.me/{$num}?text={$texto}";
    }
}

