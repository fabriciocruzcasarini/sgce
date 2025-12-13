<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?= esc($title) ?></h3>
        <?php if ($notaFiscal['valor_total'] >= $total): ?>
            <a href="<?= base_url('notas-fiscais/consolidar-nota/' . $notaFiscal['id']) ?>" class="btn btn-primary float-right">Consolidar Nota Fiscal</a>
        <?php else: ?>
            <a href="<?= base_url('notas-fiscais/consolidar-nota/' . $notaFiscal['id']) ?>" class="btn btn-primary float-right disabled">Consolidar Nota Fiscal</a>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <div class="error-container" id="error-container">
            <?php if (session()->getFlashdata('alert')): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('alert') ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php endif; ?>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Dados da Nota Fiscal</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <label>Número da NF - <?= esc($notaFiscal['numero'] ?? '') ?></label>
                </div>
                <div class="row">
                    <label>Fornecedor - <?= esc($notaFiscal['nome_fantasia'] ?? '') ?></label>
                </div>
                <div class="row">
                    <label>Valor Total dos Itens ja cadastrados para esta nota -
                        <?php if ($total > esc($notaFiscal['valor_total'])): ?>
                            <label class="text-danger">
                                <?= number_format((float)($total), 2, ',', '.') ?>
                            </label>
                        <?php else: ?>
                            <label class="text-success">
                                <?= number_format((float)($total), 2, ',', '.') ?>
                            </label>
                        <?php endif; ?>
                    </label>
                </div>
                <div class="row">
                    <label>Valor Total da Nota - <?= number_format((float)($notaFiscal['valor_total'] ?? 0), 2, ',', '.') ?></label>
                </div>
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
    </div>
    <div class="card">
        <div class="card-body">
            <form action="<?= base_url('itens-nota-fiscal/store') ?>" method="post">

                <?= csrf_field() ?>
                <input type="hidden" name="data_entrada" value="<?= esc($notaFiscal['data_entrada'] ?? '') ?>">
                <input type="hidden" name="nota_id" value="<?= esc($notaFiscal['id'] ?? '') ?>">

                <div class="row">
                    <div class="form-group col-md-12">
                        <label>Item</label>
                        <select name="produto_id" id="produtos" class="form-control form-control-sm" required>
                            <option value="">Selecione um Produto</option>
                            <?php foreach ($produtos as $p): ?>
                                <option value="<?= esc($p['id']) ?>" <?= old('produtos', $p['nome'] ?? '') == $p['id'] ? 'selected' : '' ?>>
                                    <?= esc($p['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row row-sm">
                    <div class="form-group col-sm-6">
                        <label>Lote</label>
                        <input type="text" name="lote" class="form-control form-control-sm" required>
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Validade</label>
                        <input type="date" name="validade" class="form-control form-control-sm" required>
                    </div>
                </div>

                <div class="row row-sm">
                    <div class="form-group col-sm-6">
                        <label>Quantidade</label>
                        <input type="text" name="quantidade" class="form-control form-control-sm" required
                            value="<?= old('quantidade', $notaFiscal['quantidade'] ?? '') ?>">
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Valor Unitário</label>
                        <input type="text" name="valor_unitario" class="form-control form-control-sm"
                            value="<?= old('valor_unitario', $notaFiscal['valor_unitario'] ?? '') ?>">
                    </div>
                </div>

                <!-- <div class="row row-sm">
                    <div class="form-group col-sm-2">
                        <label>Base Cal. ICMS</label>
                        <input type="text" name="base_calculo_icms" class="form-control form-control-sm">
                    </div>
                    <div class="form-group col-sm-2">
                        <label>Alíquota ICMS</label>
                        <input type="text" class="form-control form-control-sm" id="aliquota_icms">
                    </div>
                    <div class="form-group col-sm-2">
                        <label>Valor do ICMS</label>
                        <input type="text" name="valor_icms" class="form-control form-control-sm">
                    </div>
                    <div class="form-group col-sm-2">
                        <label>Base Cal. IPI</label>
                        <input type="text" name="base_calculo_ipi" class="form-control form-control-sm">
                    </div>
                    <div class="form-group col-sm-2">
                        <label>Alíquota do IPI</label>
                        <input type="text" class="form-control form-control-sm" id="aliquota_ipi">
                    </div>
                    <div class="form-group col-sm-2">
                        <label>Valor do IPI</label>
                        <input type="text" name="valor_ipi" class="form-control form-control-sm">
                    </div>
                </div> -->

                <div class="row row-sm">
                    <div class="form-group col-sm-6">
                        <label>Observações</label>
                        <input type="text" name="observacao" class="form-control form-control-sm" required
                            value="<?= old('observacao', $notaFiscal['observacoes'] ?? '') ?>">
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="ativo" <?= old('status', $notaFiscal['status'] ?? '') === 'ativo' ? 'selected' : '' ?>>Ativo</option>
                            <option value="inativo" <?= old('status', $notaFiscal['status'] ?? '') === 'inativo' ? 'selected' : '' ?>>Inativo</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <button type="submit" class="btn btn-success mt-3">Salvar</button>
                    <a href="<?= base_url('notas-fiscais') ?>" class="btn btn-primary mt-3 ml-2" role="button">Cancelar</a>
                </div>

            </form>

        </div>
    </div>

    <?= $this->endSection() ?>
    <?= $this->section('scripts') ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const quantidade = document.querySelector('[name="quantidade"]');
            const valorUnitario = document.querySelector('[name="valor_unitario"]');
            const baseICMS = document.querySelector('[name="base_calculo_icms"]');
            const valorICMS = document.querySelector('[name="valor_icms"]');
            const baseIPI = document.querySelector('[name="base_calculo_ipi"]');
            const valorIPI = document.querySelector('[name="valor_ipi"]');

            const aliquotaICMS = document.getElementById('aliquota_icms');
            const aliquotaIPI = document.getElementById('aliquota_ipi');

            function calcularImpostos() {
                // Verifica se todos os campos existem
                if (!quantidade || !valorUnitario || !baseICMS || !valorICMS ||
                    !baseIPI || !valorIPI || !aliquotaICMS || !aliquotaIPI) return;

                const qtde = parseFloat(quantidade.value.replace(',', '.')) || 0;
                const unit = parseFloat(valorUnitario.value.replace(',', '.')) || 0;

                const baseIcms = parseFloat(baseICMS.value.replace(',', '.')) || qtde * unit;
                const baseIpi = parseFloat(baseIPI.value.replace(',', '.')) || qtde * unit;

                const aliqIcms = parseFloat(aliquotaICMS.value.replace(',', '.')) / 100 || 0;
                const aliqIpi = parseFloat(aliquotaIPI.value.replace(',', '.')) / 100 || 0;

                const icms = (baseIcms * aliqIcms) / (1 + aliqIcms);
                const ipi = baseIpi * aliqIpi;

                valorICMS.value = icms.toFixed(2).replace('.', ',');
                valorIPI.value = ipi.toFixed(2).replace('.', ',');
            }

            const campos = [quantidade, valorUnitario, baseICMS, aliquotaICMS, baseIPI, aliquotaIPI];
            campos.forEach(el => {
                if (el) {
                    el.addEventListener('input', calcularImpostos);
                }
            });

            calcularImpostos();
        });
    </script>
    <?= $this->endSection() ?>