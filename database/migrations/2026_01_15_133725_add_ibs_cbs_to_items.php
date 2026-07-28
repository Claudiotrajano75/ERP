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
        if (!Schema::hasColumn('item_nves', 'perc_ibs')) {
            Schema::table('item_nves', function (Blueprint $table) {
                $table->decimal('perc_ibs', 5, 2)->default(0.05)->after('perc_ipi');
                $table->decimal('perc_cbs', 5, 2)->default(0.10)->after('perc_ibs');
            });
        }

        if (!Schema::hasColumn('item_nfces', 'perc_ibs')) {
            Schema::table('item_nfces', function (Blueprint $table) {
                $table->decimal('perc_ibs', 5, 2)->default(0.05)->after('perc_cofins');
                $table->decimal('perc_cbs', 5, 2)->default(0.10)->after('perc_ibs');
            });
        }

        if (!Schema::hasColumn('natureza_operacaos', 'perc_ibs')) {
            Schema::table('natureza_operacaos', function (Blueprint $table) {
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
        Schema::table('item_nves', function (Blueprint $table) {
            $table->dropColumn(['perc_ibs', 'perc_cbs']);
        });

        Schema::table('item_nfces', function (Blueprint $table) {
            $table->dropColumn(['perc_ibs', 'perc_cbs']);
        });

        Schema::table('natureza_operacaos', function (Blueprint $table) {
            $table->dropColumn(['perc_ibs', 'perc_cbs']);
        });
    }
};
