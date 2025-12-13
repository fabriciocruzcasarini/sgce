<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <h1>Gerenciamento de Usuários</h1>
    <a href="<?= base_url('usuarios/create') ?>" class="btn btn-primary mb-3">Novo Usuário</a>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Username</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= esc($usuario->id) ?></td>
                    <td><?= esc($usuario->email) ?></td>
                    <td><?= esc($usuario->username) ?></td>
                    <td>
                        <a href="<?= base_url('usuarios/edit/' . $usuario->id) ?>" class="btn btn-warning btn-sm">Editar</a>
                        <form action="<?= base_url('usuarios/delete/' . $usuario->id) ?>" method="post" class="d-inline">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>