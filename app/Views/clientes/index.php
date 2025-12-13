<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Lista de Clientes</h3>
    <a href="<?= base_url('clientes/create') ?>" class="btn btn-primary float-right">Novo Cliente</a>
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
          <th>Email</th>
          <th>Status</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($clientes as $c): ?>
          <tr>
            <td><?= esc($c['id']) ?></td>
            <td><?= esc($c['nome']) ?></td>
            <td><?= esc($c['email']) ?></td>
            <td>
              <span class="badge badge-<?= $c['status'] == 'ativo' ? 'success' : 'secondary' ?>">
                <?= esc($c['status']) ?>
              </span>
            </td>
            <td>
              <a href="<?= base_url('clientes/perfil/' . $c['id']) ?>" class="btn btn-sm btn-info">Perfil</a>
              <a href="<?= base_url('clientes/edit/' . $c['id']) ?>" class="btn btn-sm btn-warning">Editar</a>

              <?php if ($c['status'] == 'ativo'): ?>
                <a href="<?= base_url('clientes/desativar/' . $c['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja desativar este cliente?')">Excluir</a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>
<?= $this->endSection() ?>