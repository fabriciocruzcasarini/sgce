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
                src="<?= $produtos['imagem'] ? base_url('uploads/produtos/' . $produtos['imagem']) : base_url('assets/img/avatar-default.png') ?>"
                alt="Foto do Produto">
            </div>
            <h3 class="profile-username text-center"><?= esc($produtos['nome']) ?></h3>
            <p class="text-muted text-center"></p>
            <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item">
                <b>Data de nascimento</b> <span class="float-right"></span>
              </li>
              <li class="list-group-item">
                <b>Status</b>
                <span class="float-right badge badge-<?= $produtos['status'] === 'ativo' ? 'success' : 'secondary' ?>">
                  <?= esc($produtos['status']) ?>
                </span>
              </li>
            </ul>
            <a href="<?= base_url('produtos/edit/' . $produtos['id']) ?>" class="btn btn-primary btn-block"><b>Editar Produto</b></a>
          </div>
        </div>
      </div>

      <!-- Conteúdo Principal -->
      <div class="col-md-9">
        <div class="card">
          <div class="card-header p-2">
            <ul class="nav nav-pills">
              <li class="nav-item"><a class="nav-link active" href="#telefones" data-toggle="tab">Estoque</a></li>
              <li class="nav-item"><a class="nav-link" href="#enderecos" data-toggle="tab">Notas Fiscais</a></li>
              <li class="nav-item"><a class="nav-link" href="#pedidos" data-toggle="tab">Saída</a></li>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content">

              <!-- Telefones -->
              <div class="active tab-pane" id="telefones">
                <ul class="list-group">
                  <li class="list-group-item text-muted">Nenhum telefone cadastrado.</li>
                </ul>
              </div>

              <!-- Endereços -->
              <div class="tab-pane" id="enderecos">
                <div class="border rounded p-3 mb-3">

                  <p class="text-muted">Nenhum endereço cadastrado.</p>

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