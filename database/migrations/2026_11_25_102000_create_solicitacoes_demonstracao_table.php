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
        Schema::create('solicitacoes_demonstracao', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 120);
            $table->string('email', 150);
            $table->string('empresa', 150)->nullable();
            $table->string('telefone', 40);
            $table->text('mensagem')->nullable();
            $table->string('status', 30)->default('novo'); // novo, em_atendimento, concluido
            $table->text('observacao_admin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitacoes_demonstracao');
    }
};

