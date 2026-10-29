---
name: erp-busca-cep
description: Guia completo e padronizado para aplicar a busca automática de endereço por CEP via ViaCEP e BrasilAPI em formulários e cadastros do ERP.
---

# Padrão de Busca Automática de Endereço por CEP no ERP

Esta skill documenta a implementação e reutilização do mecanismo de consulta automática de endereço por CEP no ERP em Laravel.

---

## 1. Arquitetura da Solução

O sistema utiliza uma abordagem em duas camadas de alta resiliência e performance:

1. **Backend Laravel (Nativo - sem pacotes externos)**:
   - Endpoint: `GET /api/consulta-cep/{cep}` e `GET /consulta-cep/{cep}`
   - Controller: `App\Http\Controllers\API\HelperController@consultaCep`
   - Realiza validação de formato (8 dígitos numéricos).
   - Consulta primária: **ViaCEP** (`https://viacep.com.br/ws/{cep}/json/`) com timeout de 5 segundos.
   - Contingência/Fallback: **BrasilAPI** (`https://brasilapi.com.br/api/cep/v1/{cep}`).
   - Enriquecimento de Dados: Localiza automaticamente a cidade correspondente no banco de dados local (`App\Models\Cidade`) através do código IBGE ou Nome/UF, retornando `cidade_id` e `cidade_info` prontos para os componentes `Select2`.

2. **Frontend JavaScript (`public/js/busca-cep.js`)**:
   - Carregado globalmente em `resources/views/layouts/app.blade.php`.
   - Monitora eventos `input`, `keyup` e `blur` nos campos de CEP.
   - Ao completar 8 dígitos, faz a consulta assíncrona, mostra spinner sutil no input e preenche:
     - Logradouro / Rua
     - Bairro
     - Cidade e UF (atualiza Select2 de `cidade_id` e/ou inputs de texto)
   - Mantém **Número** e **Complemento / Referência** **100% manuais e editáveis** (o campo Complemento / Referência nunca é sobrescrito ou preenchido pela API, permanecendo totalmente limpo para o usuário).
   - Coloca automaticamente o foco (`.focus()`) no campo de **Número**.
   - Trata CEP inválido/não encontrado com aviso visual amigável sem travar a digitação manual.

---

## 2. Convenção de Campos nos Formulários Blade

Para que o preenchimento automático funcione sem precisar escrever nenhuma linha adicional de JavaScript na tela, basta utilizar os IDs e classes padrão abaixo:

### Tabela de Mapeamento de Elementos

| Campo | IDs Reconhecidos | Nomes / Classes Reconhecidas |
| :--- | :--- | :--- |
| **CEP** | `#inp-cep`, `#cep` | `.cep`, `input[name="cep"]` |
| **Logradouro / Rua** | `#inp-rua`, `#rua`, `#inp-logradouro` | `input[name="rua"]`, `input[name="logradouro"]` |
| **Número** | `#inp-numero`, `#numero` | `input[name="numero"]` |
| **Bairro** | `#inp-bairro`, `#bairro` | `input[name="bairro"]` |
| **Cidade (Select2)** | `#inp-cidade_id`, `#cidade_id` | `.cidade_id`, `select[name="cidade_id"]` |
| **Cidade (Texto)** | `#inp-cidade`, `#cidade` | `input[name="cidade"]` |
| **UF (Estado)** | `#inp-uf`, `#uf` | `input[name="uf"]`, `select[name="uf"]` |
| **Complemento** | `#inp-complemento`, `#complemento` | `input[name="complemento"]` |

---

## 3. Exemplo Prático em Formulários Blade

### Exemplo A: Formulário com Select2 para Cidade / UF (Padrão ERP)

