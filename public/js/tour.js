$(function () {
    setTimeout(() => {
        if ($("#step1").length && $("#step2").length && $("#step3").length && $("#step4").length && $("#step5").length && $("#step6").length){
            let toutVar = window.localStorage.getItem('tour-app-sym');
            if(!toutVar){
                var tour = new Tour(steps);
                tour.show();
                window.localStorage.setItem('tour-app-sym', true);
            }
        }
    }, 200);
});

var steps = [
{
  title: "👋 Bem-vindo ao ERP!",
  content: "<p>Seja bem-vindo ao sistema! Preparamos um breve tour guiado para apresentar os principais recursos e acelerar o seu fluxo de trabalho.</p>"
}, 
{
  id: "step1",
  title: "⚡ Conta, Plano & Ambiente",
  content: "<p>Aqui você visualiza rapidamente seu <strong>ambiente fiscal</strong>, <strong>endereço IP</strong>, o <strong>plano ativo</strong> e a <strong>data de expiração</strong>, com opção rápida para upgrade.</p>"
},
{
  id: "step2",
  title: "🌓 Alternar Tema",
  content: "<p>Alterne entre o <strong>Modo Claro</strong> e o <strong>Modo Escuro</strong> a qualquer momento para maior conforto visual.</p>"
},
{
  id: "step3",
  title: "👤 Perfil & Atalhos",
  content: "<p>Acesse as informações da sua conta, personalizações de perfil, preferências de segurança e opção para encerrar a sessão.</p>"
},
{
  id: "step4",
  title: "🧭 Menu Principal",
  content: "<p>Navegue de forma intuitiva por todos os módulos do ERP: Vendas, Compras, Financeiro, Fiscal, Estoque e Relatórios.</p>"
},
{
  id: "step5",
  title: "🏢 Configurações da Empresa",
  content: "<p>Gerencie os dados cadastrais da empresa, certificado digital A1, natureza de operação padrão e parâmetros fiscais de emissão.</p>"
},
{
  id: "step6",
  title: "👥 Cadastros Gerais",
  content: "<p>Gerencie seus clientes, fornecedores, transportadoras, funcionários e usuários do sistema com controle de permissões.</p>"
}, 
{
  id: "step7",
  title: "📦 Produtos & Estoque",
  content: "<p>Controle seu catálogo de produtos, categorias, variações, códigos de barras, regras de ICMS/IPI e movimentações de estoque.</p>"
}, 
{
  title: "🎉 Tudo Pronto para Começar!",
  content: "<p>Esperamos que tenha uma experiência incrível e produtiva! Você pode reiniciar este tour sempre que desejar clicando no botão <strong>Tour</strong>.</p>"
}
];

$('#click-tour').click(() => {
    if ($("#step1").length && $("#step2").length && $("#step3").length && $("#step4").length && $("#step5").length && $("#step6").length){
        var tour = new Tour(steps);
        tour.show();
    }
});
