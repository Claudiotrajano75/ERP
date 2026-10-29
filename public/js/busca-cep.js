/**
 * busca-cep.js - Utilitário universal de busca automática de endereço por CEP para o ERP
 * Compatível com ViaCEP, BrasilAPI e a rota nativa do ERP (/api/consulta-cep/{cep})
 */
(function ($) {
    "use strict";

    var ultimoCepBuscado = "";
    var debounceTimeout = null;

    /**
     * Resolve a URL base do ERP
     */
    function getBaseUrl() {
        if (typeof path_url !== "undefined" && path_url) {
            return path_url;
        }
        var prot = window.location.protocol;
        var host = window.location.host;
        return prot + "//" + host + "/";
    }

    /**
     * Aplica feedback visual de carregamento no campo CEP
     */
    function setLoading(inputEl, isLoading) {
        var $input = $(inputEl);
        var $wrapper = $input.closest(".input-group, div");

        if (isLoading) {
            $input.addClass("loading-cep").css("background-image", "url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24'%3E%3Cpath fill='%236366f1' d='M12 2A10 10 0 1 0 22 12A10 10 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8A8 8 0 0 1 12 20Z' opacity='.3'/%3E%3Cpath fill='%236366f1' d='M20 12h2A10 10 0 0 0 12 2V4A8 8 0 0 1 20 12Z'%3E%3CanimateTransform attributeName='transform' type='rotate' from='0 12 12' to='360 12 12' dur='1s' repeatCount='indefinite'/%3E%3C/path%3E%3C/svg%3E\")")
                .css("background-repeat", "no-repeat")
                .css("background-position", "calc(100% - 10px) center")
                .css("background-size", "18px 18px");
        } else {
            $input.removeClass("loading-cep")
                .css("background-image", "")
                .css("background-repeat", "")
                .css("background-position", "")
                .css("background-size", "");
        }
    }

    /**
     * Exibe notificação de erro ou aviso
     */
    function avisarUsuario(mensagem, tipo) {
        tipo = tipo || "warning";
        if (typeof toastr !== "undefined") {
            if (tipo === "warning") toastr.warning(mensagem, "Atenção");
            else if (tipo === "error") toastr.error(mensagem, "Erro");
            else toastr.info(mensagem, "Informação");
        } else if (typeof swal !== "undefined") {
            swal("Aviso", mensagem, tipo);
        } else {
            console.warn("[Busca CEP] " + mensagem);
        }
    }

    /**
     * Preenche os campos do formulário com os dados retornados
     */
    function preencherFormulario(container, dados) {
        var $context = container ? $(container) : $(document);

        // 1. Rua / Logradouro
        var $rua = $context.find("#inp-rua, #rua, #inp-logradouro, #logradouro, input[name='rua'], input[name='logradouro']").first();
        if ($rua.length) {
            $rua.val(dados.logradouro || dados.rua || "");
        }

        // 2. Bairro
        var $bairro = $context.find("#inp-bairro, #bairro, input[name='bairro']").first();
        if ($bairro.length) {
            $bairro.val(dados.bairro || "");
        }

        // 3. Cidade & UF
        // 3.1. Se houver Select2 para cidade_id
        var $cidadeSelect = $context.find("#inp-cidade_id, #cidade_id, select[name='cidade_id'], select.cidade_id").first();
        if ($cidadeSelect.length) {
            if (dados.cidade_id && dados.cidade_info) {
                var option = new Option(dados.cidade_info, dados.cidade_id, true, true);
                $cidadeSelect.html("").append(option).trigger("change");
            } else if (dados.ibge) {
                // Tenta resolver pelo código IBGE usando endpoint auxiliar caso não tenha cidade_id direto
                var urlCidade = getBaseUrl() + "api/cidadePorCodigoIbge/" + dados.ibge;
                $.get(urlCidade).done(function (res) {
                    if (res && res.id) {
                        var opt = new Option(res.info, res.id, true, true);
                        $cidadeSelect.html("").append(opt).trigger("change");
                    }
                });
            }
        }

        // 3.2. Se houver inputs de texto comuns para Cidade e UF
        var $cidadeInput = $context.find("#inp-cidade, #cidade, input[name='cidade']").first();
        if ($cidadeInput.length) {
            $cidadeInput.val(dados.cidade || dados.localidade || "");
        }

        var $ufInput = $context.find("#inp-uf, #uf, input[name='uf'], select[name='uf']").first();
        if ($ufInput.length) {
            $ufInput.val(dados.uf || "").trigger("change");
        }

        // 4. Complemento / Referência: mantido 100% limpo e individual para digitação manual do usuário

        // 5. Número e campos adicionais permanecem totalmente editáveis
        // Foca automaticamente no campo de NÚMERO para agilizar a digitação
        var $numero = $context.find("#inp-numero, #numero, input[name='numero']").first();
        if ($numero.length) {
            $numero.focus();
        }
    }

    /**
     * Executa a busca do CEP via Backend ERP com contingência direta para ViaCEP / BrasilAPI no frontend
     */
    function executarBuscaCep(cepLimpo, inputEl) {
        var $form = $(inputEl).closest("form");
        if (!$form.length) {
            $form = $(inputEl).closest(".card, .row, body");
        }

        setLoading(inputEl, true);

        var endpoint = getBaseUrl() + "api/consulta-cep/" + cepLimpo;

        // 1ª Tentativa: Endpoint oficial do backend ERP (Laravel HTTP Client com ViaCEP + BrasilAPI + Model Cidade)
        $.ajax({
            url: endpoint,
            type: "GET",
            dataType: "json",
            timeout: 7000
        }).done(function (resposta) {
            setLoading(inputEl, false);
            if (resposta && resposta.sucesso) {
                ultimoCepBuscado = cepLimpo;
                preencherFormulario($form, resposta);
            } else {
                avisarUsuario("CEP não encontrado. Preencha o endereço manualmente.", "warning");
            }
        }).fail(function (xhr) {
            // Se o backend retornou 404 (CEP não encontrado)
            if (xhr.status === 404) {
                setLoading(inputEl, false);
                avisarUsuario("CEP não encontrado. Digite o endereço manualmente.", "warning");
                return;
            }

            // Se o servidor backend estiver inacessível ou offline na internet,
            // aciona a contingência direta pelo navegador do usuário com ViaCEP
            console.log("[Busca CEP] Tentando contingência direta via navegador...");
            $.ajax({
                url: "https://viacep.com.br/ws/" + cepLimpo + "/json/",
                type: "GET",
                dataType: "json",
                timeout: 5000
            }).done(function (viaCepData) {
                setLoading(inputEl, false);
                if (viaCepData && !viaCepData.erro) {
                    ultimoCepBuscado = cepLimpo;
                    preencherFormulario($form, {
                        logradouro: viaCepData.logradouro,
                        bairro: viaCepData.bairro,
                        cidade: viaCepData.localidade,
                        uf: viaCepData.uf,
                        ibge: viaCepData.ibge
                    });
                } else {
                    avisarUsuario("CEP não localizado. Preencha o endereço manualmente.", "warning");
                }
            }).fail(function () {
                setLoading(inputEl, false);
                avisarUsuario("Não foi possível consultar o CEP automaticamente. Preencha manualmente.", "warning");
            });
        });
    }

    /**
     * Manipulador de eventos para inputs de CEP
     */
    function monitorarCep(e) {
        var $el = $(this);
        var valor = ($el.val() || "").replace(/[^0-9]/g, "");

        // Se tem 8 dígitos
        if (valor.length === 8) {
            if (valor === ultimoCepBuscado && e.type !== "blur") {
                return;
            }

            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(function () {
                executarBuscaCep(valor, $el);
            }, 250);
        } else if (e.type === "blur" && valor.length > 0 && valor.length < 8) {
            avisarUsuario("O CEP informado está incompleto.", "warning");
        }
    }

    // Inicialização e delegação de eventos dinâmicos (funciona para inputs atuais e adicionados via Ajax/Modais)
    $(document).ready(function () {
        // Garantir máscara nos campos com classe .cep caso jQuery Mask esteja carregado
        if ($.fn.mask) {
            $(".cep").mask("00000-000");
        }

        // Delegar eventos de digitação e saída de foco
        $(document).on("input keyup blur", "input.cep, #inp-cep, #cep, input[name='cep']", monitorarCep);
    });

    // Disponibiliza no objeto global window para chamadas programáticas manuais se necessário
    window.ERP_CEP = {
        consultar: function (cep, callback, context) {
            var cepLimpo = (cep || "").replace(/[^0-9]/g, "");
            if (cepLimpo.length !== 8) {
                avisarUsuario("CEP inválido. Deve conter 8 dígitos.", "warning");
                return;
            }
            var endpoint = getBaseUrl() + "api/consulta-cep/" + cepLimpo;
            $.get(endpoint, function (res) {
                if (context) {
                    preencherFormulario(context, res);
                }
                if (typeof callback === "function") {
                    callback(res);
                }
            }).fail(function () {
                avisarUsuario("Não foi possível buscar o CEP.", "warning");
            });
        },
        preencher: preencherFormulario
    };

})(jQuery);
