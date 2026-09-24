# PDV "Mesa" — Frente de Caixa (para desenvolver no Antigravity)

Este pacote contém o código de referência da nova tela de PDV para você
desenvolver no seu projeto. Anexe o ZIP no Antigravity e cole o prompt abaixo.

## Arquivos incluídos

```
src/
├── routes/index.tsx          → tela completa do PDV (referência de layout e lógica)
├── routes/__root.tsx         → fontes (Sora + Manrope) e metadados da página
├── styles.css                → design tokens (cores, fontes, raios) — copie para seu CSS global
├── components/ui/button.tsx  → botão base com variantes
└── assets/*.jpg              → fotos dos produtos do catálogo
```

> O código de referência usa React 19 + TanStack Router + Tailwind CSS v4.
> Se seu projeto usa outra stack (Next.js, Vite puro, Vue etc.), use o
> `index.tsx` como referência visual/funcional e peça à IA para adaptar.

## Prompt pronto para colar no Antigravity

---

Crie uma tela de PDV (frente de caixa) para restaurante chamada "Mesa",
profissional e elegante, seguindo estas especificações:

**Identidade visual (obrigatória):**
- Paleta: grafite (#18181B fundo, #3F3F46 superfícies/cartões, #F4F4F5 texto claro) e coral (#F05A3C) como cor de ação/destaque.
- Tipografia: Sora para títulos e números grandes (total, valores), Manrope para textos e labels.
- Estilo: painel operacional sóbrio, cantos levemente arredondados (8px), SEM gradientes, SEM tons roxos, SEM animações decorativas.

**Layout (desktop ≥1024px):**
- Header compacto fixo: logo "Mesa PDV", caixa "02", turno da tarde, status Online (bolinha verde), vendedor logado.
- Corpo em 2 colunas: à esquerda o catálogo (busca por nome/código, tabs de categorias — Todos, Executivos, Massas, Favoritos, Promoções — e grade de produtos com foto, nome, preço e botão de adicionar); à direita o pedido (comanda com itens, quantidade +/-, excluir, campos Desconto e Acréscimo em R$, TOTAL em destaque grande).
- Rodapé do painel de pedido com botões: Suspender, Cancelar e FINALIZAR VENDA (coral, botão principal).
- A página inteira NÃO pode ter barra de rolagem — apenas as listas internas (grade de produtos e itens da comanda) rolam.

**Layout (mobile <1024px):**
- Uma coluna compacta com navegação inferior entre duas abas: "Produtos" e "Pedido".

**Funcionalidade:**
- Busca filtra produtos por nome ou código.
- Clicar em categoria filtra a grade.
- Adicionar produto cria/incrementa item na comanda; +/− alteram quantidade; remover exclui.
- Desconto e acréscimo alteram o total em tempo real.
- Dados de produtos podem começar mockados (8 produtos com fotos).

O código de referência anexado (src/routes/index.tsx e src/styles.css) mostra exatamente o visual, os tokens e o comportamento esperados — replique-o na stack do meu projeto.
