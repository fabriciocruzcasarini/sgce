<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container">
    <h1><?= esc($title ?? 'Grupos') ?></h1>
    <a href="<?= base_url('admin/grupos/create') ?>" class="btn btn-primary mb-2">Novo Grupo</a>
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
            <?php foreach ($grupos as $g): ?>
                <tr>
                    <td><?= esc($g['id'] ?? $g->id) ?></td>
                    <td><?= esc($g['name'] ?? $g['name']) ?></td>
                    <td><?= esc($g['description'] ?? $g['description']) ?></td>
                    <td>
                        <a href="<?= base_url('admin/grupos/edit/' . ($g['id'] ?? $g->id)) ?>" class="btn btn-sm btn-warning">Editar</a>
                        <form action="<?= base_url('admin/grupos/delete/' . ($g['id'] ?? $g->id)) ?>" method="post" class="d-inline"><?= csrf_field() ?><button class="btn btn-sm btn-danger" onclick="return confirm('Excluir?')">Excluir</button></form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>