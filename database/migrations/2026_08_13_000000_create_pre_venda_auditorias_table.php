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
        Schema::create('pre_venda_auditorias', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pre_venda_id')->constrained('pre_vendas')->onDelete('cascade');
            // item_id sem FK: os itens são recriados a cada edição (padrão do PDV)
            $table->unsignedBigInteger('item_id')->nullable();
            $table->foreignId('usuario_id')->nullable()->constrained('users');
            $table->foreignId('empresa_id')->nullable()->constrained('empresas');

            $table->timestamp('data_hora')->useCurrent();
            $table->string('tipo_operacao', 50);
            $table->text('valores_antes')->nullable();
            $table->text('valores_depois')->nullable();

            $table->timestamps();

            $table->index(['pre_venda_id', 'data_hora']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_venda_auditorias');
    }
};
