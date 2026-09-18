<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('configuracao_supers', function (Blueprint $table) {
            if (!Schema::hasColumn('configuracao_supers', 'login_banner')) {
                $table->string('login_banner')->nullable()->after('logo');
            }
        });
    }

    public function down(): void
    {
        Schema::table('configuracao_supers', function (Blueprint $table) {
            if (Schema::hasColumn('configuracao_supers', 'login_banner')) {
                $table->dropColumn('login_banner');
            }
        });
    }
};
