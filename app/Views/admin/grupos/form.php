<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container">
    <h1><?= esc($title ?? 'Grupo') ?></h1>
    <form method="post" action="<?= isset($grupo) ? base_url('admin/grupos/update/' . $grupo['id']) : base_url('admin/grupos/store') ?>">
        <?= csrf_field() ?>
        <div class="form-group">
            <label>Nome</label>
            <input type="text" name="name" class="form-control" value="<?= old('name', $grupo['name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Descrição</label>
            <input type="text" name="description" class="form-control" value="<?= old('description', $grupo['description'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Permissões</label>
            <div>
                <?php foreach ($permissoes as $p):
                    $pid = $p['id'] ?? $p->id;
                    $checked = '';
                    if (!empty($grupo_perms ?? [])) {
                        foreach ($grupo_perms as $gp) if (($gp['permission_id'] ?? $gp->permission_id) == $pid) {
                            $checked = 'checked';
                            break;
                        }
                    }
                ?>
                    <label class="mr-2"><input type="checkbox" name="permissions[]" value="<?= $pid ?>" <?= $checked ?>> <?= esc($p['name'] ?? $p->name) ?></label>
                <?php endforeach; ?>
            </div>
        </div>

        <button class="btn btn-success"><?= isset($grupo) ? 'Atualizar' : 'Criar' ?></button>
        <a href="<?= base_url('admin/grupos') ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<?= $this->endSection() ?>