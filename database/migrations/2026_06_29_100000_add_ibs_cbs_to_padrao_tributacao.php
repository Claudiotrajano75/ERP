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
        if (!Schema::hasColumn('padrao_tributacao_produtos', 'perc_ibs')) {
            Schema::table('padrao_tributacao_produtos', function (Blueprint $table) {
                $table->decimal('perc_ibs', 5, 2)->default(0.05)->after('perc_ipi');
                $table->decimal('perc_cbs', 5, 2)->default(0.10)->after('perc_ibs');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('padrao_tributacao_produtos', function (Blueprint $table) {
            $table->dropColumn(['perc_ibs', 'perc_cbs']);
        });
    }
};
