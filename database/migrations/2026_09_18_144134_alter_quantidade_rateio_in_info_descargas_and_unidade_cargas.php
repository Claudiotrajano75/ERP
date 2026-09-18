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
        Schema::table('info_descargas', function (Blueprint $table) {
            $table->decimal('quantidade_rateio', 12, 4)->default(0)->change();
        });

        if (Schema::hasTable('unidade_cargas')) {
            Schema::table('unidade_cargas', function (Blueprint $table) {
                $table->decimal('quantidade_rateio', 12, 4)->default(0)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('info_descargas', function (Blueprint $table) {
            $table->decimal('quantidade_rateio', 5, 2)->change();
        });

        if (Schema::hasTable('unidade_cargas')) {
            Schema::table('unidade_cargas', function (Blueprint $table) {
                $table->decimal('quantidade_rateio', 5, 2)->change();
            });
        }
    }
};
