<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VideoSuporteCategoria;
use App\Models\VideoSuporte;
use Illuminate\Support\Str;

class VideoSuporteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            ['nome' => 'Primeiros passos', 'ordem' => 1],
            ['nome' => 'Cadastros', 'ordem' => 2],
            ['nome' => 'Vendas e PDV', 'ordem' => 3],
            ['nome' => 'Caixa', 'ordem' => 4],
            ['nome' => 'Estoque', 'ordem' => 5],
            ['nome' => 'Compras', 'ordem' => 6],
            ['nome' => 'Financeiro', 'ordem' => 7],
            ['nome' => 'Fiscal / Nota Fiscal', 'ordem' => 8],
            ['nome' => 'Relatórios', 'ordem' => 9],
            ['nome' => 'Configurações', 'ordem' => 10],
            ['nome' => 'Integrações', 'ordem' => 11],
            ['nome' => 'Outros', 'ordem' => 12],
        ];

        $categoriaMap = [];
        foreach ($categorias as $cat) {
            $slug = Str::slug($cat['nome']);
            $registro = VideoSuporteCategoria::firstOrCreate(
                ['slug' => $slug],
                [
                    'nome' => $cat['nome'],
                    'ordem_exibicao' => $cat['ordem'],
                    'status' => 'ATIVO'
                ]
            );
            $categoriaMap[$cat['nome']] = $registro->id;
        }

        // Exemplos iniciais
        $exemplos = [
            [
                'titulo' => 'Como cadastrar produtos no sistema',
                'descricao' => 'Aprenda a cadastrar produtos, informar preço de venda, estoque mínimo, NCM, grupo fiscal e código de barras.',
                'categoria' => 'Cadastros',
                'tags' => 'produto, cadastro, estoque, ncm, tributação',
                'status' => 'ATIVO',
                'ordem_exibicao' => 1,
                'arquivo_original' => 'tutorial_cadastro_produtos.mp4',
                'mime_type' => 'video/mp4',
                'tamanho_arquivo' => 18456120, // ~17.6 MB
                'tipo_publicacao' => 'GLOBAL'
            ],
            [
                'titulo' => 'Como realizar uma venda no PDV',
                'descricao' => 'Veja como abrir o caixa, incluir itens no pedido, aplicar desconto autorizado, receber em dinheiro, cartão ou Pix e finalizar a venda.',
                'categoria' => 'Vendas e PDV',
                'tags' => 'pdv, venda, caixa, pix, cartão',
                'status' => 'ATIVO',
                'ordem_exibicao' => 2,
                'arquivo_original' => 'tutorial_venda_pdv.mp4',
                'mime_type' => 'video/mp4',
                'tamanho_arquivo' => 26340150, // ~25.1 MB
                'tipo_publicacao' => 'GLOBAL'
            ],
            [
                'titulo' => 'Como emitir NFC-e',
                'descricao' => 'Aprenda a configurar e emitir NFC-e após concluir uma venda no caixa.',
                'categoria' => 'Fiscal / Nota Fiscal',
                'tags' => 'nfce, nota fiscal, sefaz, venda',
                'status' => 'ATIVO',
                'ordem_exibicao' => 3,
                'arquivo_original' => 'tutorial_emissao_nfce.mp4',
                'mime_type' => 'video/mp4',
                'tamanho_arquivo' => 21845100, // ~20.8 MB
                'tipo_publicacao' => 'GLOBAL'
            ],
            [
                'titulo' => 'Como configurar a tributação dos produtos',
                'descricao' => 'Entenda como configurar NCM, CEST, CFOP, CSOSN/CST, PIS, COFINS e regras tributárias no cadastro de produtos.',
                'categoria' => 'Fiscal / Nota Fiscal',
                'tags' => 'tributação, ncm, cest, cfop, csosn, impostos',
                'status' => 'ATIVO',
                'ordem_exibicao' => 4,
                'arquivo_original' => 'tutorial_tributacao_regras.mp4',
                'mime_type' => 'video/mp4',
                'tamanho_arquivo' => 33540200, // ~32 MB
                'tipo_publicacao' => 'GLOBAL'
            ]
        ];

        foreach ($exemplos as $ex) {
            $catId = $categoriaMap[$ex['categoria']] ?? null;
            VideoSuporte::updateOrCreate(
                ['titulo' => $ex['titulo']],
                array_merge($ex, ['categoria_id' => $catId])
            );
        }
    }
}
