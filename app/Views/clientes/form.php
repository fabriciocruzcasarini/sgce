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

    <form action="<?= base_url('clientes/store') ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <?php if (!empty($cliente['id'])): ?>
        <input type="hidden" name="id" value="<?= esc($cliente['id']) ?>">
      <?php endif; ?>

      <div class="form-group">
        <label>Nome</label>
        <input type="text" name="nome"
          class="form-control <?= session('errors.nome') ? 'is-invalid' : '' ?>"
          value="<?= old('nome', $cliente['nome'] ?? '') ?>" required>
      </div>

      <div class="form-group">
        <label>E-mail</label>
        <input type="email" name="email"
          class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>"
          value="<?= old('email', $cliente['email'] ?? '') ?>" required>
      </div>

      <div class="form-group">
        <label>Data de Nascimento</label>
        <input type="date" name="data_nascimento"
          class="form-control <?= session('errors.data_nascimento') ? 'is-invalid' : '' ?>"
          value="<?= old('data_nascimento', $cliente['data_nascimento'] ?? '') ?>" required>
      </div>

      <div class="form-group">
        <label>Foto do Cliente</label>
        <input type="file" name="imagem" class="form-control-file">
        <?php if (!empty($cliente['imagem'])): ?>
          <small>Imagem atual:</small><br>
          <img src="<?= base_url('uploads/clientes/' . esc($cliente['imagem'])) ?>" class="img-thumbnail mt-2" style="max-width: 150px;">
        <?php endif ?>
      </div>

      <div class="form-group">
        <label>Status</label>
        <select name="status" class="form-control <?= session('errors.status') ? 'is-invalid' : '' ?>">
          <option value="ativo" <?= old('status', $cliente['status'] ?? '') === 'ativo' ? 'selected' : '' ?>>Ativo</option>
          <option value="inativo" <?= old('status', $cliente['status'] ?? '') === 'inativo' ? 'selected' : '' ?>>Inativo</option>
        </select>
      </div>

      <hr>
      <h5>Telefones</h5>
      <div id="telefone-container">
        <?php if (!empty($telefones)): ?>
          <?php foreach ($telefones as $index => $tel): ?>
            <div class="form-row mb-2 telefone-grupo">
              <div class="col-md-5">
                <input type="text" name="telefones[<?= $index ?>][numero]" class="form-control" value="<?= esc($tel['numero']) ?>" placeholder="(xx) xxxxx-xxxx" required>
              </div>
              <div class="col-md-4">
                <select name="telefones[<?= $index ?>][tipo]" class="form-control">
                  <option value="celular" <?= $tel['tipo'] === 'celular' ? 'selected' : '' ?>>Celular</option>
                  <option value="residencial" <?= $tel['tipo'] === 'residencial' ? 'selected' : '' ?>>Residencial</option>
                  <option value="comercial" <?= $tel['tipo'] === 'comercial' ? 'selected' : '' ?>>Comercial</option>
                </select>
              </div>
              <div class="col-md-3">
                <button type="button" class="btn btn-danger btn-block btn-remover-telefone">Remover</button>
              </div>
            </div>
          <?php endforeach ?>
        <?php endif ?>
      </div>
      <button type="button" class="btn btn-info mt-2" id="btn-adicionar-telefone">Adicionar Telefone</button>

      <hr>
      <h5>Endereços</h5>

      <div id="endereco-container">
        <?php if (!empty($enderecos)): ?>
          <?php foreach ($enderecos as $index => $end): ?>
            <div class="border p-3 mb-3 endereco-grupo">
              <div class="form-row">
                <div class="col-md-8 mb-2">
                  <input type="text" name="enderecos[<?= $index ?>][logradouro]" class="form-control" value="<?= esc($end['logradouro']) ?>" placeholder="Logradouro" required>
                </div>
                <div class="col-md-4 mb-2">
                  <input type="text" name="enderecos[<?= $index ?>][numero]" class="form-control" value="<?= esc($end['numero']) ?>" placeholder="Número" required>
                </div>
              </div>

              <div class="form-row">
                <div class="col-md-6 mb-2">
                  <input type="text" name="enderecos[<?= $index ?>][complemento]" class="form-control" value="<?= esc($end['complemento']) ?>" placeholder="Complemento">
                </div>
                <div class="col-md-6 mb-2">
                  <input type="text" name="enderecos[<?= $index ?>][bairro]" class="form-control" value="<?= esc($end['bairro']) ?>" placeholder="Bairro" required>
                </div>
              </div>

              <div class="form-row">
                <div class="col-md-5 mb-2">
                  <input type="text" name="enderecos[<?= $index ?>][cidade]" class="form-control" value="<?= esc($end['cidade']) ?>" placeholder="Cidade" required>
                </div>
                <div class="col-md-3 mb-2">
                  <input type="text" name="enderecos[<?= $index ?>][estado]" class="form-control" value="<?= esc($end['estado']) ?>" placeholder="UF" maxlength="2" required>
                </div>
                <div class="col-md-4 mb-2">
                  <input type="text" name="enderecos[<?= $index ?>][cep]" class="form-control" value="<?= esc($end['cep']) ?>" placeholder="CEP" required>
                </div>
              </div>

              <button type="button" class="btn btn-danger btn-sm btn-remover-endereco">Remover</button>
            </div>
          <?php endforeach ?>
        <?php endif ?>
      </div>

      <button type="button" class="btn btn-info mt-2" id="btn-adicionar-endereco">Adicionar Endereço</button>


      <hr>
      <button type="submit" class="btn btn-success mt-3">Salvar</button>
      <a href="<?= base_url('clientes') ?>" class="btn btn-secondary mt-3">Cancelar</a>
    </form>

  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  let contadorTelefone = <?= isset($telefones) ? count($telefones) : 0 ?>;

  document.getElementById('btn-adicionar-telefone').addEventListener('click', function() {
    const container = document.getElementById('telefone-container');

    const grupo = document.createElement('div');
    grupo.classList.add('form-row', 'mb-2', 'telefone-grupo');

    grupo.innerHTML = `
      <div class="col-md-5">
        <input type="text" name="telefones[${contadorTelefone}][numero]" class="form-control" placeholder="(xx) xxxxx-xxxx" required>
      </div>
      <div class="col-md-4">
        <select name="telefones[${contadorTelefone}][tipo]" class="form-control">
          <option value="celular">Celular</option>
          <option value="residencial">Residencial</option>
          <option value="comercial">Comercial</option>
        </select>
      </div>
      <div class="col-md-3">
        <button type="button" class="btn btn-danger btn-block btn-remover-telefone">Remover</button>
      </div>
    `;

    container.appendChild(grupo);
    contadorTelefone++;
  });

  // Remover telefone
  document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('btn-remover-telefone')) {
      e.target.closest('.telefone-grupo').remove();
    }
  });
