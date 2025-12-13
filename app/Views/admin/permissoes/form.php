<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container">
    <h1><?= esc($title ?? 'Permissão') ?></h1>
    <form method="post" action="<?= isset($permissao) ? base_url('admin/permissoes/update/' . $permissao['id']) : base_url('admin/permissoes/store') ?>">
        <?= csrf_field() ?>
        <div class="form-group">
            <label>Nome</label>
            <input type="text" name="name" class="form-control" value="<?= old('name', $permissao['name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Descrição</label>
            <input type="text" name="description" class="form-control" value="<?= old('description', $permissao['description'] ?? '') ?>">
        </div>
        <button class="btn btn-success"><?= isset($permissao) ? 'Atualizar' : 'Criar' ?></button>
        <a href="<?= base_url('admin/permissoes') ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<?= $this->endSection() ?>