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
        // 1. Campos de DPS na tabela nota_servicos
        Schema::table('nota_servicos', function (Blueprint $table) {
            if (!Schema::hasColumn('nota_servicos', 'numero_dps')) {
                $table->integer('numero_dps')->nullable()->after('numero_nfse');
            }
            if (!Schema::hasColumn('nota_servicos', 'serie_dps')) {
                $table->string('serie_dps', 10)->nullable()->default('1')->after('numero_dps');
            }
            if (!Schema::hasColumn('nota_servicos', 'chave_dps')) {
                $table->string('chave_dps', 60)->nullable()->after('serie_dps');
            }
            if (!Schema::hasColumn('nota_servicos', 'xml_dps')) {
                $table->mediumText('xml_dps')->nullable()->after('chave_dps');
            }
            if (!Schema::hasColumn('nota_servicos', 'motivo_rejeicao')) {
                $table->text('motivo_rejeicao')->nullable()->after('xml_dps');
            }
        });

        // 2. Código de Tributação Nacional no cadastro de servicos
        Schema::table('servicos', function (Blueprint $table) {
            if (!Schema::hasColumn('servicos', 'codigo_tributacao_nacional')) {
                $table->string('codigo_tributacao_nacional', 20)->nullable()->after('codigo_tributacao_municipio');
            }
        });

        // 3. Código de Tributação Nacional nos itens da nota de servico
        Schema::table('item_nota_servicos', function (Blueprint $table) {
            if (!Schema::hasColumn('item_nota_servicos', 'codigo_tributacao_nacional')) {
                $table->string('codigo_tributacao_nacional', 20)->nullable()->after('codigo_tributacao_municipio');
            }
        });

        // 4. Parâmetros sequenciais de DPS na tabela empresas
        Schema::table('empresas', function (Blueprint $table) {
            if (!Schema::hasColumn('empresas', 'numero_ultima_dps')) {
                $table->integer('numero_ultima_dps')->nullable()->default(0)->after('numero_ultima_nfse');
            }
            if (!Schema::hasColumn('empresas', 'numero_serie_dps')) {
                $table->string('numero_serie_dps', 10)->nullable()->default('1')->after('numero_ultima_dps');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nota_servicos', function (Blueprint $table) {
            $cols = ['numero_dps', 'serie_dps', 'chave_dps', 'xml_dps', 'motivo_rejeicao'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('nota_servicos', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('servicos', function (Blueprint $table) {
            if (Schema::hasColumn('servicos', 'codigo_tributacao_nacional')) {
                $table->dropColumn('codigo_tributacao_nacional');
            }
        });

        Schema::table('item_nota_servicos', function (Blueprint $table) {
            if (Schema::hasColumn('item_nota_servicos', 'codigo_tributacao_nacional')) {
                $table->dropColumn('codigo_tributacao_nacional');
            }
        });

        Schema::table('empresas', function (Blueprint $table) {
            if (Schema::hasColumn('empresas', 'numero_ultima_dps')) {
                $table->dropColumn('numero_ultima_dps');
            }
            if (Schema::hasColumn('empresas', 'numero_serie_dps')) {
                $table->dropColumn('numero_serie_dps');
            }
        });
    }
};

