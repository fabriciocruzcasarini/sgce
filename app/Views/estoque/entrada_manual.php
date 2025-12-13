<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Registrar Entrada de Estoque</h3>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"> <?= session()->getFlashdata('success') ?> </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"> <?= session()->getFlashdata('error') ?> </div>
        <?php endif; ?>
        <form action="<?= base_url('estoque/registrarEntrada') ?>" method="post">
            <?= csrf_field() ?>
            <div class="form-group">
                <label>Produto</label>
                <select name="produto_id" class="form-control" required>
                    <option value="">Selecione um produto</option>
                    <?php foreach ($produtos as $p): ?>
                        <option value="<?= esc($p['id']) ?>"> <?= esc($p['nome']) ?> </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Lote do Produto</label>
                <input type="text" name="lote" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Validade do Produto</label>
                <input type="text" name="validade" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Quantidade</label>
                <input type="number" name="quantidade" class="form-control" min="0.0001" step="0.0001" required>
            </div>
            <button type="submit" class="btn btn-success">Registrar Entrada</button>
            <a href="<?= base_url('estoque') ?>" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>