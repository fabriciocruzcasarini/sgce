<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container">
    <h1><?= esc($title ?? 'Usuários') ?></h1>
    <a href="<?= base_url('admin/usuarios/create') ?>" class="btn btn-primary mb-2">Novo</a>
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Username</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= esc($u->id ?? $u['id']) ?></td>
                    <td><?= esc($u->email ?? ($u['email'] ?? '')) ?></td>
                    <td><?= esc($u->username ?? ($u['username'] ?? '')) ?></td>
                    <td>
                        <a href="<?= base_url('admin/usuarios/edit/' . ($u->id ?? $u['id'])) ?>" class="btn btn-sm btn-warning">Editar</a>
                        <form action="<?= base_url('admin/usuarios/delete/' . ($u->id ?? $u['id'])) ?>" method="post" class="d-inline">
                            <?= csrf_field() ?>
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Excluir?')">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>