</script>
<script>
  let contadorEndereco = <?= isset($enderecos) ? count($enderecos) : 0 ?>;

  document.getElementById('btn-adicionar-endereco').addEventListener('click', function() {
    const container = document.getElementById('endereco-container');

    const grupo = document.createElement('div');
    grupo.classList.add('border', 'p-3', 'mb-3', 'endereco-grupo');

    grupo.innerHTML = `
      <div class="form-row">
        <div class="col-md-8 mb-2">
          <input type="text" name="enderecos[${contadorEndereco}][logradouro]" class="form-control" placeholder="Logradouro" required>
        </div>
        <div class="col-md-4 mb-2">
          <input type="text" name="enderecos[${contadorEndereco}][numero]" class="form-control" placeholder="Número" required>
        </div>
      </div>
      <div class="form-row">
        <div class="col-md-6 mb-2">
          <input type="text" name="enderecos[${contadorEndereco}][complemento]" class="form-control" placeholder="Complemento">
        </div>
        <div class="col-md-6 mb-2">
          <input type="text" name="enderecos[${contadorEndereco}][bairro]" class="form-control" placeholder="Bairro" required>
        </div>
      </div>
      <div class="form-row">
        <div class="col-md-5 mb-2">
          <input type="text" name="enderecos[${contadorEndereco}][cidade]" class="form-control" placeholder="Cidade" required>
        </div>
        <div class="col-md-3 mb-2">
          <input type="text" name="enderecos[${contadorEndereco}][estado]" class="form-control" placeholder="UF" maxlength="2" required>
        </div>
        <div class="col-md-4 mb-2">
          <input type="text" name="enderecos[${contadorEndereco}][cep]" class="form-control" placeholder="CEP" required>
        </div>
      </div>
      <button type="button" class="btn btn-danger btn-sm btn-remover-endereco">Remover</button>
    `;

    container.appendChild(grupo);
    contadorEndereco++;
  });

  document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('btn-remover-endereco')) {
      e.target.closest('.endereco-grupo').remove();
    }
  });
</script>

<?= $this->endSection() ?>