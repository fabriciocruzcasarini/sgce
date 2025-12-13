<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><?= esc($title) ?></h3>
                    </div>
                    <form action="<?= base_url('estoque/registrarSaidaCliente') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="cliente_id">Cliente</label>
                                <select name="cliente_id" id="cliente_id" class="form-control" required>
                                    <option value="">Selecione um cliente</option>
                                    <?php foreach ($clientes as $cliente): ?>
                                        <option value="<?= esc($cliente['id']) ?>"><?= esc($cliente['nome']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="produto_id">Produto</label>
                                <select name="produto_id" id="produto_id" class="form-control" required>
                                    <option value="">Selecione um produto</option>
                                    <?php foreach ($lotes_disponiveis as $lote): ?>
                                        <option value="<?= esc($lote['produto_id']) ?>"
                                            data-lote="<?= esc($lote['lote']) ?>"
                                            data-validade="<?= esc($lote['validade']) ?>"
                                            data-saldo="<?= esc($lote['saldo_lote']) ?>">
                                            <?= esc($lote['nome_produto']) ?> | Lote: <?= esc($lote['lote']) ?> | Validade: <?= esc($lote['validade']) ?> | Saldo: <?= esc($lote['saldo_lote']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="quantidade">Quantidade</label>
                                <input type="number" name="quantidade" id="quantidade" class="form-control" step="0.01" min="0" required>
                            </div>
                            <div class="form-group">
                                <label for="observacao">Observação</label>
                                <textarea name="observacao" id="observacao" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Registrar Saída</button>
                            <a href="<?= base_url('estoque') ?>" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>