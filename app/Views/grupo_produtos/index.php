<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
  <div class="card-header">
    <h3 class="card-title"><?= esc($title) ?></h3>
    <a href="<?= base_url('grupo-produtos/create') ?>" class="btn btn-primary float-right">Novo Grupo</a>
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
          <th>Descricao</th>
          <th>Status</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($grupos as $g): ?>
          <tr>
            <td><?= esc($g['id']) ?></td>
            <td><?= esc($g['nome']) ?></td>
            <td><?= esc($g['descricao']) ?></td>
            <td>
              <span class="badge badge-<?= $g['status'] == 'ativo' ? 'success' : 'secondary' ?>">
                <?= esc($g['status']) ?>
              </span>
            </td>
            <td>
              <a href="<?= base_url('grupo-produtos/edit/' . $g['id']) ?>" class="btn btn-sm btn-warning">Editar</a>
              <?php if ($g['status'] == 'ativo'): ?>
                <a href="<?= base_url('grupo-produtos/desativar/' . $g['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja desativar este grupo ?')">Excluir</a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>
<?= $this->endSection() ?>