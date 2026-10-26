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
        Schema::table('fila_impressoes', function (Blueprint $table) {
            $table->unsignedInteger('tentativas')->default(0)->after('status')->index();
            $table->timestamp('tentativa_em')->nullable()->after('tentativas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fila_impressoes', function (Blueprint $table) {
            $table->dropColumn(['tentativas', 'tentativa_em']);
        });
    }
};
