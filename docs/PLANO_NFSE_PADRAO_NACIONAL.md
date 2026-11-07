# 📋 Plano de Implementação — NFS-e Padrão Nacional (Portal da Receita Federal)

> **Data de Planejamento:** 05/11/2026  
> **Status:** Pronto para Início de Implementação  
> **Objetivo:** Atualizar o módulo de emissão de Nota Fiscal de Serviços Eletrônica (NFS-e) do ERP, substituindo a dependência da Cloud DFe por integração direta via API com o **Emissor Nacional da Receita Federal (Padrão Nacional DPS)**, eliminando particularidades e custos de prefeituras individuais.

---

## 🏛️ 1. Contexto Legal e Normativo

* **Legislação Base:** Lei Complementar nº 116/2003 e Resolução CGSN nº 169/2022.
* **Obrigatoriedade Atual:**
  * **MEIs:** Desde 1º de setembro de 2023, todos os Microempreendedores Individuais prestadores de serviços de todo o território brasileiro são legalmente obrigados a emitir suas notas fiscais exclusivamente pelo Sistema Nacional da NFS-e.
  * **Demais Empresas (ME, EPP, Presumido, Real):** Mais de 1.000 municípios já formalizaram convênio com a Receita Federal e migraram suas emissões municipais para o ambiente nacional. Com a Reforma Tributária (IBS/CBS), a unificação será de 100%.
* **Vantagens da Migração:**
  * **Custo Zero por Emissão:** A API do governo federal é pública e gratuita para empresas emissoras.
  * **Padrão Unificado:** Acaba a dor de cabeça de integrar com dezenas de provedores municipais diferentes (Betha, ISSNet, Ginfes, IPM, WebISS, SimplISS, etc.).
  * **Tabela Nacional de Serviços:** Elimina códigos de tributação que só valiam para uma única cidade.

---

## 🔍 2. Diagnóstico Técnico do Módulo Atual do ERP

### 2.1. O que temos hoje no ERP:
1. **Infraestrutura Pronta (~80%):**
   * CRUD de Serviços com discriminador e alíquotas de tributos (`app/Models/Servico.php`).
   * Cadastro de Clientes / Tomadores (`app/Models/Cliente.php`).
   * Controle Financeiro integrado (gera Conta a Receber e baixa no Caixa Aberto).
   * **Certificado Digital A1 já integrado no sistema:** O ERP já manipula e assina notas com Certificado A1 para NF-e (modelo 55) e NFC-e (modelo 65) via biblioteca `NFePHP\Common\Certificate`.
2. **Onde estava o gargalo / quebra:**
   * O controller de transmissão (`app/Http/Controllers/API/NotaServicoController.php`) utiliza uma versão legada do pacote `cloud-dfe/sdk-php: ^0.4.0`.
   * Esse pacote dependia de token de Softwarehouse comprado de terceiros (`ConfiguracaoSuper->token_auth_nfse`), de envio do certificado para servidores de terceiros e de comunicação com provedores municipais antigos.
   * Se o token estiver vencido, incorreto ou a prefeitura migrou para o portal nacional, o envio falha.

---

## 🏗️ 3. A Arquitetura do Padrão Nacional (DPS ➔ NFS-e)

```
[ ERP / Tela de Serviço ]
           │
           ▼
[ Gera XML da DPS (Declaração de Prestação de Serviços) ]
           │
           ▼
[ Assina o XML da DPS com o Certificado Digital A1 da Empresa ]
           │
           ▼
[ Envia via API REST (HTTPS + mTLS com Certificado A1) ]
           │
           ▼
[ Portal Nacional da NFS-e (Receita Federal / SERPRO) ]
           │
           ├─► Homologação: https://hom-nfse.receita.fazenda.gov.br/portal/
           └─► Produção:    https://nfse.receita.fazenda.gov.br/portal/
           │
           ▼
[ Resposta da Receita Federal: Autorização Imediata ]
           ├─► Chave de Acesso Nacional de 50 dígitos
           ├─► Número Oficial da NFS-e
           ├─► Link / ByteStream do DANFSE Nacional em PDF (com QR Code)
           └─► XML Oficial Assinado da NFS-e
```

### 3.1. Documentos Envolvidos:
* **DPS (Declaração de Prestação de Serviços):** Documento emitido pelo ERP antes da nota existir. Contém os dados do prestador, tomador, serviço, valores e retenções.
* **NFS-e Nacional:** Documento oficial gerado e assinado pela Receita Federal a partir da DPS.
* **DANFSE:** Documento Auxiliar da NFS-e (PDF com layout nacional padronizado).

