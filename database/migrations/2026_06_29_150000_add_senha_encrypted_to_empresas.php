<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('empresas', 'senha_encrypted')) {
            return;
        }

        Schema::table('empresas', function (Blueprint $table) {
            $table->text('senha_encrypted')->nullable()->after('senha');
        });

        // Criptografar senhas existentes
        $empresas = DB::table('empresas')->whereNotNull('senha')->where('senha', '!=', '')->get();
        foreach ($empresas as $empresa) {
            try {
                $encrypted = Crypt::encryptString($empresa->senha);
                DB::table('empresas')
                    ->where('id', $empresa->id)
                    ->update(['senha_encrypted' => $encrypted]);
            } catch (\Exception $e) {
                // Se falhar (ex: APP_KEY mudou), ignora e mantém senha legada
                echo "Aviso: não foi possível criptografar senha da empresa {$empresa->id}: {$e->getMessage()}\n";
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn('senha_encrypted');
        });
    }
};
