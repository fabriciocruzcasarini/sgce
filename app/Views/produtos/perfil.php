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
                src="<?= isset($produtos['imagem']) && $produtos['imagem'] ? base_url('uploads/produtos/' . $produtos['imagem']) : base_url('assets/img/avatar-default.png') ?>"
                alt="Foto do Produto">
            </div>
            <h3 class="profile-username text-center"><?= esc($produtos['nome'] ?? '') ?></h3>
            <p class="text-muted text-center"><?= esc($produtos['descricao_curta'] ?? '') ?></p>

            <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item">
                <b>Fornecedor</b>
                <span class="float-right"><?= esc($produtos['nome_fornecedor'] ?? '-') ?></span>
              </li>
              <li class="list-group-item">
                <b>Grupo / Subgrupo</b>
                <span class="float-right"><?= esc(($produtos['nome_grupo'] ?? '-') . ' / ' . ($produtos['nome_subgrupo'] ?? '-')) ?></span>
              </li>
              <li class="list-group-item">
                <b>Código / SKU</b>
                <span class="float-right"><?= esc($produtos['sku'] ?? '-') ?></span>
              </li>
              <li class="list-group-item">
                <b>Preço</b>
                <span class="float-right"><?= isset($produtos['preco']) ? 'R$ ' . number_format($produtos['preco'], 2, ',', '.') : '-' ?></span>
              </li>
              <li class="list-group-item">
                <b>Status</b>
                <span class="float-right badge badge-<?= (isset($produtos['status']) && $produtos['status'] === 'ativo') ? 'success' : 'secondary' ?>">
                  <?= esc($produtos['status'] ?? '-') ?>
                </span>
              </li>
            </ul>

            <a href="<?= base_url('produtos/edit/' . ($produtos['id'] ?? '')) ?>" class="btn btn-primary btn-block"><b>Editar Produto</b></a>
          </div>
        </div>
      </div>

      <!-- Conteúdo Principal -->
      <div class="col-md-9">
        <div class="card">
          <div class="card-header p-2">
            <ul class="nav nav-pills">
              <li class="nav-item"><a class="nav-link active" href="#estoque" data-toggle="tab">Estoque</a></li>
              <li class="nav-item"><a class="nav-link" href="#notas" data-toggle="tab">Notas Fiscais</a></li>
              <li class="nav-item"><a class="nav-link" href="#pedidos" data-toggle="tab">Saída</a></li>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content">

              <!-- Estoque -->
              <div class="active tab-pane" id="estoque">
                <ul class="list-group">
                  <?php if (!empty($produtos['estoque_por_lote'])): ?>
                    <?php foreach ($produtos['estoque_por_lote'] as $lote): ?>
                      <li class="list-group-item">
                        <b>Lote:</b> <?= esc($lote['lote']) ?> |
                        <b>Validade:</b> <?= esc($lote['validade']) ?> |
                        <b>Saldo:</b> <?= (int)($lote['total_entrada'] - $lote['total_saida']) ?>
                      </li>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <li class="list-group-item text-muted">Nenhum saldo disponível por lote.</li>
                  <?php endif; ?>
                </ul>
              </div>

              <!-- Notas Fiscais -->
              <div class="tab-pane" id="notas">
                <ul class="list-group">
                  <?php if (!empty($produtos['notas_fiscais'])): ?>
                    <?php foreach ($produtos['notas_fiscais'] as $nota): ?>
                      <li class="list-group-item">
                        <b>Nota:</b> <?= esc($nota['numero']) ?> |
                        <b>Fornecedor:</b> <?= esc($nota['fornecedor']) ?> |
                        <b>Data:</b> <?= date('d/m/Y', strtotime($nota['data_emissao'])) ?> |
                        <b>Quantidade:</b> <?= esc($nota['quantidade']) ?> |
                        <b>Valor Unitário:</b> R$ <?= number_format($nota['valor_unitario'], 2, ',', '.') ?>
                      </li>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <li class="list-group-item text-muted">Nenhuma nota fiscal vinculada ao produto.</li>
                  <?php endif; ?>
                </ul>
              </div>

              <!-- Saída / Pedidos -->
              <div class="tab-pane" id="pedidos">
                <div class="border rounded p-3 mb-3">
                  <strong>Últimas saídas</strong><br>
                  <small class="text-muted">Nenhum registro de saída disponível.</small>
                </div>
                <p class="text-muted">Módulo de Pedidos não disponível</p>
              </div>

            </div>
          </div>
        </div>

      </div>
    </div>
</section>
<?= $this->endSection() ?>