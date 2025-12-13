<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?= esc($title) ?></h3>
        <?php
        $statusNota = old('status', $notaFiscal['status'] ?? '');
        if ($statusNota !== 'consolidada') {
            if (!empty($notaFiscal['id'])): ?>
                <a href="<?= base_url('itens-nota-fiscal/create/' . esc($notaFiscal['id'])) ?>" class="btn btn-primary float-right">Adicionar Itens na Nota</a>
            <?php endif; ?>
        <?php } ?>
    </div>
    <div class="card-body">
        <div class="error-container" id="error-container">
            <?php if ($errors = session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $e): ?>
                            <li><?= esc($e) ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endif ?>
        </div>

        <form action="<?= base_url('notas-fiscais/store') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <?php if (!empty($notaFiscal['id'])): ?>
                <input type="hidden" name="id" value="<?= esc($notaFiscal['id']) ?>">
            <?php endif ?>

            <!-- <?php if (empty($notaFiscal['id'])): ?>
                <div class="row">
                    <div class="input-group col-sm-12">
                        <div class="custom-file">
                            <input type="file" name="xml" class="custom-file-input custom-file-input-sm" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" accept=".xml">
                            <?php if (!empty($notaFiscal['xml'])): ?>
                                <small>XML atual:</small><br>
                                <a href="<?= base_url('uploads/xml_notasFiscais/' . esc($notaFiscal['xml'])) ?>" class="btn btn-primary btn-sm" target="_blank">Visualizar XML</a>
                            <?php endif ?>

                            <label class="custom-file-label" for="inputGroupFile01" data-browse="Selecionar arquivo">Selecione o arquivo XML da Nota Fiscal</label>
                        </div>
                        <div class="input-group-append">
                            <button class="btn btn-primary" name="carregar-xml" type="submit" id="inputGroupFileAddon04">Carregar dados</button>
                        </div>
                    </div>
                </div>
            <?php endif ?> -->

            <div class="row">
                <div class="form-group col-md-12">
                    <label>Fornecedor</label>
                    <select name="fornecedor_id" id="fornecedor_id" class="form-control form-control-sm <?= session('errors.id') ? 'is-invalid' : '' ?>" required>
                        <option value="">Selecione um fornecedor</option>
                        <?php foreach ($fornecedores as $f): ?>
                            <option value="<?= esc($f['id']) ?>" <?= old('fornecedor_id', $notaFiscal['fornecedor_id'] ?? '') == $f['id'] ? 'selected' : '' ?>>
                                <?= esc($f['nome_fantasia']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row row-sm">
                <div class="form-group col-sm-6">
                    <label>Número da nota</label>
                    <input type="text" name="numero" class="form-control form-control-sm" required
                        value="<?= old('numero', $notaFiscal['numero'] ?? '') ?>">
                </div>
                <div class="form-group col-sm-6">
                    <label>Série</label>
                    <input type="text" name="serie" class="form-control form-control-sm"
                        value="<?= old('serie', $notaFiscal['serie'] ?? '') ?>">
                </div>
            </div>

            <div class="row row-sm">
                <div class="form-group col-sm-6">
                    <label>Data de Emissão</label>
                    <input type="date" name="data_emissao" class="form-control form-control-sm" required
                        value="<?= old('data_emissao', $notaFiscal['data_emissao'] ?? '') ?>">
                </div>

                <div class="form-group col-sm-6">
                    <label>Data de Entrada</label>
                    <input type="date" name="data_entrada" class="form-control form-control-sm" required
                        value="<?= old('data_entrada', $notaFiscal['data_entrada'] ?? '') ?>">
                </div>
            </div>

            <!-- <div class="form-group">
                <label>Chave de Acesso</label>
                <input type="text" name="chave_acesso" class="form-control" required
                    value="<?= old('chave_acesso', $notaFiscal['chave_acesso'] ?? '') ?>">
            </div> -->

            <div class="row row-sm">
                <div class="form-group col-sm-6">
                    <label>Tipo de Operação</label>
                    <select name="tipo_operacao" class="form-control form-control-sm">
                        <option value="entrada" <?= old('tipo_operacao', $notaFiscal['tipo_operacao'] ?? '') === 'entrada' ? 'selected' : '' ?>>Entrada</option>
                        <option value="saida" <?= old('tipo_operacao', $notaFiscal['tipo_operacao'] ?? '') === 'saida' ? 'selected' : '' ?>>Saída</option>
                    </select>
                </div>

                <!-- <div class="form-group col-sm-6">
                    <label>Natureza da Operação</label>
                    <input type="text" name="natureza_operacao" class="form-control form-control-sm" required
                        value="<?= old('natureza_operacao', $notaFiscal['natureza_operacao'] ?? '') ?>">
                </div> -->
            </div>

            <div class="row row-sm">
                <div class="form-group col-sm-3">
                    <label>Valor Total da Nota</label>
                    <input type="text" name="valor_total" class="form-control form-control-sm" required
                        value="<?= old('valor_total', $notaFiscal['valor_total'] ?? '') ?>">
                </div>

                <!-- <div class="form-group col-sm-3">
                    <label>Base Calc. ICMS</label>
                    <input type="text" name="base_calculo_icms" class="form-control form-control-sm" required
                        value="<?= old('base_calculo_icms', $notaFiscal['base_calculo_icms'] ?? '') ?>">
                </div>

                <div class="form-group col-sm-3">
                    <label>Valor ICMS</label>
                    <input type="text" name="valor_icms" class="form-control form-control-sm" required
                        value="<?= old('valor_icms', $notaFiscal['valor_icms'] ?? '') ?>">
                </div>

                <div class="form-group col-sm-3">
                    <label>Base Calc. IPI</label>
                    <input type="text" name="base_calculo_ipi" class="form-control form-control-sm" required
                        value="<?= old('base_calculo_ipi', $notaFiscal['base_calculo_ipi'] ?? '') ?>">
                </div>

                <div class="form-group col-sm-3">
                    <label>Valor IPI</label>
                    <input type="text" name="valor_ipi" class="form-control form-control-sm" required
                        value="<?= old('valor_ipi', $notaFiscal['valor_ipi'] ?? '') ?>">
                </div>

                <div class="form-group col-sm-3">
                    <label>Outros valores</label>
                    <input type="text" name="outros_valores" class="form-control form-control-sm" required
                        value="<?= old('outros_valores', $notaFiscal['outros_valores'] ?? '') ?>">
                </div> -->
            </div>

            <div class="row row-sm">
                <div class="form-group col-sm-6">
                    <label>Forma de Pagamento</label>
                    <input type="text" name="forma_pagamento" class="form-control form-control-sm" required
                        value="<?= old('forma_pagamento', $notaFiscal['forma_pagamento'] ?? '') ?>">
                </div>
                <div class="form-group col-sm-6">
                    <label>Número do Pedido</label>
                    <input type="text" name="numero_pedido" class="form-control form-control-sm" required
                        value="<?= old('numero_pedido', $notaFiscal['numero_pedido'] ?? '') ?>">
                </div>
            </div>

            <div class="row row-sm">
                <div class="form-group col-sm-6">
                    <label>Observações</label>
                    <input type="text" name="observacoes" class="form-control form-control-sm" required
                        value="<?= old('observacoes', $notaFiscal['observacoes'] ?? '') ?>">
                </div>
                <div class="form-group col-sm-6">
                    <label>Status <?= esc($itens[0]['id'] ?? 'vvv') ?></label>
                    <select name="status" class="form-control">
                        <option value="ativo" <?= old('status', $notaFiscal['status'] ?? '') === 'ativo' ? 'selected' : '' ?>>Ativo</option>
                        <option value="inativo" <?= old('status', $notaFiscal['status'] ?? '') === 'inativo' ? 'selected' : '' ?>>Inativo</option>
                        <option value="consolidada" <?= old('status', $notaFiscal['status'] ?? '') === 'consolidada' ? 'selected' : '' ?>>Consolidada</option>
                    </select>
                </div>
            </div>
            <div class="row">

                <button type="submit" class="btn btn-success mt-3">Salvar</button>
                <a href="<?= base_url('notas-fiscais') ?>" class="btn btn-primary mt-3 ml-2" role="button">Cancelar</a>

            </div>
        </form>
        <div class="card-footer">
            <div class="row">
                <label>Ítens da Nota já lançados</label>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Quantidade</th>
                                    <th>Valor Unitário</th>
                                    <th><strong>Valor Total do Item</strong></th>
                                    <th>Base Calc. ICMS</th>
                                    <th>Valor ICMS</th>
                                    <th>Base Calc. IPI</th>
                                    <th>Valor IPI</th>
                                    <th>Observações</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($itens)): ?>
                                    <?php foreach ($itens as $item): ?>
                                        <tr>
                                            <td><?= esc($item['produto'] ?? $item['produto_id']) ?></td>
                                            <td><?= number_format((int)($item['quantidade'] ?? 0), 0, ',', '.') ?></td>
                                            <td><?= number_format((float)($item['valor_unitario'] ?? 0), 2, ',', '.') ?></td>
                                            <td><?= number_format((float)($item['valor_total_item'] ?? ($item['quantidade'] * $item['valor_unitario'])), 2, ',', '.') ?></td>
                                            <td><?= esc($item['base_calculo_icms']) ?></td>
                                            <td><?= esc($item['valor_icms']) ?></td>
                                            <td><?= esc($item['base_calculo_ipi']) ?></td>
                                            <td><?= esc($item['valor_ipi']) ?></td>
                                            <td><?= esc($item['observacao']) ?></td>
                                            <td><a href="<?= base_url('itens-nota-fiscal/delete/' . $item['id']) ?>" class="btn btn-warning btn-sm">Remover</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10" class="text-center">Nenhum item cadastrado.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php
                $totalItens = 0;
                if (!empty($itens)) {
                    foreach ($itens as $item) {
                        $totalItens += (float)($item['valor_total_item'] ?? 0);
                    }
                }
                ?>
                <label>Valor total dos Itens Cadastrados: R$<?= number_format($totalItens, 2, ',', '.') ?></label>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    document.getElementById('inputGroupFileAddon04').addEventListener('click', function(e) {
        e.preventDefault();

        const fileInput = document.getElementById('inputGroupFile01');
        const formData = new FormData();
        formData.append('xml', fileInput.files[0]);

        fetch('<?= base_url('notas-fiscais/parse-xml') ?>', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                const errorContainer = document.getElementById('error-container');
                if (errorContainer) {
                    errorContainer.innerHTML = ''; // 💨 limpa alerta anterior
                }

                if (data.error) {
                    alert(data.error);
                    document.querySelector('form').reset(); // limpa campos se erro estrutural
                    return;
                }

                if (data.success === false) {
                    errorContainer.innerHTML = `
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <li>${data.message || 'Fornecedor não encontrado. Selecione manualmente.'}</li>
                    </ul>
                </div>
                `;
                    document.querySelector('form').reset(); // limpa os campos antigos
                    return;
                }

                // Preenche os campos com novo XML válido
                document.querySelector('input[name="numero"]').value = data.numero || '';
                document.querySelector('input[name="serie"]').value = data.serie || '';
                document.querySelector('input[name="data_emissao"]').value = data.data_emissao || '';
                document.querySelector('input[name="valor_total"]').value = data.valor_total || '';
                document.querySelector('input[name="chave_acesso"]').value = data.chave_nfe || '';
                document.querySelector('input[name="natureza_operacao"]').value = data.natureza_operacao || '';
                document.querySelector('input[name="base_calculo_icms"]').value = data.base_calculo_icms || '';
                document.querySelector('input[name="valor_icms"]').value = data.valor_icms || '';
                document.querySelector('input[name="base_calculo_ipi"]').value = data.base_calculo_ipi || '';
                document.querySelector('input[name="valor_ipi"]').value = data.valor_ipi || '';
                if (data.fornecedor_id) {
                    document.querySelector('select[name="fornecedor_id"]').value = data.fornecedor_id;
                }
            });
    });
</script>
<?= $this->endSection() ?>