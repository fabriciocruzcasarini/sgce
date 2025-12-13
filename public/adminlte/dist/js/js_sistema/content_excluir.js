$(function () {
    $('#excluir').click(function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.resultado === true) {
                    $('.modal-title').html('Cadastro realizado');
                    $('#msg').html(response.mensagem);
                    $('#type_modal').addClass('bg-success');
                    $('.modal').modal();
                } else {
                    $('.modal-title').html('<h3>Atenção!!!</h3>');
                    $('#msg').html(response.mensagem);
                    $('#type_modal').addClass('bg-warning');
                    $('.modal').modal();
                }
            },
            error: function (xhr, status, error) {

                $('.modal-title').html('<h3>Atenção!!!</h3>');
                $('#msg').html(status + error);
                $('#type_modal').addClass('bg-warning');
                $('.modal').modal();

            }
        });
    })
});