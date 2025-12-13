<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Filtros de Estoque</h3>
  </div>
  <div class="card-body">
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

        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3><?= $saldoPositivo ?? '0' ?></h3>
              <p>Produtos com Estoque Positivo</p>
            </div>
            <div class="icon">
              <i class="fas fa-exclamation-triangle"></i>
            </div>
            <a href="<?= base_url('produtos/baixo-estoque') ?>" class="small-box-footer">Ver produtos <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <!-- Entradas de Estoque -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3><?= $validadeProxima ?? '0' ?></h3>
              <p>Validade Próxima</p>
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
              <h3><?= $validadeVencida ?? '0' ?></h3>
              <p>Validade Vencida</p>
            </div>
            <div class="icon">
              <i class="fas fa-arrow-circle-up"></i>
            </div>
            <a href="<?= base_url('estoque/saidas') ?>" class="small-box-footer">Detalhes <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <!-- Estoque em Baixo Nível -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3><?= $saldoZerado ?? '0' ?></h3>
              <p>Produtos com Estoque Zerado</p>
            </div>
            <div class="icon">
              <i class="fas fa-exclamation-triangle"></i>
            </div>
            <a href="<?= base_url('produtos/baixo-estoque') ?>" class="small-box-footer">Ver produtos <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3><?= $saldoNegativo ?? '0' ?></h3>
              <p>Produtos com Estoque Negativo</p>
            </div>
            <div class="icon">
              <i class="fas fa-exclamation-triangle"></i>
            </div>
            <a href="<?= base_url('produtos/baixo-estoque') ?>" class="small-box-footer">Ver produtos <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3><?= $itensComSaidaParaCliente ?? '0' ?></h3>
              <p>Quantidade de Vendas para Clientes</p>
            </div>
            <div class="icon">
              <i class="fas fa-exclamation-triangle"></i>
            </div>
            <a href="<?= base_url('produtos/baixo-estoque') ?>" class="small-box-footer">Ver produtos <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>


      </div>
    </div>
  </div>
</div>
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Estoque Atual dos Produtos</h3>
  </div>
  <div class="card-body">
    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"> <?= session()->getFlashdata('success') ?> </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"> <?= session()->getFlashdata('error') ?> </div>
    <?php endif; ?>
    <div class="table-responsive">
      <table class="table table-bordered table-striped table-sm">
        <thead>
          <tr>
            <th>Produto</th>
            <th>Lote</th>
            <th>Validade</th>
            <th>Quantidade em Estoque</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($estoque)): ?>
            <?php foreach ($estoque as $item): ?>
              <tr>
                <td><?= esc($item['nome_produto']) ?></td>
                <td><?= esc($item['lote']) ?></td>
                <td><?= esc($item['validade']) ?></td>
                <td><?= number_format($item['quantidade'], 4, ',', '.') ?></td>
                <td>
                  <a href="<?= base_url('estoque/historico/' . $item['produto_id']) ?>" class="btn btn-info btn-sm">Histórico</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="text-center">Nenhum produto com saldo em estoque.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<!-- solid sales graph -->
<div class="card bg-gradient-info">
  <div class="card-header border-0">
    <h3 class="card-title">
      <i class="fas fa-th mr-1"></i>
      Entradas e Saídas de Produtos por Dia
    </h3>

    <div class="card-tools">
      <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
        <i class="fas fa-minus"></i>
      </button>
      <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
        <i class="fas fa-times"></i>
      </button>
    </div>
  </div>
  <div class="card-body">
    <canvas class="chart" id="line-chart"
      style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
  </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
  // Dados para o gráfico
  const chartLabels = <?= json_encode($chartLabels ?? []) ?>;
  const chartEntradas = <?= json_encode($chartEntradas ?? []) ?>;
  const chartSaidas = <?= json_encode($chartSaidas ?? []) ?>;

  // Configuração do Chart.js
  const ctx = document.getElementById('line-chart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: chartLabels,
      datasets: [{
          label: 'Entradas',
          data: chartEntradas,
          borderColor: 'rgba(75, 192, 192, 1)',
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          borderWidth: 2,
          fill: true,
          tension: 0.4
        },
        {
          label: 'Saídas',
          data: chartSaidas,
          borderColor: 'rgba(255, 99, 132, 1)',
          backgroundColor: 'rgba(255, 99, 132, 0.2)',
          borderWidth: 2,
          fill: true,
          tension: 0.4
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true,
          position: 'top'
        }
      },
      scales: {
        x: {
          title: {
            display: true,
            text: 'Dia'
          }
        },
        y: {
          title: {
            display: true,
            text: 'Quantidade'
          },
          beginAtZero: true,
          suggestedMin: 0,
          suggestedMax: Math.max(...chartEntradas, ...chartSaidas) + 10 // Ajusta o limite máximo dinamicamente
        }
      }
    }
  });
</script>
<?= $this->endSection() ?>