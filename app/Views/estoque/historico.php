<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Histórico de Movimentações - <?= esc($produto['nome'] ?? '') ?></h3>
    </div>
    <div class="card-body">
        <a href="<?= base_url('estoque') ?>" class="btn btn-secondary mb-3">Voltar ao Estoque</a>

        <?php if (!empty($movimentacoes_agrupadas)): ?>
            <?php foreach ($movimentacoes_agrupadas as $origem_key => $grupo): ?>

                <h4 class="mt-4 mb-2 border-bottom pb-1 text-primary">Origem: <?= esc($grupo['origem_label']) ?></h4>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr class="bg-light">
                                <th>Tipo</th>
                                <th>Quantidade</th>
                                <th>Lote</th>
                                <th>Validade</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($grupo['items'] as $m): ?>
                                <tr>
                                    <td>
                                        <span class="badge badge-<?= ($m['tipo'] == 'entrada' ? 'success' : 'danger') ?>">
                                            <?= esc(ucfirst($m['tipo'])) ?>
                                        </span>
                                    </td>
                                    <td><?= number_format($m['quantidade'], 4, ',', '.') ?></td>
                                    <td><?= esc($m['lote']) ?></td>
                                    <td><?= esc($m['validade']) ?></td>
                                    <td><?= esc($m['created_at']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info text-center">Nenhuma movimentação encontrada.</div>
        <?php endif; ?>

    </div>
</div>

<?= $this->endSection() ?>