<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Histórico de Movimentações - <?= esc($produto['nome'] ?? '') ?></h3>
    </div>
    <div class="card-body">
        <a href="<?= base_url('estoque') ?>" class="btn btn-secondary mb-3">Voltar ao Estoque</a>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Quantidade</th>
                        <th>Lote</th>
                        <th>Validade</th>
                        <th>Origem</th>
                        <th>Número da NF</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($movimentacoes)): ?>
                        <?php foreach ($movimentacoes as $m): ?>
                            <tr>
                                <td><?= esc(ucfirst($m['tipo'])) ?></td>
                                <td><?= number_format($m['quantidade'], 4, ',', '.') ?></td>
                                <td><?= esc($m['lote']) ?></td>
                                <td><?= esc($m['validade']) ?></td>
                                <td><?= esc($m['origem']) ?></td>
                                <td><?= esc($m['numero_nf']) ?></td>
                                <td><?= esc($m['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Nenhuma movimentação encontrada.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>