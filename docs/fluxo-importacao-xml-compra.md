# Fluxo Interno — Importação de XML de Compra (NFe de Entrada)

Rota da tela: `http://127.0.0.1:8000/store-xml`

O processo de importação acontece em **3 telas / 2 etapas de gravação** e envolve **4 rotas**:

| Etapa | Rota (name) | Método | Controller | View |
|---|---|---|---|---|
| 0. Seleção do arquivo | `compras.xml` → `/compras-xml` | GET | `CompraController@xml` | `compras/xml.blade.php` |
| 1. Upload e leitura do XML | `compras.store-xml` → `/store-xml` | POST | `CompraController@storeXml` | `compras/import_xml.blade.php` |
| 2. Confirmação e gravação | `compras.finish-xml` → `/compras-finish-xml` | POST | `CompraController@finishXml` | redireciona para `compras.index` |

> Detalhe importante: a URL `/store-xml` que você vê no navegador **não é a tela de seleção de arquivo** — é a tela de **confirmação** (etapa 1). Ela é o resultado do POST do formulário de upload.

---

## ETAPA 0 — Seleção do arquivo (`/compras-xml`)

**View:** `resources/views/compras/xml.blade.php`

1. O controller `CompraController@xml` verifica se há **caixa aberto** (`__isCaixaAberto()`).
   - Se não houver: flash "Abrir caixa antes de continuar!" e redireciona para `caixa.create`.
   - Se houver: renderiza a tela de upload.
2. O input `<input type="file">` é invisível (CSS); o usuário clica na área pontilhada (label `custom-file-upload`).
3. Ao escolher um arquivo `.xml`, o JS da própria view:
   - Mostra o nome do arquivo em `#filename`;
   - Após 500 ms, dá `submit()` no formulário `#form-xml` (POST multipart → `compras.store-xml`);
   - Adiciona a classe `loading` no `<body>` (spinner).

---

## ETAPA 1 — Upload, leitura do XML e tela de confirmação (`/store-xml`)

**Controller:** `CompraController@storeXml` (linhas ~229–466)
**Middleware:** `authh`, `validaEmpresa` (injeta `empresa_id` do usuário logado no request), `verificaEmpresa`, `validaPlano`.

### 1.1 Validações iniciais
1. Verifica `$request->hasFile('file')`. Sem arquivo → flash "XML inválido!" e volta para a tela anterior.
2. Faz o parse com `simplexml_load_file()`.
3. Verifica se existe `$xml->NFe->infNFe`. Não existe → flash "Este XML parece inválido!" e volta.

### 1.2 Arquivo físico
4. Extrai a **chave de 44 dígitos** do atributo `Id` de `infNFe` (substr a partir da posição 3).
5. Move o arquivo para `public/xml_entrada/{chave}.xml` (cria o diretório se não existir).

### 1.3 Fornecedor (emitente da nota)
6. Busca a **cidade** pelo código IBGE (`cMun`) na tabela `cidades`.
7. Lê o CNPJ **ou** CPF do emitente e aplica máscara (`##.###.###/####-##` ou `###.###.###-##`).
8. Monta o array `$dataFornecedor` (razão social, nome fantasia, IE, telefone, endereço etc.).
9. Chama `cadastraFornecedor()`:
   - Procura fornecedor existente pelo `cpf_cnpj` **+ `empresa_id`**;
   - Se não existir, **cadastra automaticamente** (`Fornecedor::create`).

### 1.4 Produtos (itens da nota)
10. Para cada bloco `<det>` do XML:
    - `Produto::verificaCadastrado(cEAN, xProd, cProd, empresa_id)` — tenta casar o item com um produto já cadastrado (por código de barras, nome ou código interno).
    - **IPI**: se houver `<IPI><IPITrib><vIPI>`, divide pelo `qCom` e soma ao valor unitário.
    - **ICMS-ST**: se houver `vICMSST > 0`, divide pelo `qCom` e soma ao valor unitário.
    - Remove apóstrofos do nome; normaliza o código (`cProd`) deixando só dígitos.
    - Monta um stdClass por item: id (0 = novo), código, nome, NCM, CEST, CFOP, unidade, valor unitário (com IPI/ST), quantidade, subtotal, código de barras, CST/CSOSN, %ICMS, %PIS, %COFINS, %IPI, %RED BC, código benefício fiscal.
    - Conta quantos itens ficaram **sem produto cadastrado** (`contSemRegistro`) — esses serão cadastrados na confirmação.
    - `relacaoDadosFornecedor($prod)` busca dados de relação produto/fornecedor.

### 1.5 Totais, fatura (duplicatas) e dados gerais
11. Lê do XML: `vNF` (total), `vFrete`, `vDesc`, `indPag`, `nNF`, `dhEmi`.
12. Monta a **fatura**:
    - Se houver `<cobr><dup>`: uma parcela por duplicata (número, vencimento `dVenc`, valor `vDup`, tipo de pagamento `tPag`).
    - Senão: parcela única com vencimento na data de emissão e valor = `vProd`.
