<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
</head>




<body>
    <div>
        <h1>Cadastro</h1>


        <div id="mensagem" style="display:none;"></div>
        <ul id="erros" style="color:red; display:none;"></ul>

        <form id="form-criar-usuario">
            <div>
                <label for="name">Nome</label>
                <input type="text" id="name" name="name" placeholder="Digite seu nome">
            </div>

            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Digite seu email">
            </div>

            <div>
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" placeholder="Digite sua senha">
            </div>

            <div>
                <label for="password_confirmation">Confirmar Senha</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    placeholder="Confirme sua senha">
            </div>

            <button type="submit">Cadastrar</button>
        </form>
    </div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(function() {

        $('#form-criar-usuario').on('submit', function(e) {
            e.preventDefault();

            $('#erros').hide().empty();
            $('#mensagem').hide().empty;

            $.ajax({
                url: "{{ route('gerenciador.usuario.incluir') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'

                },
                contentType: 'application/json',
                data: JSON.stringify({
                    name: $('#name').val(),
                    email: $('#email').val(),
                    password: $('#password').val(),
                    password_confirmation: $('#password_confirmation').val()
                }),
                sucess: function(response) {
                    $('mensagem').text(response.mensagem).css('color', 'green').show();
                    $('$form-criar-usuario')[0].reset();
                },
                error: function(xhr) {

                    if (xhr.status === 422) {
                        const erros = xhr.responseJSON.erros;
                        for (const campo in erros) {
                            erros[campo].forEach(function(msg) {
                                $('$erros').append('<li>' + msg + '</li>');
                            });
                        }
                        $('erros').show();
                    } else {
                        $('#mensagem').text(xhr.responseJSON?.mensagem ||
                            'Erro inesperado.').css('color', 'red').show();
                    }

                }
            });
        });


    })
</script>




</body>

</html>
