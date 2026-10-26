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
        Schema::create('fila_impressoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id')->index();
            $table->string('tipo', 50)->default('nfce');
            $table->unsignedBigInteger('documento_id')->nullable();
            $table->string('printer_ip', 45);
            $table->integer('printer_porta')->default(9100);
            $table->longText('payload_base64');
            $table->enum('status', ['pendente', 'impresso', 'erro'])->default('pendente')->index();
            $table->text('erro_mensagem')->nullable();
            $table->timestamp('impresso_em')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fila_impressoes');
    }
};
