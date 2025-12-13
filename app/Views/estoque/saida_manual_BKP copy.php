<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Registrar Saída de Estoque (FIFO)</h3>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"> <?= session()->getFlashdata('success') ?> </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"> <?= session()->getFlashdata('error') ?> </div>
        <?php endif; ?>
        <form method="post" action="<?= base_url('estoque/registrarSaida') ?>">
            <div class="form-group">
                <label for="produto_id">Produto</label>
                <select name="produto_id" id="produto_id" class="form-control" required>
                    <option value="">Selecione</option>
                    <?php foreach ($produtos as $p): ?>
                        <option value="<?= $p['id'] ?>"> <?= esc($p['nome']) ?> </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="cliente_id">Cliente</label>
                <select name="cliente_id" id="cliente_id" class="form-control" required>
                    <option value="">Selecione</option>
                    <?php foreach ($clientes as $c): ?>
                        <option value="<?= $c['id'] ?>"> <?= esc($c['nome']) ?> </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="quantidade">Quantidade a retirar</label>
                <input type="number" step="0.0001" min="0.0001" name="quantidade" id="quantidade" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrar Saída</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>