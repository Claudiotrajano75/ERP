<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaServico extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id', 'valor_total', 'estado', 'serie', 'codigo_verificacao', 'numero_nfse', 'url_xml',
        'url_pdf_nfse', 'url_pdf_rps', 'cliente_id', 'documento', 'razao_social', 'im', 'ie', 'cep',
        'rua', 'numero', 'bairro', 'complemento', 'cidade_id', 'email', 'telefone', 'natureza_operacao', 'uuid',
        'ambiente', 'gerar_conta_receber', 'data_vencimento', 'conta_receber_id',
        'numero_dps', 'serie_dps', 'chave_dps', 'xml_dps', 'motivo_rejeicao'
    ];

    public function servico(){
        return $this->hasOne(ItemNotaServico::class, 'nota_servico_id');
    }

    public function contaReceber(){
        return $this->belongsTo(ContaReceber::class, 'conta_receber_id');
    }

    public function novoNumeroNFse(){
        $item = NotaServico::where("empresa_id", $this->empresa_id)
        ->orderBy('numero_nfse', 'desc')->first();
        if($item != null){
            return $item->numero_nfse + 1;
        }
        return 1;
    }

    public function novoNumeroDps(){
        $item = NotaServico::where("empresa_id", $this->empresa_id)
            ->whereNotNull('numero_dps')
            ->orderBy('numero_dps', 'desc')->first();
        if($item != null && $item->numero_dps > 0){
            return $item->numero_dps + 1;
        }
        $empresa = Empresa::find($this->empresa_id);
        if ($empresa && $empresa->numero_ultima_dps > 0) {
            return $empresa->numero_ultima_dps + 1;
        }
        return 1;
    }

    public function cidade(){
        return $this->belongsTo(Cidade::class, 'cidade_id');
    }

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function empresa(){
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public static function exigibilidades(){
        return [
            1 => 'Exígivel',
            2 => 'Não incidência',
            3 => 'Isenção',
            4 => 'Exportação',
            5 => 'Imunidade',
            6 => 'Exigibilidade Suspensa por Decisão Judicial',
            7 => 'Exigibilidade Suspensa por Processo Administrativo',
        ];
    }
}