13. Carrega para a tela: transportadoras, cidades e **naturezas de operação** da empresa.
    - Se a empresa **não tiver natureza de operação cadastrada**: flash "Primeiro cadastre um natureza de operação!" e redireciona para `natureza-operacao.create` (**a importação para aqui**).
14. Lê o **percentual de lucro padrão** de produtos do `ConfigGeral` da empresa.
15. Renderiza `compras/import_xml.blade.php` com os dados em `$dadosXml`.

### 1.6 A tela de confirmação (onde fica o botão Salvar)
**View:** `resources/views/compras/import_xml.blade.php` + partial `compras/_forms_xml.blade.php`

- Formulário POST multipart → `compras.finish-xml`, com id `form-nfe` e `novalidate` (a validação é feita por JS para dar feedback visual).
- **4 abas:**
  1. **Fornecedor** — select `fornecedor_id` pré-preenchido com o fornecedor criado/encontrado + campos de edição (nome, CNPJ, IE, cidade, endereço...). Ao trocar o select, `nfe.js` chama `GET /api/fornecedores/find/{id}` e preenche os campos.
  2. **Produtos** — uma linha por item do XML. Itens com `produto_id = 0` mostram "*Produto será cadastrado no sistema*". Campos editáveis: valor de venda, conversão de estoque, %ICMS, %PIS, %COFINS, %IPI, %RED BC, CFOP, NCM, CSTs. Botões para adicionar/remover linhas.
  3. **Frete** — transportadora (select + dados) e valores de frete/volumes.
  4. **Fatura** — natureza de operação (**required**), acréscimo, desconto, número da NFe, chave importada (readonly), flag "gerar conta a pagar" e a tabela de parcelas (tipo de pagamento, vencimento, valor).
- **JS responsável:** `public/js/nfe.js` (totais, selects, máscaras) e `public/js/import_xml.js` (select2 dos produtos).
- Cálculos em tempo real: `calcTotal()` soma os subtotais → `total_prod`; `calTotalNfe()` soma produtos + frete + acréscimo − desconto → grava em `valor_total` (hidden); `calcTotalFatura()` soma as parcelas.
- Botão **Salvar** (`btn-salvar-nfe`): a validação é interceptada por JS (`addClassRequired()` verifica required vazios e a existência de produto). Se estiver tudo OK, o formulário é enviado via POST para `compras.finish-xml`.

---

## ETAPA 2 — Gravação da compra (`/compras-finish-xml`)

**Controller:** `CompraController@finishXml` — tudo dentro de `DB::transaction()` (ou dá erro e **nada é gravado**).

1. **Fornecedor:**
   - `fornecedor_id` preenchido → `atualizaFornecedor()` atualiza os dados com o que está na tela.
   - Vazio → `cadastrarFornecedor()` grava um novo com os dados do formulário.
2. **Transportadora:**
   - `transportadora_id` preenchido → `atualizaTransportadora()` (só se `razao_social_transp` estiver preenchido).
   - Vazio → `cadastrarTransportadora()` (só se `razao_social_transp` preenchido; senão fica `null`).
3. Busca a **empresa** e exige **caixa aberto** (senão lança exceção "Abrir caixa antes de continuar!").
4. Faz `merge` no request: emissor, ambiente, `chave_importada`, estado `novo`, totais convertidos com `__convert_value_bd()` (formato BR → BD), `caixa_id`, `local_id`, `user_id`.
5. **`Nfe::create()`** — grava o cabeçalho da compra (a compra é uma NFe de entrada: `tpNF = 0`, `finNFe` etc.).
6. **Loop pelos produtos** (`produto_id[]`, `quantidade[]`, `valor_unitario[]`...):
   - `produto_id = 0` → **cadastra o produto novo** (`cadastrarProduto()`: nome, NCM, unidade, CFOPs estadual/outro estado, CSTs, percentuais, cest, valor de compra e valor de venda).
   - Senão → busca o produto existente.
   - Aplica **conversão de estoque** (quantidade × conversão; valor unitário ÷ conversão).
   - **`ItemNfe::create()`** — grava o item vinculado à NFe.
   - `ProdutoFornecedor::updateOrCreate()` — vincula produto ↔ fornecedor.
   - Atualiza `valor_compra` do produto.
   - Se o produto **gerencia estoque** → `incrementaEstoque()` (**entrada no estoque**).
7. Se veio de **manifestação do destinatário** (`chave_dfe`), vincula o DFH à compra (`ManifestoDfe.compra_id`).
8. **Fatura:** para cada parcela enviada (`tipo_pagamento[]`, `data_vencimento[]`, `valor_fatura[]`):
   - `FaturaNfe::create()` — grava a parcela.
   - Se "gerar conta a pagar" = 1 → **`ContaPagar::create()`** (conta a pagar por parcela, vinculada à empresa, NFe e local).
