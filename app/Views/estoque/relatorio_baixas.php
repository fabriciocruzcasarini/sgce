<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Relatório de Baixas de Estoque (Saídas FIFO)</h3>
    </div>
    <div class="card-body">
        <form method="get" class="mb-3">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="produto_id">Produto</label>
                    <select name="produto_id" id="produto_id" class="form-control">
                        <option value="">Todos</option>
                        <?php foreach ($produtos as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= ($filtros['produto_id'] ?? '') == $p['id'] ? 'selected' : '' ?>><?= esc($p['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="cliente_id">Cliente</label>
                    <select name="cliente_id" id="cliente_id" class="form-control">
                        <option value="">Todos</option>
                        <?php foreach ($clientes as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= ($filtros['cliente_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= esc($c['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="periodo">Período</label>
                    <input type="date" name="data_ini" value="<?= esc($filtros['data_ini'] ?? '') ?>" class="form-control d-inline-block w-45"> a
                    <input type="date" name="data_fim" value="<?= esc($filtros['data_fim'] ?? '') ?>" class="form-control d-inline-block w-45">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </form>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Produto</th>
                        <th>Lote</th>
                        <th>Validade</th>
                        <th>Cliente</th>
                        <th>Quantidade</th>
                        <th>Origem</th>
                        <th>Origem ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($baixas)): ?>
                        <?php foreach ($baixas as $b): ?>
                            <tr>
                                <td><?= esc($b['created_at']) ?></td>
                                <td><?= esc($b['nome_produto']) ?></td>
                                <td><?= esc($b['lote']) ?></td>
                                <td><?= esc($b['validade']) ?></td>
                                <td><?= esc($b['nome_cliente']) ?></td>
                                <td><?= number_format($b['quantidade'], 4, ',', '.') ?></td>
                                <td><?= esc($b['origem']) ?></td>
                                <td><?= esc($b['origem_id']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">Nenhuma baixa encontrada.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>