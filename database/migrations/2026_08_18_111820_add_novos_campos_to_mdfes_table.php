<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mdves', function (Blueprint $table) {
            $table->string('unidade_medida', 2)->nullable();
            $table->string('responsavel_seguro', 50)->nullable();
            $table->string('cnpj_responsavel_seguro', 20)->nullable();
            $table->string('infpag_nome_contratante', 100)->nullable();
            $table->string('infpag_cnpj_contratante', 20)->nullable();
            $table->decimal('infpag_valor_contrato', 12, 2)->nullable();
            $table->string('infpag_ind_pag', 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mdves', function (Blueprint $table) {
            $table->dropColumn([
                'unidade_medida',
                'responsavel_seguro',
                'cnpj_responsavel_seguro',
                'infpag_nome_contratante',
                'infpag_cnpj_contratante',
                'infpag_valor_contrato',
                'infpag_ind_pag'
            ]);
        });
    }
};
