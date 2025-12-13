<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?= esc($title) ?></h3>
        <a href="<?= base_url('notas-fiscais/create') ?>" class="btn btn-primary float-right">Nova Nota Fiscal</a>
    </div>
    <div class="card-body responsive">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Notas Fiscais Ativas</h3>
    </div>
    <div class="card-body responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Número da NF</th>
                    <th>Fornecedor</th>
                    <th>Tipo Operação</th>
                    <th>Data de Entrada</th>
                    <th>Pedido</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notasFiscais as $n): ?>
                    <tr>
                        <td><?= esc($n['id']) ?></td>
                        <td><?= esc($n['numero']) ?></td>
                        <td><?= esc($n['nome_fornecedor']) ?></td>
                        <td><?= esc($n['tipo_operacao']) ?></td>
                        <td><?= esc($n['data_entrada']) ?></td>
                        <td><?= esc($n['numero_pedido']) ?></td>
                        <td>
                            <a href="<?= base_url('notas-fiscais/edit/' . $n['id']) ?>" class="btn btn-sm btn-warning">Editar</a>
                            <?php if ($n['status'] === 'ativo'): ?>
                                <a href="<?= base_url('notas-fiscais/desativar/' . $n['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Deseja desativar esta nota fiscal?')">Desativar</a>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Notas Fiscais Ativas</h3>
    </div>
    <div class="card-body responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Número da NF</th>
                    <th>Fornecedor</th>
                    <th>Tipo Operação</th>
                    <th>Data de Entrada</th>
                    <th>Pedido</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notasFiscais_consolidadas as $n): ?>
                    <tr>
                        <td><?= esc($n['id']) ?></td>
                        <td><?= esc($n['numero']) ?></td>
                        <td><?= esc($n['nome_fornecedor']) ?></td>
                        <td><?= esc($n['tipo_operacao']) ?></td>
                        <td><?= esc($n['data_entrada']) ?></td>
                        <td><?= esc($n['numero_pedido']) ?></td>
                        <td>
                            <a href="<?= base_url('notas-fiscais/edit/' . $n['id']) ?>" class="btn btn-sm btn-warning">Editar</a>
                            <?php if ($n['status'] == 'ativo'): ?>
                                <a href="<?= base_url('notas-fiscais/desativar/' . $n['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Deseja desativar esta nota fiscal?')">Desativar</a>
                            <?php endif ?>
                            <?php if ($n['status'] == 'consolidada'): ?>
                                <a href="<?= base_url('notas-fiscais/desativar/' . $n['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Deseja desativar esta nota fiscal?')">Desativar</a>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>