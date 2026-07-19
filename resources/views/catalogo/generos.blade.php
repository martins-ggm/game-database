<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gêneros — Game Database</title>
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

    {{-- topbar --}}
    <header class="border-b border-white/10">
        <div class="max-w-[1600px] mx-auto px-6 sm:px-12 py-5 flex items-center justify-between">
            <a href="/" class="text-2xl sm:text-3xl font-black tracking-widest">
                GAME<span class="text-[#6B5B9E]">DB</span>
            </a>
            <a href="{{ route('gerenciador.dashboard.visualizar') }}"
                class="text-sm font-bold tracking-widest uppercase text-white/60 hover:text-[#6B5B9E] transition">
                &larr; Dashboard
            </a>
        </div>
    </header>

    {{-- conteúdo --}}
    <main class="flex-1 max-w-[1600px] w-full mx-auto px-6 sm:px-12 py-12">

        {{-- cabeçalho da página --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <h1 class="text-2xl sm:text-3xl font-black tracking-widest uppercase border-l-4 border-[#6B5B9E] pl-4">
                Gêneros
            </h1>
            <button type="button" id="btn-novo-genero"
                class="self-start sm:self-auto px-6 py-3 bg-[#6B5B9E] text-black font-black tracking-widest uppercase text-xs hover:bg-[#8674B8] transition">
                + Novo Gênero
            </button>
        </div>

        {{-- feedback --}}
        <div id="mensagem" class="hidden mb-4 p-3 border text-sm font-bold tracking-wide"></div>

        {{-- busca --}}
        <div class="mb-4">
            <input type="text" id="busca" placeholder="Buscar por nome..." autocomplete="off"
                class="w-full sm:max-w-sm px-4 py-3 bg-[#1C1B26] border border-white/10 text-white placeholder-white/30 focus:outline-none focus:border-[#6B5B9E] transition">
        </div>

        {{-- tabela --}}
        <div class="bg-[#1C1B26] border border-white/10 overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-white/10 text-left">
                        <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-white/40">Nome</th>
                        <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-white/40 text-right">
                            Ações</th>
                    </tr>
                </thead>
                <tbody id="tabela-generos">
                    @forelse ($generos ?? [] as $genero)
                        <tr class="border-b border-white/5 hover:bg-[#25232F] transition">
                            <td class="px-5 py-4 font-bold">{{ $genero->nome }}</td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <button type="button" data-editar-genero="{{ $genero->id }}"
                                    class="text-[10px] font-black uppercase tracking-widest text-white/60 hover:text-[#6B5B9E] transition">
                                    Editar
                                </button>
                                <button type="button" data-remover-genero="{{ $genero->id }}"
                                    class="ml-4 text-[10px] font-black uppercase tracking-widest text-white/60 hover:text-red-400 transition">
                                    Excluir
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr id="linha-vazia">
                            <td colspan="2"
                                class="px-5 py-12 text-center text-white/30 text-xs uppercase tracking-widest">
                                Nenhum gênero cadastrado
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>

    {{-- footer --}}
    <footer class="border-t border-white/10">
        <div
            class="max-w-[1600px] mx-auto px-6 sm:px-12 py-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-[10px] tracking-widest text-white/40 uppercase font-bold">&copy; 2026 Game Database</p>
            <p class="text-[10px] tracking-widest text-white/40 uppercase font-bold">Built with the SISP architecture
            </p>
        </div>
    </footer>

    {{-- ============ MODAL (novo / editar) ============ --}}
    <div id="modal-genero" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        {{-- backdrop --}}
        <div class="absolute inset-0 bg-black/70" data-fechar-modal></div>

        {{-- caixa --}}
        <div class="relative w-full max-w-md bg-[#1C1B26] border border-white/10">

            {{-- header do modal --}}
            <div class="flex items-center justify-between px-6 py-5 border-b border-white/10">
                <h2 id="modal-titulo"
                    class="text-lg font-black tracking-widest uppercase border-l-4 border-[#6B5B9E] pl-3">
                    Novo Gênero
                </h2>
                <button type="button" data-fechar-modal
                    class="text-white/40 hover:text-white text-2xl leading-none">&times;</button>
            </div>

            {{-- erros de validação (jQuery preenche) --}}
            <ul id="erros"
                class="hidden mx-6 mt-4 p-3 bg-red-500/10 border border-red-500/30 text-sm text-red-300 list-disc list-inside space-y-1">
            </ul>

            {{-- form --}}
            <form id="form-genero" class="p-6 space-y-5">
                <input type="hidden" id="genero_id" name="id">

                <div>
                    <label for="nome"
                        class="block text-[10px] font-black uppercase tracking-widest text-white/60 mb-2">Nome</label>
                    <input type="text" id="nome" name="nome" placeholder="Nome do gênero"
                        class="w-full px-4 py-3 bg-[#11101A] border border-white/10 text-white placeholder-white/30 focus:outline-none focus:border-[#6B5B9E] transition">
                </div>

                {{-- ações do modal --}}
                <div class="flex flex-col sm:flex-row gap-3 sm:justify-end pt-2">
                    <button type="button" data-fechar-modal
                        class="px-6 py-3 border border-white/30 text-white font-black tracking-widest uppercase text-xs hover:border-white/60 transition">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-[#6B5B9E] text-black font-black tracking-widest uppercase text-xs hover:bg-[#8674B8] transition">
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ============ MODAL de confirmação de remoção ============ --}}
    <div id="modal-remover" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        {{-- backdrop --}}
        <div class="absolute inset-0 bg-black/70" data-fechar-remocao></div>

        {{-- caixa --}}
        <div class="relative w-full max-w-sm bg-[#1C1B26] border border-white/10">

            {{-- header --}}
            <div class="flex items-center justify-between px-6 py-5 border-b border-white/10">
                <h2 class="text-lg font-black tracking-widest uppercase border-l-4 border-red-400 pl-3">
                    Remover
                </h2>
                <button type="button" data-fechar-remocao
                    class="text-white/40 hover:text-white text-2xl leading-none">&times;</button>
            </div>

            {{-- corpo --}}
            <div class="p-6 space-y-1">
                <p class="text-sm text-white/70">Tem certeza que deseja remover</p>
                <p id="nome-remocao" class="text-base font-black tracking-wide text-white break-words"></p>
                <p class="text-[10px] text-white/40 uppercase tracking-widest pt-3">Esta ação não pode ser desfeita.</p>
            </div>

            {{-- ações --}}
            <div class="flex flex-col sm:flex-row gap-3 sm:justify-end px-6 pb-6">
                <button type="button" data-fechar-remocao
                    class="px-6 py-3 border border-white/30 text-white font-black tracking-widest uppercase text-xs hover:border-white/60 transition">
                    Cancelar
                </button>
                <button type="button" id="btn-confirmar-remocao"
                    class="px-6 py-3 bg-red-500 text-black font-black tracking-widest uppercase text-xs hover:bg-red-400 transition">
                    Remover
                </button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(function() {

            const modal = $('#modal-genero');

            // ---------- helpers ----------
            function escapar(texto) {
                return $('<div>').text(texto ?? '').html();
            }

            function abrirModalNovo() {
                $('#form-genero')[0].reset();
                $('#genero_id').val('');
                $('#modal-titulo').text('Novo Gênero');
                $('#erros').addClass('hidden').empty();
                modal.removeClass('hidden');
                $('#nome').trigger('focus');
            }

            function fecharModal() {
                modal.addClass('hidden');
            }

            function mostrarSucesso(texto) {
                $('#mensagem')
                    .removeClass('hidden bg-red-500/10 border-red-500/30 text-red-300')
                    .addClass('bg-[#6B5B9E]/10 border-[#6B5B9E]/40 text-[#6B5B9E]')
                    .text(texto);
            }

            function mostrarErroMensagem(texto) {
                $('#mensagem')
                    .removeClass('hidden bg-[#6B5B9E]/10 border-[#6B5B9E]/40 text-[#6B5B9E]')
                    .addClass('bg-red-500/10 border-red-500/30 text-red-300')
                    .text(texto);
            }

            function mostrarErros(lista) {
                $('#erros').empty();
                lista.forEach(function(msg) {
                    $('#erros').append('<li>' + escapar(msg) + '</li>');
                });
                $('#erros').removeClass('hidden');
            }

            // monta o HTML de uma linha (reaproveitado por incluir e pela busca)
            function linhaHtml(genero) {
                return `
                    <tr class="border-b border-white/5 hover:bg-[#25232F] transition">
                        <td class="px-5 py-4 font-bold">${escapar(genero.nome)}</td>
                        <td class="px-5 py-4 text-right whitespace-nowrap">
                            <button type="button" data-editar-genero="${genero.id}"
                                class="text-[10px] font-black uppercase tracking-widest text-white/60 hover:text-[#6B5B9E] transition">Editar</button>
                            <button type="button" data-remover-genero="${genero.id}"
                                class="ml-4 text-[10px] font-black uppercase tracking-widest text-white/60 hover:text-red-400 transition">Excluir</button>
                        </td>
                    </tr>`;
            }

            function adicionarLinha(genero) {
                $('#linha-vazia').remove();
                $('#tabela-generos').append(linhaHtml(genero));
            }

            // redesenha a tabela inteira a partir de uma lista (usado na busca)
            function renderizarTabela(generos) {
                const tbody = $('#tabela-generos');
                tbody.empty();

                if (generos.length === 0) {
                    tbody.html(
                        '<tr id="linha-vazia"><td colspan="2" class="px-5 py-12 text-center text-white/30 text-xs uppercase tracking-widest">Nenhum gênero encontrado</td></tr>'
                    );
                    return;
                }

                generos.forEach(function(genero) {
                    tbody.append(linhaHtml(genero));
                });
            }

            function atualizarLinha(genero) {
                const botao = $('#tabela-generos').find('[data-editar-genero="' + genero.id + '"]');
                const linha = botao.closest('tr');
                linha.find('td').eq(0).text(genero.nome);
            }

            // ---------- modal (abrir / fechar) ----------
            $('#btn-novo-genero').on('click', abrirModalNovo);
            $('[data-fechar-modal]').on('click', fecharModal);

            // ---------- busca (com debounce) ----------
            const urlBuscar = "{{ route('catalogo.genero.buscar') }}";
            let timerBusca = null;   // segura o disparo até parar de digitar
            let reqBusca = null;     // guarda a requisição em voo pra poder cancelar

            $('#busca').on('input', function() {
                const termo = $(this).val();

                clearTimeout(timerBusca);                 // reinicia a contagem a cada tecla
                timerBusca = setTimeout(function() {

                    if (reqBusca) reqBusca.abort();        // cancela a busca anterior que ainda não voltou

                    reqBusca = $.ajax({
                        url: urlBuscar,
                        method: 'GET',
                        data: { nome: termo },             // vira ?nome=... na URL
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        success: function(response) {
                            renderizarTabela(response.generos);
                        },
                        error: function(xhr, status) {
                            if (status === 'abort') return;   // foi cancelada de propósito, ignora
                            mostrarErroMensagem(xhr.responseJSON?.message || 'Erro na busca.');
                        }
                    });
                }, 300);
            });

            // ---------- abrir modal em modo edição ----------
            $('#tabela-generos').on('click', '[data-editar-genero]', function() {
                const id = $(this).attr('data-editar-genero');
                const nome = $(this).closest('tr').find('td').eq(0).text().trim();

                $('#genero_id').val(id);
                $('#nome').val(nome);
                $('#modal-titulo').text('Editar Gênero');
                $('#erros').addClass('hidden').empty();
                $('#mensagem').addClass('hidden').empty();
                modal.removeClass('hidden');
                $('#nome').trigger('focus');
            });

            // ---------- incluir / editar ----------
            const urlEditarBase = "{{ route('catalogo.genero.editar', ['id' => 'ID_PLACEHOLDER']) }}";

            $('#form-genero').on('submit', function(e) {
                e.preventDefault();
                $('#erros').addClass('hidden').empty();
                $('#mensagem').addClass('hidden').empty();

                const id = $('#genero_id').val();

                const url = id ?
                    urlEditarBase.replace('ID_PLACEHOLDER', id) // tem id → editar
                    :
                    "{{ route('catalogo.genero.criar') }}"; // sem id → criar

                $.ajax({
                    url: url,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    contentType: 'application/json',
                    data: JSON.stringify({
                        id: id || null,
                        nome: $('#nome').val()
                    }),
                    success: function(response) {
                        if (id) {
                            atualizarLinha(response.genero);
                        } else {
                            adicionarLinha(response.genero);
                        }
                        fecharModal();
                        mostrarSucesso(response.mensagem);
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            mostrarErros(Object.values(xhr.responseJSON.errors).flat());
                        } else {
                            mostrarErros([xhr.responseJSON?.message || 'Erro inesperado.']);
                        }
                    }
                });
            });

            // ---------- remover ----------
            const urlRemoverBase = "{{ route('catalogo.genero.remover', ['id' => 'ID_PLACEHOLDER']) }}";
            const modalRemover = $('#modal-remover');
            let removerId = null;      // guarda quem está sendo removido
            let removerLinha = null;   // guarda a <tr> pra apagar depois

            function fecharRemocao() {
                modalRemover.addClass('hidden');
                removerId = null;
                removerLinha = null;
            }

            // clicar em "Excluir" → abre o modal de confirmação (não remove ainda)
            $('#tabela-generos').on('click', '[data-remover-genero]', function() {
                removerId = $(this).attr('data-remover-genero');
                removerLinha = $(this).closest('tr');
                const nome = removerLinha.find('td').eq(0).text().trim();

                $('#nome-remocao').text(nome);
                $('#mensagem').addClass('hidden').empty();
                modalRemover.removeClass('hidden');
            });

            // cancelar / X / backdrop
            $('[data-fechar-remocao]').on('click', fecharRemocao);

            // confirmar → aí sim manda o AJAX
            $('#btn-confirmar-remocao').on('click', function() {
                if (!removerId) return;

                const id = removerId;         // cópia local, o modal pode fechar antes do AJAX voltar
                const linha = removerLinha;

                $.ajax({
                    url: urlRemoverBase.replace('ID_PLACEHOLDER', id),
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        linha.remove();
                        if ($('#tabela-generos tr').length === 0) {
                            $('#tabela-generos').html(
                                '<tr id="linha-vazia"><td colspan="2" class="px-5 py-12 text-center text-white/30 text-xs uppercase tracking-widest">Nenhum gênero cadastrado</td></tr>'
                            );
                        }
                        fecharRemocao();
                        mostrarSucesso(response.mensagem);
                    },
                    error: function(xhr) {
                        fecharRemocao();
                        mostrarErroMensagem(xhr.responseJSON?.message || 'Erro ao remover.');
                    }
                });
            });

        });
    </script>

</body>

</html>
