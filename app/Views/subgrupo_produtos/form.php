<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
  <div class="card-header">
    <h3 class="card-title"><?= esc($title) ?></h3>
  </div>
  <div class="card-body">

    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
    <?php endif; ?>

    <?php if ($errors = session()->getFlashdata('errors')): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach ($errors as $e): ?>
            <li><?= esc($e) ?></li>
          <?php endforeach ?>
        </ul>
      </div>
    <?php endif ?>

    <form action="<?= base_url('subgrupo-produtos/store') ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <?php if (!empty($subgrupo['id'])): ?>
        <input type="hidden" name="id" value="<?= esc($subgrupo['id']) ?>">
      <?php endif; ?>

      <div class="form-group">
        <label>Nome</label>
        <input type="text" name="nome"
          class="form-control <?= session('errors.nome') ? 'is-invalid' : '' ?>"
          value="<?= old('nome', $subgrupo['nome'] ?? '') ?>" required>
      </div>

      <div class="form-group">
        <label>Descrição</label>
        <input type="text" name="descricao"
          class="form-control <?= session('errors.descricao') ? 'is-invalid' : '' ?>"
          value="<?= old('descricao', $subgrupo['descricao'] ?? '') ?>" required>
      </div>

      <div class="form-group">
        <label>Grupo</label>
        <select name="grupo_produtos_id" class="form-control <?= session('errors.id') ? 'is-invalid' : '' ?>" required>
          <option value="">Selecione um grupo</option>
          <?php foreach ($grupos as $grupo): ?>
            <option value="<?= esc($grupo['id']) ?>" <?= old('grupo_produtos_id', $subgrupo['grupo_produtos_id'] ?? '') == $grupo['id'] ? 'selected' : '' ?>>
              <?= esc($grupo['nome']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>


      <div class="form-group">
        <label>Status</label>
        <select name="status" class="form-control <?= session('errors.status') ? 'is-invalid' : '' ?>">
          <option value="ativo" <?= old('status', $subgrupo['status'] ?? '') === 'ativo' ? 'selected' : '' ?>>Ativo</option>
          <option value="inativo" <?= old('status', $subgrupo['status'] ?? '') === 'inativo' ? 'selected' : '' ?>>Inativo</option>
        </select>
      </div>

      <hr>

      <button type="submit" class="btn btn-success mt-3">Salvar</button>
      <a href="<?= base_url('clientes') ?>" class="btn btn-secondary mt-3">Cancelar</a>
    </form>

  </div>
</div>
<?= $this->endSection() ?>