<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?= esc($title) ?></h3>
    </div>
    <div class="card-body">

        <?php if ($errors = session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $e): ?>
                        <li><?= esc($e) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif ?>

        <form action="<?= base_url('produtos/store') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <?php if (!empty($produto['id'])): ?>
                <input type="hidden" name="id" value="<?= esc($produto['id']) ?>">
            <?php endif ?>

            <div class="form-group">
                <label>Fornecedor</label>
                <select name="fornecedor_id" id="fornecedor_id" class="form-control <?= session('errors.id') ? 'is-invalid' : '' ?>" required>
                    <option value="">Selecione um fornecedor</option>
                    <?php foreach ($fornecedores as $fornecedor): ?>
                        <option value="<?= esc($fornecedor['id']) ?>" <?= old('fornecedor_id', $produto['fornecedor_id'] ?? '') == $fornecedor['id'] ? 'selected' : '' ?>>
                            <?= esc($fornecedor['nome_fantasia']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Nome</label>
                <input type="text" name="nome" class="form-control" required
                    value="<?= old('nome', $produto['nome'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Descrição</label>
                <input type="text" name="descricao" class="form-control"
                    value="<?= old('descricao', $produto['descricao'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>SKU</label>
                <input type="text" name="sku" class="form-control" required
                    value="<?= old('sku', $produto['sku'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Foto do Produto</label>
                <input type="file" name="imagem" class="form-control-file">
                <?php if (!empty($produto['imagem'])): ?>
                    <small>Imagem atual:</small><br>
                    <img src="<?= base_url('uploads/produtos/' . esc($produto['imagem'])) ?>" class="img-thumbnail mt-2" style="max-width: 150px;">
                <?php endif ?>
            </div>

            <div class="form-group">
                <label>Observação</label>
                <textarea name="observacao" class="form-control"><?= old('observacao', $produto['observacao'] ?? '') ?></textarea>
            </div>

            <!-- <div class="form-group">
                <label>Grupo do Produto</label>
                <select name="grupo_produtos_id" id="grupo_produtos_id" class="form-control" required>
                    <option value="">Selecione um grupo</option>
                    <?php foreach ($grupos as $g): ?>
                        <option value="<?= esc($g['id']) ?>"
                            <?= old('grupo_produtos_id', $produto['grupo_produtos_id'] ?? '') == $g['id'] ? 'selected' : '' ?>>
                            <?= esc($g['nome']) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div> -->
            <div class="form-group">
                <label>Grupo</label>
                <select name="grupo_produtos_id" id="grupo_produtos_id" class="form-control <?= session('errors.id') ? 'is-invalid' : '' ?>" required>
                    <option value="">Selecione um grupo</option>
                    <?php foreach ($grupos as $grupo): ?>
                        <option value="<?= esc($grupo['id']) ?>" <?= old('grupo_produtos_id', $produto['grupo_produtos_id'] ?? '') == $grupo['id'] ? 'selected' : '' ?>>
                            <?= esc($grupo['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div id="subgrupo-loading">🔄 Carregando subgrupos...</div>
            <div class="form-group">
                <label>Subgrupo</label>
                <select name="subgrupo_produtos_id" class="form-control" required>
                    <option value="">Selecione um subgrupo</option>
                    <?php foreach ($subgrupos as $s): ?>
                        <option value="<?= esc($s['id']) ?>"
                            <?= old('subgrupo_produtos_id', $produto['subgrupo_produtos_id'] ?? '') == $s['id'] ? 'selected' : '' ?>>
                            <?= esc($s['nome']) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="ativo" <?= old('status', $produto['status'] ?? '') === 'ativo' ? 'selected' : '' ?>>Ativo</option>
                    <option value="inativo" <?= old('status', $produto['status'] ?? '') === 'inativo' ? 'selected' : '' ?>>Inativo</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success mt-3">Salvar</button>
            <a href="<?= base_url('produtos') ?>" class="btn btn-secondary mt-3">Cancelar</a>
        </form>

    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<!-- Adicione esse estilo para um spinner simples -->
<style>
    #subgrupo-loading {
        display: none;
        margin-top: 5px;
        font-size: 0.9em;
        color: #888;
    }
</style>
<script>
    $(document).ready(function() {
        $('#grupo_produtos_id').on('change', function() {
            let grupoId = $(this).val();
            let $subgrupoSelect = $('select[name="subgrupo_produtos_id"]');
            let $loading = $('#subgrupo-loading');

            $subgrupoSelect.prop('disabled', true);
            $loading.show();

            $.get("<?= base_url('subgrupo-produtos/por-grupo') ?>/" + grupoId, function(data) {
                $subgrupoSelect.empty().append('<option value="">Selecione um subgrupo</option>');
                data.forEach(function(subgrupo) {
                    $subgrupoSelect.append(`<option value="${subgrupo.id}">${subgrupo.nome}</option>`);
                });
            }).fail(function() {
                $subgrupoSelect.empty().append('<option value="">Erro ao carregar subgrupos</option>');
            }).always(function() {
                $subgrupoSelect.prop('disabled', false);
                $loading.hide();
            });
        });
    });
</script>
<?= $this->endSection() ?>