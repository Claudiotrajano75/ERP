<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
# novoerp




<!-- Correção NFC-e -->

Correção do Erro de Comunicação NFC-e (Proibido 403)
O problema "Erro de comunicação via soap, Proibido" foi resolvido. A causa raiz era que a SEFAZ do Ceará (CE) desativou seus próprios servidores de autorização para o modelo 65 (NFC-e) e migrou para a SVRS (Sefaz Virtual Rio Grande do Sul). A biblioteca 
nfephp-org/sped-nfe
 possuía URLs desatualizadas para o estado do Ceará.

Alterações Realizadas
1. Atualização das URLs da SEFAZ CE
Modifiquei o arquivo de configuração de Web Services da biblioteca para apontar para os endpoints da SVRS:

Arquivo: 
wsnfe_4.00_mod65.xml
Mudança: Substituídas todas as URLs de Homologação e Produção do Ceará pelas correspondentes da SVRS.
2. Ajustes de Compatibilidade no Serviço
Ajustei a inicialização do 
SoapCurl
 para garantir que o SSL não seja verificado de forma rígida, o que é comum em ambientes de desenvolvimento e produção com SEFAZ:

Arquivo: 
NFCeService.php
Mudança: Adicionado 
disableSecurity(true)
 e configurado 
httpVersion(null)
 para permitir que o cURL escolha a melhor versão automaticamente.
Verificação dos Resultados
Realizei testes de consulta de status de serviço tanto em Homologação quanto em Produção, utilizando o certificado da empresa MSG INDUSTRIA E COMERCIO DE ALIMENTOS LTDA.

Resultado do Teste (Produção)
Testando Status para Empresa: MSG INDUSTRIA E COMERCIO DE ALIMENTOS LTDA | UF: CE | Ambiente: Produção
cStat: 107
xMotivo: Servico em Operacao
Resultado do Teste (Homologação)
Testando Status para Empresa: MSG INDUSTRIA E COMERCIO DE ALIMENTOS LTDA | UF: CE | Ambiente: Homologação
cStat: 107
xMotivo: Servico em Operacao
IMPORTANT

A emissão de NFC-e para o Ceará agora deve ocorrer normalmente. Foi confirmado que o erro 403 era uma mensagem de "Serviço Desativado" enviada pelos antigos servidores da SEFAZ CE.

WARNING

Como a alteração das URLs foi feita em um arquivo dentro do diretório vendor, se você executar um composer update ou composer install de forma limpa, essa alteração poderá ser perdida. Recomenda-se manter backup desta alteração ou atualizar a biblioteca nfephp-org para uma versão que já contenha as URLs da SVRS para o CE (se disponível).


<!-- CSC Homologação -->
1	B2FA1727-4C7B-4622-AC58-30CFD7FCFE70	28/11/2025 00:00	
2	12A90A11-C112-46B2-83E1-6EAF198B702C	28/11/2025 00:00

<!-- CSC Produção -->




<!-- Sobre o novo impostos ibs e cbs -->
Com base na análise que fiz do seu sistema (especialmente o 
NFCeService.php
 e a estrutura de modelos), aqui está uma avaliação técnica sobre a implementação do IBS e CBS (Reforma Tributária):

Nível de Complexidade: Média
Não é uma alteração "simples" (como mudar um campo de texto), mas também não é uma "reestruturação total" do software. Vou dividir o motivo em 3 pontos:

1. Dependência da Biblioteca Base (NFePHP)
Seu sistema utiliza a biblioteca nfephp-org/sped-nfe.

A boa notícia: Eles são a base de quase todos os sistemas PHP no Brasil. Assim que a SEFAZ liberar o novo layout (provavelmente a versão 5.0 da NF-e), a biblioteca será atualizada com os novos métodos (ex: tagIBS(), tagCBS()).
O trabalho: Você precisará atualizar essa biblioteca via composer, o que pode exigir ajustes de compatibilidade em outras partes do código.
2. Alterações no 
NFCeService.php
Hoje, seu código percorre os itens e gera as tags de ICMS, PIS e COFINS de forma manual.

Para o IBS/CBS, teremos que criar novos blocos de código dentro do loop de produtos para substituir ou conviver com os tributos atuais durante a transição.
Como seu código atual já está bem organizado (separando o que é Simples Nacional do que é Regime Normal), a inserção da nova lógica de cálculo será direta.
3. Mudanças no Banco de Dados e Telas
Esta é a parte que exige mais atenção:

Banco de Dados: Precisaremos criar novas colunas nas tabelas de produtos, itens_venda e configurações_da_empresa para armazenar as alíquotas de IBS e CBS.
Telas: Sua interface de cadastro de produtos e de impostos precisará de novos campos para que o usuário possa configurar essas alíquotas.
Conclusão: É seguro fazer?
Sim, pode ser feito sem problemas.

