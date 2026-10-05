<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('config_gerals', function (Blueprint $table) {
            $table->enum('printer_tipo', ['rede', 'usb'])->default('rede')->after('printer_status')->comment('Tipo de impressao termica: rede (IP) ou usb (driver local)');
        });
    }

    public function down(): void
    {
        Schema::table('config_gerals', function (Blueprint $table) {
            $table->dropColumn('printer_tipo');
        });
    }
};
