<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="content">
  <div class="container-fluid">
    <div class="row">

      <!-- Painel Lateral -->
      <div class="col-md-3">
        <div class="card card-primary card-outline">
          <div class="card-body box-profile">
            <div class="text-center">
              <img class="profile-user-img img-fluid img-circle"
                src="<?= $fornecedor['imagem'] ? base_url('uploads/fornecedores/' . $fornecedor['imagem']) : base_url('assets/img/avatar-default.png') ?>"
                alt="Foto do Fornecedor">
            </div>
            <h3 class="profile-username text-center"><?= esc($fornecedor['nome_fantasia']) ?></h3>
            <p class="text-muted text-center">CNPJ - <?= esc($fornecedor['cnpj']) ?></p>
            <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item">
                <b>Status</b>
                <span class="float-right badge badge-<?= $fornecedor['status'] === 'ativo' ? 'success' : 'secondary' ?>">
                  <?= esc($fornecedor['status']) ?>
                </span>
              </li>
            </ul>
            <a href="<?= base_url('fornecedores/edit/' . $fornecedor['id']) ?>" class="btn btn-primary btn-block"><b>Editar Fornecedor</b></a>
          </div>
        </div>
      </div>

      <!-- Conteúdo Principal -->
      <div class="col-md-9">
        <div class="card">
          <div class="card-header p-2">
            <ul class="nav nav-pills">
              <li class="nav-item"><a class="nav-link active" href="#telefones" data-toggle="tab">Telefones</a></li>
              <li class="nav-item"><a class="nav-link" href="#enderecos" data-toggle="tab">Endereços</a></li>
              <li class="nav-item"><a class="nav-link" href="#pedidos" data-toggle="tab">Pedidos</a></li>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content">

              <!-- Telefones -->
              <div class="active tab-pane" id="telefones">
                <ul class="list-group">
                  <?php foreach ($telefones as $tel): ?>
                    <li class="list-group-item">
                      <strong><?= ucfirst($tel['tipo']) ?>:</strong> <?= esc($tel['numero']) ?>
                    </li>
                  <?php endforeach ?>
                  <?php if (empty($telefones)): ?>
                    <li class="list-group-item text-muted">Nenhum telefone cadastrado.</li>
                  <?php endif ?>
                </ul>
              </div>

              <!-- Endereços -->
              <div class="tab-pane" id="enderecos">
                <?php foreach ($enderecos as $e): ?>
                  <div class="border rounded p-3 mb-3">
                    <strong><?= esc($e['logradouro']) ?>, <?= esc($e['numero']) ?></strong><br>
                    <?= $e['complemento'] ? esc($e['complemento']) . '<br>' : '' ?>
                    <?= esc($e['bairro']) ?> – <?= esc($e['cidade']) ?>/<?= esc($e['estado']) ?><br>
                    <small class="text-muted">CEP: <?= esc($e['cep']) ?></small>
                  </div>
                <?php endforeach ?>
                <?php if (empty($enderecos)): ?>
                  <p class="text-muted">Nenhum endereço cadastrado.</p>
                <?php endif ?>
              </div>

              <!-- Pedidos -->
              <div class="tab-pane" id="pedidos">
                  <div class="border rounded p-3 mb-3">
                    <strong></strong><br>
                    <small class="text-muted"></small>
                  </div>
                  <p class="text-muted">Módulo de Pedidos não disponível</p>
              </div>

            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
<?= $this->endSection() ?>