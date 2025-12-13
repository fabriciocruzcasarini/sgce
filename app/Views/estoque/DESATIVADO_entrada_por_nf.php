<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Registrar Entrada de Estoque - Por Nota Fiscal</h3>
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
            <input type="hidden" name="origem" value="entrada_por_nf">
            <div class="form-group">
                <label>Nota Fiscal</label>
                <select name="origem_id" class="form-control" required>
                    <option value="">Selecione uma Nota Fiscal</option>
                    <?php foreach ($notasFiscais as $nf): ?>
                        <option value="<?= esc($nf['id']) ?>"> <?= esc($nf['nome_fantasia']) ?> <?= esc($nf['numero']) ?> </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Produto</label>
                <select name="produto_id" class="form-control" required <?= empty($produtos) ? 'disabled' : '' ?>>
                    <?php if (!empty($produtos)): ?>
                        <option value="">Selecione um produto</option>
                        <?php foreach ($produtos as $p): ?>
                            <option value="<?= esc($p['id']) ?>"> <?= esc($p['nome']) ?> </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="" disabled selected>Não há mais itens para dar entrada para esta nota fiscal.</option>
                    <?php endif; ?>
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

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const origemSelect = document.querySelector('select[name="origem_id"]');
        const produtoSelect = document.querySelector('select[name="produto_id"]');

        origemSelect.addEventListener('change', function() {
            const notaId = this.value;
            produtoSelect.innerHTML = '<option value="">Carregando...</option>';
            if (!notaId) {
                produtoSelect.innerHTML = '<option value="">Selecione um produto</option>';
                return;
            }
            fetch('<?= base_url('estoque/produtosPorNota/') ?>' + notaId)
                .then(response => response.json())
                .then(data => {
                    let options = '<option value="">Selecione um produto</option>';
                    data.forEach(function(item) {
                        options += `<option value="${item.produto_id}">${item.nome}</option>`;
                    });
                    produtoSelect.innerHTML = options;
                })
                .catch(() => {
                    produtoSelect.innerHTML = '<option value="">Erro ao carregar produtos</option>';
                });
        });
    });
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>