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
        // 1. Tabela de Categorias para os Vídeos de Suporte
        if (!Schema::hasTable('video_suporte_categorias')) {
            Schema::create('video_suporte_categorias', function (Blueprint $table) {
                $table->id();
                $table->string('nome', 100);
                $table->string('slug', 100)->unique();
                $table->integer('ordem_exibicao')->default(0);
                $table->string('status', 20)->default('ATIVO');
                $table->timestamps();
            });
        }

        // 2. Evolução da Tabela video_suportes
        if (Schema::hasTable('video_suportes')) {
            Schema::table('video_suportes', function (Blueprint $table) {
                if (!Schema::hasColumn('video_suportes', 'titulo')) {
                    $table->string('titulo', 150)->nullable()->after('id');
                }
                if (!Schema::hasColumn('video_suportes', 'descricao')) {
                    $table->text('descricao')->nullable()->after('titulo');
                }
                if (!Schema::hasColumn('video_suportes', 'categoria_id')) {
                    $table->unsignedBigInteger('categoria_id')->nullable()->after('descricao');
                }
                if (!Schema::hasColumn('video_suportes', 'categoria')) {
                    $table->string('categoria', 100)->nullable()->after('categoria_id');
                }
                if (!Schema::hasColumn('video_suportes', 'tags')) {
                    $table->text('tags')->nullable()->after('categoria');
                }
                if (!Schema::hasColumn('video_suportes', 'arquivo_original')) {
                    $table->string('arquivo_original', 255)->nullable()->after('tags');
                }
                if (!Schema::hasColumn('video_suportes', 'arquivo_path')) {
                    $table->string('arquivo_path', 255)->nullable()->after('arquivo_original');
                }
                if (!Schema::hasColumn('video_suportes', 'arquivo_url')) {
                    $table->text('arquivo_url')->nullable()->after('arquivo_path');
                }
                if (!Schema::hasColumn('video_suportes', 'mime_type')) {
                    $table->string('mime_type', 50)->default('video/mp4')->nullable()->after('arquivo_url');
                }
                if (!Schema::hasColumn('video_suportes', 'tamanho_arquivo')) {
                    $table->unsignedBigInteger('tamanho_arquivo')->nullable()->after('mime_type');
                }
                if (!Schema::hasColumn('video_suportes', 'duracao_segundos')) {
                    $table->integer('duracao_segundos')->nullable()->after('tamanho_arquivo');
                }
                if (!Schema::hasColumn('video_suportes', 'thumbnail_path')) {
                    $table->string('thumbnail_path', 255)->nullable()->after('duracao_segundos');
                }
                if (!Schema::hasColumn('video_suportes', 'status')) {
                    $table->string('status', 20)->default('ATIVO')->after('thumbnail_path');
                }
                if (!Schema::hasColumn('video_suportes', 'ordem_exibicao')) {
                    $table->integer('ordem_exibicao')->default(0)->after('status');
                }
                if (!Schema::hasColumn('video_suportes', 'tipo_publicacao')) {
                    $table->string('tipo_publicacao', 30)->default('GLOBAL')->after('ordem_exibicao');
                }
                if (!Schema::hasColumn('video_suportes', 'empresa_id')) {
                    $table->unsignedBigInteger('empresa_id')->nullable()->after('tipo_publicacao');
                }
                if (!Schema::hasColumn('video_suportes', 'usuario_criacao_id')) {
                    $table->unsignedBigInteger('usuario_criacao_id')->nullable()->after('empresa_id');
                }
                if (!Schema::hasColumn('video_suportes', 'usuario_atualizacao_id')) {
                    $table->unsignedBigInteger('usuario_atualizacao_id')->nullable()->after('usuario_criacao_id');
                }
                if (!Schema::hasColumn('video_suportes', 'deleted_at')) {
                    $table->softDeletes()->after('updated_at');
                }
            });

            // Ajustar colunas antigas para nullable se necessário
            Schema::table('video_suportes', function (Blueprint $table) {
                $table->string('pagina', 80)->nullable()->change();
                $table->string('url_video', 255)->nullable()->change();
                $table->string('url_servidor', 255)->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('video_suportes')) {
            Schema::table('video_suportes', function (Blueprint $table) {
                $cols = [
                    'titulo', 'descricao', 'categoria_id', 'categoria', 'tags',
                    'arquivo_original', 'arquivo_path', 'arquivo_url', 'mime_type',
                    'tamanho_arquivo', 'duracao_segundos', 'thumbnail_path',
                    'status', 'ordem_exibicao', 'tipo_publicacao', 'empresa_id',
                    'usuario_criacao_id', 'usuario_atualizacao_id', 'deleted_at'
                ];
                foreach ($cols as $col) {
                    if (Schema::hasColumn('video_suportes', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        Schema::dropIfExists('video_suporte_categorias');
    }
};