9. **Commit** da transação. Em caso de exceção → rollback + flash "Algo deu errado ..." + log de erro.
10. Grava log do sistema (`__createLog` → "Importação XML / cadastrar"), flash "Importação cadastrada!" e redireciona para `compras.index`.

---

## Resumo visual do fluxo

```
Usuário escolhe o XML
        │
        ▼
POST /store-xml ──► CompraController@storeXml
        │                ├─ valida o arquivo (NFe/infNFe)
        │                ├─ salva o XML em public/xml_entrada/{chave}.xml
        │                ├─ cria/encontra o FORNECEDOR
        │                ├─ casa os itens com PRODUTOS existentes (ou marca como novos)
        │                ├─ extrai totais, fatura/duplicatas
        │                └─ (sem natureza de operação? redireciona para o cadastro)
        ▼
Tela de confirmação (abas Fornecedor/Produtos/Frete/Fatura)
        │
        ▼  botão Salvar (JS valida e envia)
POST /compras-finish-xml ──► CompraController@finishXml  [DB::transaction]
        │                ├─ grava/atualiza fornecedor
        │                ├─ grava/atualiza transportadora (se houver)
        │                ├─ Nfe::create (cabeçalho da compra)
        │                ├─ para cada item: cria produto novo se id=0,
        │                │   ItemNfe::create, vínculo produto-fornecedor,
        │                │   valor_compra e ENTRADA NO ESTOQUE
        │                ├─ FaturaNfe::create por parcela
        │                ├─ ContaPagar::create (se marcado)
        │                └─ commit → flash "Importação cadastrada!"
        ▼
Redireciona para compras.index (listagem de compras)
```

---

## Problema encontrado no botão Salvar (e correções aplicadas)

### Causa raiz
1. **O formulário não tinha `id`**: o `nfe.js` procura `#form-nfe` (usado em NFe) e o botão dependia do submit nativo do HTML5.
2. **Campos `required` em abas ocultas**: o campo **Natureza de Operação** (aba Fatura) e vários campos do Fornecedor são `required`, mas ficam em abas não visíveis. O navegador **bloqueia o submit silenciosamente** e mostra o balão de validação sobre um campo que o usuário nem vê — a impressão é que "clicar não faz nada".
3. **Bug fatal no backend**: `finishXml` chamava `$this->cadastrarFornecedor($request)`, mas o método **não existia** no controller. Se o fornecedor viesse vazio, a gravação quebraria com erro fatal.
4. **Erro em `storeXml`**: o `%COFINS` era lido de `pPIS` (`$prod->perc_cofins = $arr[0]->pPIS`) — o valor gravado saía errado.

### Correções aplicadas
| Arquivo | Correção |
|---|---|
| `resources/views/compras/import_xml.blade.php` | Adicionado `->id('form-nfe')` e `->attrs(['novalidate' => 'novalidate'])` no `Form::open`; handler próprio do botão Salvar que valida via `addClassRequired()` e só envia se estiver OK. |
| `public/js/nfe.js` | `addClassRequired()` agora **retorna true/false**, trata `val() == null`, mostra o nome do campo quando não acha o label e **troca automaticamente para a aba** do primeiro campo pendente (o usuário vê o que falta preencher). |
| `app/Http/Controllers/CompraController.php` | Criado o método `cadastrarFornecedor()` que faltava (gravava via `Fornecedor::create` com os dados do formulário). |
| `app/Http/Controllers/CompraController.php` | Em `finishXml`, se não houver caixa aberto agora lança exceção clara ("Abrir caixa antes de continuar!") em vez de quebrar com erro de null (`$caixa->local_id`). |
| `app/Http/Controllers/CompraController.php` | Em `storeXml`, `%COFINS` agora é lido de `pCOFINS` corretamente. |
| `resources/views/compras/_forms_xml.blade.php` | A cidade do fornecedor vinda do XML (`fornecedor->cidade_id`) passa a vir **pré-selecionada** na tela de confirmação (antes só preenchia quando havia `$item`). |

### Como testar
1. Abra `http://127.0.0.1:8000/compras-xml` (com caixa aberto).
2. Selecione o XML da nota de compra — a tela `/store-xml` de confirmação abrirá automaticamente.
3. Clique em **Salvar** sem preencher a Natureza de Operação: o sistema deve **mudar para a aba Fatura** e mostrar o alerta "Campos pendentes" (antes: nada acontecia).
4. Preencha a natureza e clique em Salvar: deve gravar, mostrar "Importação cadastrada!" e voltar para a listagem de compras com estoque, fatura e (opcional) conta a pagar gerados.