```blade
<div class="row g-3">
    <!-- 1. CEP com botão de busca manual -->
    <div class="col-md-3 col-12">
        <label class="form-label fw-bold">CEP <span class="text-danger">*</span></label>
        <div class="input-group">
            {!! Form::tel('cep', false)
                ->attrs(['class' => 'form-control cep', 'id' => 'inp-cep'])
                ->required()
                ->placeholder('00000-000') !!}
            <button class="btn btn-outline-primary" type="button" id="btn-buscar-cep" title="Buscar CEP">
                <i class="ri-search-line"></i>
            </button>
        </div>
        <small class="text-muted fs-11">Digite os 8 números do CEP.</small>
    </div>

    <!-- 2. Rua / Logradouro -->
    <div class="col-md-6 col-12">
        {!! Form::text('rua', 'Logradouro / Rua')
            ->attrs(['id' => 'inp-rua'])
            ->required()
            ->placeholder('Ex: Av. Paulista') !!}
    </div>

    <!-- 3. Número (Recebe o foco automaticamente após a busca) -->
    <div class="col-md-3 col-12">
        {!! Form::text('numero', 'Número')
            ->attrs(['id' => 'inp-numero'])
            ->required()
            ->placeholder('Ex: 100') !!}
    </div>

    <!-- 4. Bairro -->
    <div class="col-md-4 col-12">
        {!! Form::text('bairro', 'Bairro')
            ->attrs(['id' => 'inp-bairro'])
            ->required()
            ->placeholder('Ex: Centro') !!}
    </div>

    <!-- 5. Complemento / Referência (Opcional) -->
    <div class="col-md-4 col-12">
        {!! Form::text('complemento', 'Complemento / Referência')
            ->attrs(['id' => 'inp-complemento'])
            ->placeholder('Ex: Apto 12, Bloco B') !!}
    </div>

    <!-- 6. Cidade / UF via Select2 (Por último) -->
    <div class="col-md-4 col-12">
        {!! Form::select('cidade_id', 'Cidade / UF')
            ->required()
            ->attrs(['class' => 'select2 form-control cidade_id', 'id' => 'inp-cidade_id'])
            ->options($item != null && $item->cidade ? [$item->cidade_id => $item->cidade->info] : []) !!}
    </div>
</div>
```

---

## 4. Uso Programático em JavaScript

Se você precisar disparar a busca ou preencher campos via código customizado (por exemplo, dentro de um modal Ajax ou wizard step):

```javascript
// Disparar busca manual para um CEP específico
window.ERP_CEP.consultar('01001000', function(dados) {
    console.log('Dados do endereço retornados:', dados);
}, $('#meu-formulario'));
```

Retorno JSON do endpoint:
```json
{
  "sucesso": true,
  "erro": false,
  "origem": "viacep",
  "cep": "01001-000",
  "logradouro": "Praça da Sé",
  "rua": "Praça da Sé",
  "complemento": "lado ímpar",
  "bairro": "Sé",
  "cidade": "São Paulo",
  "uf": "SP",
  "ibge": "3550308",
  "cidade_id": 3830,
  "cidade_info": "São Paulo (SP)"
}
```

---

## 5. Boas Práticas e Prevenção de Falhas

1. **Formulários com Abas (Bootstrap Tabs)**:
   - Nunca use `required` nativo do HTML5 em inputs localizados dentro de abas inativas (`display: none`), pois o navegador bloqueia o submit silenciosamente (`An invalid form control is not focusable`).
   - Use `novalidate` na tag `<form>` e valide os campos via JavaScript antes do envio, ativando a aba correspondente caso algum campo obrigatório não tenha sido preenchido.

2. **Select2 e o atributo required**:
   - O Select2 oculta a tag `<select>` nativa. Nunca coloque `required` estrito em `<select class="select2">` dentro de formulários sem validação controlada, pois isso também pode causar bloqueio silencioso no Chrome/Edge.

3. **Fallback Automático**:
   - O `busca-cep.js` tenta primeiro o backend local `/api/consulta-cep/{cep}`. Se o backend estiver indisponível ou sem rota para a internet, ele automaticamente tenta uma consulta direta de contingência via navegador para o ViaCEP.
