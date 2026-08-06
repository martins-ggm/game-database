<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar conta — Game Database</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
        }
    </style>
</head>

<body class="bg-[#11101A] text-white min-h-screen flex flex-col">

    {{-- topbar com a marca --}}
    <header class="border-b border-white/10">
        <div class="max-w-[1600px] mx-auto px-6 sm:px-12 py-5 flex items-center justify-between">
            <a href="/" class="text-2xl sm:text-3xl font-black tracking-widest">
                GAME<span class="text-[#6B5B9E]">DB</span>
            </a>
            <a href="/"
                class="text-sm font-bold tracking-widest uppercase text-white/60 hover:text-[#6B5B9E] transition">
                &larr; VOLTAR
            </a>
        </div>
    </header>

    {{-- form --}}
    <main class="flex-1 flex items-center justify-center px-6 py-12 sm:py-20">
        <div class="w-full max-w-md">

            <div class="mb-8">
                <h1 class="text-2xl sm:text-3xl font-black tracking-widest uppercase border-l-4 border-[#6B5B9E] pl-4">
                    Criar conta
                </h1>
                <p class="text-sm text-white/60 mt-3 ml-5">Preencha os dados para se cadastrar</p>
            </div>

            <div id="mensagem" class="hidden mb-4 p-3 border text-sm font-bold tracking-wide"></div>

            <ul id="erros"
                class="hidden mb-4 p-3 bg-red-500/10 border border-red-500/30 text-sm text-red-300 list-disc list-inside space-y-1">
            </ul>

            <form id="form-criar-usuario" class="space-y-5 bg-[#1C1B26] border border-white/10 p-6 sm:p-8">
                <div>
                    <label for="nome"
                        class="block text-[10px] font-black uppercase tracking-widest text-white/60 mb-2">Nome</label>
                    <input type="text" id="nome" name="nome" placeholder="Digite seu nome"
                        class="w-full px-4 py-3 bg-[#11101A] border border-white/10 text-white placeholder-white/30 focus:outline-none focus:border-[#6B5B9E] transition">
                </div>

                <div>
                    <label for="email"
                        class="block text-[10px] font-black uppercase tracking-widest text-white/60 mb-2">Email</label>
                    <input type="email" id="email" name="email" placeholder="seu@email.com"
                        class="w-full px-4 py-3 bg-[#11101A] border border-white/10 text-white placeholder-white/30 focus:outline-none focus:border-[#6B5B9E] transition">
                </div>

                <div>
                    <label for="password"
                        class="block text-[10px] font-black uppercase tracking-widest text-white/60 mb-2">Senha</label>
                    <input type="password" id="password" name="password" placeholder="Mínimo 6 caracteres"
                        class="w-full px-4 py-3 bg-[#11101A] border border-white/10 text-white placeholder-white/30 focus:outline-none focus:border-[#6B5B9E] transition">
                </div>

                <div>
                    <label for="password_confirmation"
                        class="block text-[10px] font-black uppercase tracking-widest text-white/60 mb-2">Confirmar
                        senha</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        placeholder="Digite a senha novamente"
                        class="w-full px-4 py-3 bg-[#11101A] border border-white/10 text-white placeholder-white/30 focus:outline-none focus:border-[#6B5B9E] transition">
                </div>

                <div>
                    <label
                        class="block text-[10px] font-black uppercase tracking-widest text-white/60 mb-2">Foto de perfil
                        <span class="text-white/30 normal-case tracking-normal">(opcional)</span></label>
                    <div class="flex items-center gap-4">
                        <div
                            class="w-20 h-20 flex-shrink-0 bg-[#11101A] border border-white/10 overflow-hidden flex items-center justify-center">
                            <img id="preview-imagem" src="" class="w-full h-full object-cover hidden">
                            <span id="preview-imagem-vazio"
                                class="text-[10px] text-white/30 uppercase tracking-widest">Sem foto</span>
                        </div>
                        <input type="file" id="imagem" name="imagem" accept="image/jpeg,image/png,image/webp"
                            class="text-xs text-white/60 file:mr-3 file:py-2 file:px-4 file:border-0 file:bg-[#6B5B9E] file:text-black file:font-black file:uppercase file:tracking-widest file:text-[10px] file:cursor-pointer">
                    </div>
                </div>

                <button type="submit"
                    class="w-full mt-2 py-3 bg-[#6B5B9E] text-black font-black tracking-widest uppercase text-xs hover:bg-[#8674B8] transition">
                    Cadastrar
                </button>
            </form>

            <p class="text-center text-xs text-white/40 mt-6 tracking-wide">
                Já tem conta?
                <a href="{{ route('gerenciador.usuario.login') }}"
                    class="text-[#6B5B9E] font-bold hover:text-[#8674B8] uppercase tracking-widest">Entrar</a>
            </p>
        </div>
    </main>

    {{-- footer --}}
    <x-footer />

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(function() {

            // preview local da foto escolhida
            $('#imagem').on('change', function() {
                const arquivo = this.files[0];
                if (!arquivo) return;

                $('#preview-imagem').attr('src', URL.createObjectURL(arquivo)).removeClass('hidden');
                $('#preview-imagem-vazio').addClass('hidden');
            });

            $('#form-criar-usuario').on('submit', function(e) {
                e.preventDefault();

                $('#erros').addClass('hidden').empty();
                $('#mensagem').addClass('hidden').empty();

                // arquivo não vai por JSON → FormData
                const dados = new FormData();
                dados.append('nome', $('#nome').val());
                dados.append('email', $('#email').val());
                dados.append('password', $('#password').val());
                dados.append('password_confirmation', $('#password_confirmation').val());

                const arquivo = $('#imagem')[0].files[0];
                if (arquivo) dados.append('imagem', arquivo); // só manda se escolheu

                $.ajax({
                    url: "{{ route('gerenciador.usuario.incluir') }}",
                    method: 'POST',
                    data: dados,
                    processData: false, // não serializa o FormData
                    contentType: false, // deixa o browser pôr o boundary do multipart
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        $('#mensagem')
                            .removeClass('hidden bg-red-500/10 border-red-500/30 text-red-300')
                            .addClass('bg-[#6B5B9E]/10 border-[#6B5B9E]/40 text-[#6B5B9E]')
                            .text(response.mensagem);
                        $('#form-criar-usuario')[0].reset();

                        // limpa o preview junto com o form
                        $('#preview-imagem').addClass('hidden').attr('src', '');
                        $('#preview-imagem-vazio').removeClass('hidden');
                          window.location.href = response.redirect;
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const erros = xhr.responseJSON.errors;
                            for (const campo in erros) {
                                erros[campo].forEach(function(msg) {
                                    $('#erros').append('<li>' + msg + '</li>');
                                });
                            }
                            $('#erros').removeClass('hidden');
                        } else {
                            $('#mensagem')
                                .removeClass(
                                    'hidden bg-[#6B5B9E]/10 border-[#6B5B9E]/40 text-[#6B5B9E]')
                                .addClass('bg-red-500/10 border-red-500/30 text-red-300')
                                .text(xhr.responseJSON?.message || 'Erro inesperado.');
                        }
                    }
                });
            });
        });
    </script>

</body>

</html>
