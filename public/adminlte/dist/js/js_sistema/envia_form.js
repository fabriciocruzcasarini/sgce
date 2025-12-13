$(function () {
    $('#enviar').click(function (Event) {
        Event.preventDefault();
        var base_url = 'https://fabriciocruz.ddns.net/sistema/';
        var caminho = $('#frm').attr('class');

        $('#frm').ajaxForm({

            dataType: 'json',
            url: base_url + caminho,
            resetForm: true,
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
            }
        }).submit();
    })
    $('#fechar').click(function (Event) {
        Event.preventDefault();
        location.reload();
    })
});