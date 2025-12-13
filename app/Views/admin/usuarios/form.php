<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container">
    <h1><?= esc($title ?? 'Usuário') ?></h1>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <?php foreach (session()->getFlashdata('errors') as $err) echo '<p>' . esc($err) . '</p>'; ?>
        </div>
    <?php endif; ?>
    <form method="post" action="<?= isset($usuario) ? base_url('admin/usuarios/update/' . $usuario['id'] ?? $usuario->id) : base_url('admin/usuarios/store') ?>">
        <?= csrf_field() ?>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= old('email', isset($usuario) ? ($usuario['email'] ?? $usuario->email) : '') ?>" required>
        </div>
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="<?= old('username', isset($usuario) ? ($usuario['username'] ?? $usuario->username) : '') ?>" required>
        </div>
        <div class="form-group">
            <label>Senha <?= isset($usuario) ? '(deixe em branco para manter)' : '' ?></label>
            <input type="password" name="password" class="form-control" <?= isset($usuario) ? '' : 'required' ?>>
        </div>

        <div class="form-group">
            <label>Grupos</label>
            <div>
                <?php foreach ($grupos as $g):
                    $gid = $g['id'] ?? $g->id;
                    $checked = '';
                    if (!empty($usuario_groups ?? [])) {
                        foreach ($usuario_groups as $ug) if (($ug['group_id'] ?? $ug->group_id) == $gid) {
                            $checked = 'checked';
                            break;
                        }
                    }
                ?>
                    <label class="mr-2"><input type="checkbox" name="groups[]" value="<?= $gid ?>" <?= old('groups') && in_array($gid, (array)old('groups')) ? 'checked' : $checked ?>> <?= esc($g['name'] ?? $g->name) ?></label>
                <?php endforeach; ?>
            </div>
        </div>

        <button class="btn btn-success"><?= isset($usuario) ? 'Atualizar' : 'Criar' ?></button>
        <a href="<?= base_url('admin/usuarios') ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<?= $this->endSection() ?>