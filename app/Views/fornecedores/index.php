<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Lista de Fornecedores</h3>
    <a href="<?= base_url('fornecedores/create') ?>" class="btn btn-primary float-right">Novo Fornecedor</a>
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
          <th>CNPJ</th>
          <th>Status</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($fornecedores as $f): ?>
          <tr>
            <td><?= esc($f['id']) ?></td>
            <td><?= esc($f['nome_fantasia']) ?></td>
            <td><?= esc($f['cnpj']) ?></td>
            <td>
              <span class="badge badge-<?= $f['status'] == '1' ? 'success' : 'secondary' ?>">
                Ativo
              </span>
            </td>
            <td>
              <a href="<?= base_url('fornecedores/perfil/' . $f['id']) ?>" class="btn btn-sm btn-info">Perfil</a>
              <a href="<?= base_url('fornecedores/edit/' . $f['id']) ?>" class="btn btn-sm btn-warning">Editar</a>

              <?php if ($f['status'] == 'ativo'): ?>
                <a href="<?= base_url('fornecedores/desativar/' . $f['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja desativar este fornecedor?')">Excluir</a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>
<?= $this->endSection() ?>