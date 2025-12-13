<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container">
    <h1><?= esc($title ?? 'Permissões') ?></h1>
    <a href="<?= base_url('admin/permissoes/create') ?>" class="btn btn-primary mb-2">Nova</a>
    <table class="table table-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($permissoes as $p): ?>
                <tr>
                    <td><?= esc($p['id'] ?? $p->id) ?></td>
                    <td><?= esc($p['name'] ?? $p->name) ?></td>
                    <td><?= esc($p['description'] ?? $p->description) ?></td>
                    <td>
                        <a href="<?= base_url('admin/permissoes/edit/' . ($p['id'] ?? $p->id)) ?>" class="btn btn-sm btn-warning">Editar</a>
                        <form action="<?= base_url('admin/permissoes/delete/' . ($p['id'] ?? $p->id)) ?>" method="post" class="d-inline"><?= csrf_field() ?><button class="btn btn-sm btn-danger" onclick="return confirm('Excluir?')">Excluir</button></form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>