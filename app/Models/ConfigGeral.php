<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigGeral extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'empresa_id', 'balanca_digito_verificador', 'balanca_valor_peso', 'confirmar_itens_prevenda', 'notificacoes',
        'margem_combo', 'gerenciar_estoque', 'percentual_lucro_produto', 'tipos_pagamento_pdv', 'senha_manipula_valor',
        'abrir_modal_cartao', 'percentual_desconto_orcamento', 'agrupar_itens', 'tipo_comissao', 'modelo', 'alerta_sonoro',
        'cabecalho_pdv',
        // Impressora termica
        'printer_nome', 'printer_ip', 'printer_porta', 'printer_largura', 'printer_status', 'printer_tipo'
    ];

    public static function getNotificacoes(){
        return [
            'Contas a pagar', 'Contas a receber', 'Alerta de estoque', 'Alerta de validade', 'Ticket'
        ];
    }

    /**
     * Verifica se a impressora termica esta configurada e habilitada
     */
    public function isPrinterConfigured()
    {
        if ($this->printer_status != 1) {
            return false;
        }

        $tipo = $this->printer_tipo ?: 'rede';
        if ($tipo === 'usb') {
            return true;
        }

        return !empty($this->printer_ip) && !empty($this->printer_porta);
    }

    public function isPrinterUsb()
    {
        return $this->printer_status == 1 && ($this->printer_tipo ?: 'rede') === 'usb';
    }

    public function isPrinterRede()
    {
        return $this->printer_status == 1 && ($this->printer_tipo ?: 'rede') === 'rede' && !empty($this->printer_ip);
    }
}
