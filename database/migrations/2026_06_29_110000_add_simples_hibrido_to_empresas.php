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
        if (!Schema::hasColumn('empresas', 'simples_hibrido')) {
            Schema::table('empresas', function (Blueprint $table) {
                $table->boolean('simples_hibrido')->default(false)->after('tributacao');
            });
        }

        if (!Schema::hasColumn('localizacaos', 'simples_hibrido')) {
            Schema::table('localizacaos', function (Blueprint $table) {
                $table->boolean('simples_hibrido')->default(false)->after('tributacao');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn(['simples_hibrido']);
        });

        Schema::table('localizacaos', function (Blueprint $table) {
            $table->dropColumn(['simples_hibrido']);
        });
    }
};
