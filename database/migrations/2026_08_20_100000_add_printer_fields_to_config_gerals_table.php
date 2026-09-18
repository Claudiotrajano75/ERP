<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('config_gerals', function (Blueprint $table) {
            $table->string('printer_nome', 100)->nullable()->after('cabecalho_pdv')->comment('Nome da impressora termica');
            $table->string('printer_ip', 45)->nullable()->after('printer_nome')->comment('IP da impressora (ex: 192.168.1.100)');
            $table->integer('printer_porta')->default(9100)->after('printer_ip')->comment('Porta TCP/IP (padrao 9100)');
            $table->enum('printer_largura', ['58', '80'])->default('80')->after('printer_porta')->comment('Largura da bobina em mm');
            $table->boolean('printer_status')->default(0)->after('printer_largura')->comment('0=Desabilitada, 1=Habilitada');
        });
    }

    public function down(): void
    {
        Schema::table('config_gerals', function (Blueprint $table) {
            $table->dropColumn([
                'printer_nome',
                'printer_ip',
                'printer_porta',
                'printer_largura',
                'printer_status',
            ]);
        });
    }
};
