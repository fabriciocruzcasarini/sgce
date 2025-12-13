$(document).ready(function() {
    $('#adicionar').on('click', function() {
        var numero_nf = $('#id_notafiscal').val();
        var newFieldHTML = `
            <div class="row campos">
                <div class="col-2">
                    <div class="form-group">
                        <label>Nota Fiscal</label>
                        <input type="text" name="id_notafiscal[]" class="form-control">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label>Produto</label>
                        <input type="text" name="id_produto[]" class="form-control">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label>Quantidade</label>
                        <input type="text" name="qtd_entrada[]" class="form-control">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label>Valor Unitario</label>
                        <input type="text" name="valor_unitario[]" class="form-control">
                    </div>
                </div>
                <div class="col-1 text-right">
                    <div class="form-group">
                        <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                        <button type="button" class="btn-xs btn-danger remover">Remover</button>
                    </div>
                </div>
            </div>
        `;
        $('#card-body').append(newFieldHTML);
    });

    $('#card-body').on('click', '.remover', function() {
        $(this).closest('.row.campos').remove();
    });
});