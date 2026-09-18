import re

with open(r"c:\xampp\htdocs\ERP\resources\views\mdfe\_forms.blade.php", "r", encoding="utf-8") as f:
    content = f.read()

# 1. Update Seguradora Block
seguradora_target = """                    <h5 class="card-title mb-0 fs-13 text-uppercase text-muted fw-bold">Seguradora (Opcional)</h5>
                </div>
                <div class="card-body p-3">
                    <div class="row g-2">
                        <div class="col-md-6 col-12">
                            {!! Form::text('seguradora_nome', 'Nome da seguradora')->attrs(['class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-6 col-12">
                            {!! Form::tel('seguradora_cnpj', 'CNPJ da seguradora')->attrs(['class' => 'form-control cpf_cnpj']) !!}
                        </div>
                        <div class="col-md-6 col-12">
                            {!! Form::text('numero_apolice', 'Número da apólice')->attrs(['class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-6 col-12">
                            {!! Form::text('numero_averbacao', 'Número da averbação')->attrs(['class' => 'form-control']) !!}
                        </div>
                    </div>"""

seguradora_replacement = """                    <h5 class="card-title mb-0 fs-13 text-uppercase text-muted fw-bold">Seguradora (Opcional)</h5>
                </div>
                <div class="card-body p-3">
                    <div class="row g-2">
                        <div class="col-md-12 col-12">
                            {!! Form::select('resp_seguro', 'Responsável pelo Seguro', ['' => 'Selecione...', '1' => 'Emitente do MDF-e', '2' => 'Contratante do Frete'])->attrs(['class' => 'form-select']) !!}
                        </div>
                        <div class="col-md-6 col-12">
                            {!! Form::text('seguradora_nome', 'Nome da seguradora')->attrs(['class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-6 col-12">
                            {!! Form::tel('seguradora_cnpj', 'CNPJ da seguradora')->attrs(['class' => 'form-control cpf_cnpj']) !!}
                        </div>
                        <div class="col-md-6 col-12">
                            {!! Form::text('numero_apolice', 'Número da apólice')->attrs(['class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-6 col-12">
                            {!! Form::text('numero_averbacao', 'Número da averbação')->attrs(['class' => 'form-control']) !!}
                        </div>
                    </div>"""

content = content.replace(seguradora_target, seguradora_replacement)

# 2. Add Pagamento Block right after Vale Pedágio
pedagio_target = """        <div class="col-md-6 col-12">
            <div class="card border border-light-subtle shadow-none h-100">
                <div class="card-header bg-light border-bottom-0 py-2">
                    <h5 class="card-title mb-0 fs-13 text-uppercase text-muted fw-bold">Vale Pedágio (Opcional)</h5>
                </div>
                <div class="card-body p-3">
                    <div class="row g-2">
                        <div class="col-md-4 col-12">
                            {!! Form::text('vale_pedagio_cnpj_fornecedor', 'CNPJ Fornecedor')->attrs(['class' => 'form-control cpf_cnpj']) !!}
                        </div>
                        <div class="col-md-4 col-12">
                            {!! Form::text('vale_pedagio_cnpj_pagador', 'CNPJ Pagador')->attrs(['class' => 'form-control cpf_cnpj']) !!}
                        </div>
                        <div class="col-md-4 col-12">
                            {!! Form::text('vale_pedagio_numero', 'Nº Comprovante')->attrs(['class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>"""

pagamento_block = pedagio_target + """

        {{-- ═══ PAGAMENTO DO FRETE ═══ --}}
        <div class="col-md-12 col-12">
            <div class="card border border-light-subtle shadow-none h-100">
                <div class="card-header bg-light border-bottom-0 py-2">
                    <h5 class="card-title mb-0 fs-13 text-uppercase text-muted fw-bold">Pagamento do Frete (Opcional)</h5>
                </div>
                <div class="card-body p-3">
                    <div class="row g-2">
                        <div class="col-md-3 col-12">
                            {!! Form::text('nome_contratante', 'Nome do Contratante')->attrs(['class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-3 col-12">
                            {!! Form::tel('cpf_cnpj_contratante', 'CPF/CNPJ do Contratante')->attrs(['class' => 'form-control cpf_cnpj']) !!}
                        </div>
                        <div class="col-md-2 col-12">
                            {!! Form::tel('vAdiant', 'Valor Adiantamento')->attrs(['class' => 'form-control moeda']) !!}
                        </div>
                        <div class="col-md-2 col-12">
                            {!! Form::select('indPag', 'Indicador de Pagamento', ['' => 'Selecione...', '0' => 'Pagamento à Vista', '1' => 'Pagamento a Prazo'])->attrs(['class' => 'form-select']) !!}
                        </div>
                        <div class="col-md-2 col-12">
                            {!! Form::tel('vPrest', 'Valor da Prestação')->attrs(['class' => 'form-control moeda']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>"""

content = content.replace(pedagio_target, pagamento_block)

# 3. Apply Modernization Classes Safely
# .card -> .form-section-card
content = re.sub(
    r'<div class="card border border-light-subtle[^"]*">',
    r'<div class="form-section-card">',
    content
)

# .card-header -> .section-header
content = re.sub(
    r'<div class="card-header[^"]*">',
    r'<div class="section-header">',
    content
)

# .card-body -> .section-body
content = re.sub(
    r'<div class="card-body[^"]*">',
    r'<div class="section-body">',
    content
)

# card-title -> h5 (no extra classes needed since section-header h5 is styled in CSS)
content = re.sub(
    r'<h5 class="card-title[^"]*">',
    r'<h5>',
    content
)

with open(r"c:\xampp\htdocs\ERP\resources\views\mdfe\_forms.blade.php", "w", encoding="utf-8") as f:
    f.write(content)

print("Done")
