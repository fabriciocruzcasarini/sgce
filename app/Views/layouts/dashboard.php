<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
  <div class="row">

    <!-- Total de Produtos -->
    <div class="col-lg-3 col-6">
      <div class="small-box bg-info">
        <div class="inner">
          <h3><?= $totalProdutos ?? '0' ?></h3>
          <p>Produtos Cadastrados</p>
        </div>
        <div class="icon">
          <i class="fas fa-box"></i>
        </div>
        <a href="<?= base_url('produtos') ?>" class="small-box-footer">Ver todos <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>

    <!-- Entradas de Estoque -->
    <div class="col-lg-3 col-6">
      <div class="small-box bg-success">
        <div class="inner">
          <h3><?= $entradasHoje ?? '0' ?></h3>
          <p>Entradas Hoje</p>
        </div>
        <div class="icon">
          <i class="fas fa-arrow-circle-down"></i>
        </div>
        <a href="<?= base_url('estoque/entradas') ?>" class="small-box-footer">Detalhes <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>

    <!-- Saídas de Estoque -->
    <div class="col-lg-3 col-6">
      <div class="small-box bg-danger">
        <div class="inner">
          <h3><?= $saidasHoje ?? '0' ?></h3>
          <p>Saídas Hoje</p>
        </div>
        <div class="icon">
          <i class="fas fa-arrow-circle-up"></i>
        </div>
        <a href="<?= base_url('estoque/saidas') ?>" class="small-box-footer">Detalhes <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>

    <!-- Estoque em Baixo Nível -->
    <div class="col-lg-3 col-6">
      <div class="small-box bg-warning">
        <div class="inner">
          <h3><?= $estoqueCritico ?? '0' ?></h3>
          <p>Produtos em Baixo Estoque</p>
        </div>
        <div class="icon">
          <i class="fas fa-exclamation-triangle"></i>
        </div>
        <a href="<?= base_url('produtos/baixo-estoque') ?>" class="small-box-footer">Ver produtos <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>

  </div>
</div>
<?= $this->endSection() ?>