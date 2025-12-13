$(document).ready(function() {
    $('#adicionar').on('click', function() {
        var numero_nf = $('#id_notafiscal').val(); 
        var newFieldHTML = `
            <div class="row campos">
                <div class="col-2">
                    <div class="form-group">
                        <label>Nota Fiscal</label>
                        <input type="text" name="id_notafiscal[]" class="form-control" value="${numero_nf}"> 
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label>Produto</label>
                        <input type="text" name="id_produto[]" class="form-control" id="id_produto"> 
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
                        <input type="text" name="valor_unitario[]" class="form-control" id="valor_unitario"> 
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

        // Get the last added product input field
        var lastProductIdInput = $('#card-body').find('.campos:last').find('#id_produto'); 

        lastProductIdInput.on('change', function() {
            var productId = $(this).val();
            var valorUnitarioInput = $(this).closest('.campos').find('#valor_unitario');

            $.ajax({
                url: 'https://fabriciocruz.ddns.net/sistema/entrada-estoque/get-produto/' + productId, // Replace with your API endpoint
                type: 'GET',
                success: function(data) {
                    if (data.success) {
                        valorUnitarioInput.val(data.valor_unitario); 
                    } else {
                        // Handle error (e.g., display an alert)
                        alert("Produto não encontrado.");
                        valorUnitarioInput.val(""); 
                    }
                },
                error: function() {
                    // Handle error (e.g., display an alert)
                    alert("Erro ao buscar dados do produto.");
                    valorUnitarioInput.val(""); 
                }
            });
        });
    });

    $('#card-body').on('click', '.remover', function() {
        $(this).closest('.row.campos').remove();
    });
});