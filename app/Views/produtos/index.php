<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?= esc($title) ?></h3>
        <a href="<?= base_url('produtos/create') ?>" class="btn btn-primary float-right">Novo Produto</a>
    </div>
    <div class="card-body responsive">
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
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Fornecedor</th>
                    <th>SKU</th>
                    <th>Grupo</th>
                    <th>Subgrupo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $p): ?>
                    <tr>
                        <td><?= esc($p['id']) ?></td>
                        <td><?= esc($p['nome']) ?></td>
                        <td><?= esc($p['nome_fornecedor']) ?></td>
                        <td><?= esc($p['sku']) ?></td>
                        <td><?= esc($p['nome_grupo']) ?></td>
                        <td><?= esc($p['nome_subgrupo']) ?></td>
                        <td>
                            <a href="<?= base_url('produtos/perfil/' . $p['id']) ?>" class="btn btn-sm btn-info">Perfil</a>
                            <a href="<?= base_url('produtos/edit/' . $p['id']) ?>" class="btn btn-sm btn-warning">Editar</a>
                            <?php if ($p['status'] === 'ativo'): ?>
                                <a href="<?= base_url('produtos/desativar/' . $p['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Deseja desativar este produto?')">Desativar</a>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>