---

## 📊 4. Mapeamento do Banco de Dados

### 4.1. Tabela `nota_servicos`
Campos já existentes:
* `id`, `empresa_id`, `cliente_id`, `cidade_id`, `valor_total`, `estado` (`novo`, `aprovado`, `cancelado`, `rejeitado`), `chave`, `ambiente`, `numero_nfse`, `url_xml`, `url_pdf_nfse`.

Campos a serem adicionados para o Padrão Nacional:
* `numero_dps` (integer) — Número sequencial da DPS.
* `serie_dps` (varchar) — Série da DPS (ex: `1` ou `900`).
* `chave_dps` (varchar) — Identificador único da DPS.
* `xml_dps` (text / mediumtext) — Armazena o XML assinado enviado.
* `motivo_rejeicao` (text) — Mensagem amigável da SEFIN/Receita em caso de erro.

### 4.2. Tabela `servicos` / `item_nota_servicos`
* Adicionar `codigo_tributacao_nacional` (varchar 6-7 dígitos): Código padronizado da lista nacional de serviços (ex: `01.01.01` ou `010101`).

---

## 🚀 5. Plano de Implementação em 5 Fases

### 📅 Fase 1: Ajuste de Banco de Dados e Parâmetros
1. Criar migration adicionando os campos de DPS na tabela `nota_servicos`.
2. Adicionar o campo de Código de Tributação Nacional no cadastro de `servicos`.
3. Inserir na configuração da empresa os parâmetros de ambiente (`1 = Produção`, `2 = Homologação`) e último número de DPS emitido.

### 📅 Fase 2: Motor de Geração e Assinatura da DPS (`NfseNacionalService.php`)
1. Criar `app/Services/NfseNacionalService.php`.
2. Implementar método `montarXmlDps(NotaServico $nota)` conforme o esquema oficial da Receita Federal (`dps_v1.00.xsd`):
   * Grupo `<infDPS>`: Dados do emissor, tomador, serviço, alíquotas de ISS e deduções.
3. Implementar assinatura digital do XML via `NFePHP\Common\Certificate` usando o certificado A1 da própria empresa.

### 📅 Fase 3: Conexão com a API REST da Receita Federal (mTLS)
1. Implementar cliente HTTP com suporte a mTLS (autenticação mútua usando o Certificado Digital A1).
2. Criar os métodos de comunicação:
   * `transmitirDps($xmlAssinado)`: Envia para o endpoint do Portal Nacional e processa a resposta.
   * `consultarNfse($chave)`: Consulta o status da nota e recupera o XML/PDF.
   * `cancelarNfse($chave, $motivo)`: Envia evento de cancelamento da NFS-e.
   * `baixarDanfse($chave)`: Baixa o PDF oficial gerado pelo governo com QR Code.

### 📅 Fase 4: Atualização das Telas e Controladores
1. Atualizar `NotaServicoController.php`:
   * Desvincular chamadas da antiga Cloud DFe.
   * Chamar diretamente o novo `NfseNacionalService`.
2. Atualizar a tela `resources/views/nota_servico/index.blade.php`:
   * Botão de transmissão com feedback em tempo real (SweetAlert / Toastr).
   * Botão para visualizar/baixar o DANFSE oficial em PDF.
   * Botão para baixar o XML da NFS-e.
   * Modal de cancelamento conectado diretamente à API nacional.
3. Simplificar a tela `resources/views/nota_servico/create.blade.php` com o Código de Tributação Nacional.

### 📅 Fase 5: Testes em Homologação e Virada de Chave
1. Configurar uma empresa de testes no Ambiente de Homologação Nacional da Receita Federal.
2. Emitir a primeira DPS de teste.
3. Validar retorno de autorização, número da NFS-e e download do PDF/XML.
4. Testar fluxo de cancelamento.
5. Testar baixa financeira no Contas a Receber e Caixa.

---

## 📌 Checklist para Início dos Trabalhos (Amanhã)

- [x] Rodar a migration para adicionar os campos de DPS na tabela `nota_servicos` e código nacional em `servicos` (Fase 1 Concluída).
- [ ] Criar a classe `app/Services/NfseNacionalService.php`.
- [ ] Implementar a geração da tag `<infDPS>` e a assinatura digital com o certificado A1 da empresa.
- [ ] Fazer o primeiro teste de conexão com o endpoint de homologação da Receita Federal.
- [ ] Ajustar o botão de transmissão na tela para o novo fluxo.

---
*Documento gerado automaticamente para preservação do planejamento e continuidade dos trabalhos.*

