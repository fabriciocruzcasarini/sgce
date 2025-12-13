<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Registrar Saída de Estoque (FIFO)</h3>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"> <?= session()->getFlashdata('success') ?> </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"> <?= session()->getFlashdata('error') ?> </div>
        <?php endif; ?>
        <form method="post" action="<?= base_url('estoque/registrarSaida') ?>">

            <div class="form-group">
                <!-- Alterado o label para refletir o novo conteúdo do select -->
                <label for="produto_id">Produto - Lote - Saldo</label>
                <select name="produto_id" id="produto_id" class="form-control" required>
                    <option value="">Selecione um Produto/Lote</option>
                    <?php
                    // Loop sobre os dados agrupados por lote/validade, passados pelo Controller
                    // A variável é $lotes_disponiveis (antes era $produtos)
                    foreach ($lotes_disponiveis as $lote):

                        // 1. Nome do Produto
                        $nome_completo = esc($lote['nome_produto']);

                        // 2. Lote
                        if (!empty($lote['lote'])) {
                            $nome_completo .= ' - Lote: ' . esc($lote['lote']);
                        } else {
                            $nome_completo .= ' - S/Lote';
                        }

                        // 3. Validade (Opcional, mas útil)
                        if (!empty($lote['validade']) && $lote['validade'] !== '0000-00-00') {
                            $validade_formatada = date('d/m/Y', strtotime($lote['validade']));
                            $nome_completo .= ' - Val: ' . $validade_formatada;
                        }

                        // 4. Saldo em Estoque (Obrigatório)
                        $saldo_formatado = number_format($lote['saldo_lote'], 4, ',', '.');
                        $nome_completo .= ' - Saldo: ' . $saldo_formatado;
                    ?>
                        <!-- O VALOR do option é o produto_id, mantendo a compatibilidade com registrarSaida -->
                        <option value="<?= $lote['produto_id'] ?>">
                            <?= $nome_completo ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="quantidade">Quantidade a retirar</label>
                <input type="number" step="0.0001" min="0.0001" name="quantidade" id="quantidade" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrar Saída</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>