O seu sistema segue o padrão MVC (Laravel) e utiliza serviços isolados para as notas fiscais. Isso é o cenário ideal para esse tipo de atualização, pois as mudanças ficam concentradas em poucos arquivos (Service, Model e Migrations).

Resumo: Quando a reforma entrar em vigor para valer na NFC-e, o caminho será:

Atualizar o NFePHP.
Adicionar campos no banco de dados.
Adaptar a lógica de "tags" no 
NFCeService
.
Dica: Como o seu sistema já trata diferentes regimes tributários, a transição será muito mais tranquila do que em sistemas legados ou desorganizados. Seu código está "saudável" para essa evolução.


 Quando a reforma entrar em vigor para valer na NFC-e, e nao entrou em vigor ainda?

Thought for 2s


Não, ainda não entrou em vigor para o dia a dia das empresas.

Estamos agora (janeiro de 2026) iniciando o período que o governo chama de "fase de teste" ou "transição". Veja como funciona o cronograma oficial da Reforma Tributária:

1. Ano de 2026 (O "Teste")
O que começa agora: Uma alíquota experimental bem pequena (0,1% de IBS e 0,9% de CBS).
O que muda na nota: A SEFAZ ainda não tornou obrigatório o novo layout "5.0" com as tags exclusivas de IBS/CBS para todos. O objetivo deste ano é apenas coletar dados. Na prática, as notas de venda de varejo (como as suas) continuam saindo com ICMS, PIS e COFINS como sempre foram.
2. Ano de 2027 (A Mudança Real - Federal)
Adeus PIS e COFINS: O PIS e a COFINS deixam de existir e são substituídos 100% pela CBS (Contribuição sobre Bens e Serviços).
Neste momento, teremos que fazer a primeira grande mudança no seu código para remover o cálculo de PIS/COFINS e inserir a CBS.
3. De 2029 até 2033 (A Transição do ICMS)
O ICMS (que é o que mais dá erro nas suas notas hoje) começará a ser reduzido aos poucos, e o IBS (Imposto sobre Bens e Serviços) começará a subir.
Somente em 2033 o ICMS deixará de existir completamente.
Por que você não precisa se preocupar hoje?
A SEFAZ ainda não publicou a Nota Técnica definitiva que obriga a mudança do XML da NFC-e para o varejo geral.

Resumo da ópera:

Hoje: Você continua usando o que acabamos de arrumar (Simples Nacional, CSOSN 102, etc).
Final de 2026: Devemos ficar de olho na atualização da biblioteca NFePHP.
Início de 2027: Será o momento de realmente sentarmos para atualizar o campo de CBS no seu sistema.
Como o seu sistema é um ERP moderno, essa transição será feita via código de forma que você quase não perceba a complexidade. O programa fará o cálculo "por baixo dos panos" e você só verá os novos nomes dos impostos na tela


Existe uma diferença fundamental entre as duas telas:

1. A Tela Padrão (/nfe/create — Nova Venda NFe)
Ela é voltada para a Venda de Mercadorias. No fluxo do seu ERP:

Ela gera uma nota atrelada a uma transação comercial de saída (venda).
Ela obrigatoriamente assume que a operação movimentará o estoque e gerará parcelas financeiras (contas a receber) de forma fixa e integrada ao fechamento de caixa do dia.
Ela não permite a edição manual avançada de impostos por item (como alterar alíquotas e bases de cálculo de PIS/COFINS/IPI/ICMS dinamicamente) durante o lançamento — o sistema apenas aplica a tributação automática pré-configurada no produto e na natureza da operação.
2. A Nova Tela (/faturamento-avulso/create — Faturamento à Parte)
Ela foi desenhada para a Emissão Avulsa (Direta) de NFe, sem a obrigatoriedade de ser uma "venda":

Liberdade Fiscal: É possível abrir o modal de cada item e mudar qualquer detalhe de imposto manualmente (como CST, base de cálculo e alíquota de ICMS, PIS, COFINS e IPI). Isso é fundamental para notas que não são vendas, como devolução de compra para fornecedor, remessa de conserto, transferência de estoque, entre outras.
Flexibilidade Operacional: Você tem as chaves liga/desliga para escolher se a emissão deve ou não abaixar estoque, e se deve ou não gerar financeiro no contas a receber (na tela de vendas comum, você não tem essa flexibilidade).
Organização em etapas: É muito mais simples e limpa para notas complexas, evitando a tabela gigante e confusa de rolagem horizontal da tela padrão.
Resumo: A de /nfe/create serve para registrar Vendas normais com Nota, enquanto a nova de /faturamento-avulso/create serve para Faturamentos/Notas avulsas de qualquer tipo (Devolução, Remessa, Ajustes, etc.) sem prender-se a uma venda comercial.