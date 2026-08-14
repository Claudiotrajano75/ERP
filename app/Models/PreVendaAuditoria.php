<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreVendaAuditoria extends Model
{
    use HasFactory;

    protected $table = 'pre_venda_auditorias';

    protected $fillable = [
        'pre_venda_id', 'item_id', 'usuario_id', 'empresa_id',
        'data_hora', 'tipo_operacao', 'valores_antes', 'valores_depois'
    ];

    public function preVenda()
    {
        return $this->belongsTo(PreVenda::class, 'pre_venda_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Registra uma operação de auditoria em tabela própria.
     */
    public static function registrar(int $preVendaId, string $tipoOperacao, ?int $itemId = null, $valoresAntes = null, $valoresDepois = null, ?int $empresaId = null, ?int $usuarioId = null): void
    {
        self::create([
            'pre_venda_id' => $preVendaId,
            'item_id' => $itemId,
            'usuario_id' => $usuarioId ?? (auth()->check() ? auth()->id() : null),
            'empresa_id' => $empresaId ?? (request()->has('empresa_id') ? request()->empresa_id : null),
            'tipo_operacao' => $tipoOperacao,
            'valores_antes' => $valoresAntes !== null ? json_encode($valoresAntes) : null,
            'valores_depois' => $valoresDepois !== null ? json_encode($valoresDepois) : null,
        ]);
    }

    /**
     * Gera a lista de operações de auditoria comparando os itens antes e depois de uma edição.
     *
     * Cada item deve ser um array com: produto_id, quantidade, valor.
     * A comparação é feita por posição (índice), pois os itens são recriados a cada edição.
     *
     * @param  array<int, array<string, mixed>>  $antes
     * @param  array<int, array<string, mixed>>  $depois
     * @return array<int, array<string, mixed>>  Lista de [item_id, tipo_operacao, valores_antes, valores_depois]
     */
    public static function diffItens(array $antes, array $depois): array
    {
        $operacoes = [];
        $total = max(count($antes), count($depois));

        for ($i = 0; $i < $total; $i++) {
            $old = $antes[$i] ?? null;
            $new = $depois[$i] ?? null;

            if ($old === null) {
                $operacoes[] = [
                    'item_id' => $new['item_id'] ?? null,
                    'tipo_operacao' => 'ADD_ITEM',
                    'valores_antes' => null,
                    'valores_depois' => $new,
                ];
                continue;
            }

            if ($new === null) {
                $operacoes[] = [
                    'item_id' => $old['item_id'] ?? null,
                    'tipo_operacao' => 'REMOVE_ITEM',
                    'valores_antes' => $old,
                    'valores_depois' => null,
                ];
                continue;
            }

            if (($old['quantidade'] ?? null) != ($new['quantidade'] ?? null)) {
                $operacoes[] = [
                    'item_id' => $new['item_id'] ?? $old['item_id'] ?? null,
                    'tipo_operacao' => 'UPDATE_QTD',
                    'valores_antes' => ['quantidade' => $old['quantidade'] ?? null],
                    'valores_depois' => ['quantidade' => $new['quantidade'] ?? null],
                ];
            }

            if ((float)($old['valor'] ?? 0) != (float)($new['valor'] ?? 0)) {
                $operacoes[] = [
                    'item_id' => $new['item_id'] ?? $old['item_id'] ?? null,
                    'tipo_operacao' => 'UPDATE_VALOR_ITEM',
                    'valores_antes' => ['valor' => $old['valor'] ?? null],
                    'valores_depois' => ['valor' => $new['valor'] ?? null],
                ];
            }
        }

        return $operacoes;
    }

    /**
     * Gera operações de auditoria para o cabeçalho da pré-venda.
     *
     * @return array<int, array<string, mixed>>  Lista de [item_id => null, tipo_operacao, valores_antes, valores_depois]
     */
    public static function diffCabecalho(array $antes, array $depois): array
    {
        $operacoes = [];
        $campos = [
            'desconto' => 'UPDATE_DESCONTO',
            'acrescimo' => 'UPDATE_ACRESCIMO',
            'valor_total' => 'UPDATE_VALOR_TOTAL',
            'cliente_id' => 'UPDATE_CLIENTE',
            'tipo_pagamento' => 'UPDATE_PAGAMENTO',
            'observacao' => 'UPDATE_OBSERVACAO',
        ];

        foreach ($campos as $campo => $tipo) {
            $old = $antes[$campo] ?? null;
            $new = $depois[$campo] ?? null;

            if ((string)$old != (string)$new) {
                $operacoes[] = [
                    'item_id' => null,
                    'tipo_operacao' => $tipo,
                    'valores_antes' => [$campo => $old],
                    'valores_depois' => [$campo => $new],
                ];
            }
        }

        return $operacoes;
    }
}
