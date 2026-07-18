<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desenvolvedoras — Game Database</title>
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
                Desenvolvedoras
            </h1>
            <button type="button" id="btn-nova-desenvolvedora"
                class="self-start sm:self-auto px-6 py-3 bg-[#6B5B9E] text-black font-black tracking-widest uppercase text-xs hover:bg-[#8674B8] transition">
                + Nova Desenvolvedora
            </button>
        </div>

        {{-- feedback (o jQuery vai usar depois) --}}
        <div id="mensagem" class="hidden mb-4 p-3 border text-sm font-bold tracking-wide"></div>

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
                <tbody id="tabela-desenvolvedoras">
                    @forelse ($desenvolvedoras ?? [] as $desenvolvedora)
                        <tr class="border-b border-white/5 hover:bg-[#25232F] transition">
                            <td class="px-5 py-4 font-bold">{{ $desenvolvedora->nome }}</td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <button type="button" data-editar-desenvolvedora="{{ $desenvolvedora->id }}"
                                    class="text-[10px] font-black uppercase tracking-widest text-white/60 hover:text-[#6B5B9E] transition">
                                    Editar
                                </button>
                                <button type="button" data-remover-desenvolvedora="{{ $desenvolvedora->id }}"
                                    class="ml-4 text-[10px] font-black uppercase tracking-widest text-white/60 hover:text-red-400 transition">
                                    Excluir
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr id="linha-vazia">
                            <td colspan="2"
                                class="px-5 py-12 text-center text-white/30 text-xs uppercase tracking-widest">
                                Nenhuma desenvolvedora cadastrada
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

    {{-- ============ MODAL (nova / editar) ============ --}}
    <div id="modal-desenvolvedora" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        {{-- backdrop --}}
        <div class="absolute inset-0 bg-black/70" data-fechar-modal></div>

        {{-- caixa --}}
        <div class="relative w-full max-w-md bg-[#1C1B26] border border-white/10">

            {{-- header do modal --}}
            <div class="flex items-center justify-between px-6 py-5 border-b border-white/10">
                <h2 id="modal-titulo"
                    class="text-lg font-black tracking-widest uppercase border-l-4 border-[#6B5B9E] pl-3">
                    Nova Desenvolvedora
                </h2>
                <button type="button" data-fechar-modal
                    class="text-white/40 hover:text-white text-2xl leading-none">&times;</button>
            </div>

            {{-- erros de validação (jQuery preenche) --}}
            <ul id="erros"
                class="hidden mx-6 mt-4 p-3 bg-red-500/10 border border-red-500/30 text-sm text-red-300 list-disc list-inside space-y-1">
            </ul>

            {{-- form --}}
            <form id="form-desenvolvedora" class="p-6 space-y-5">
                <input type="hidden" id="desenvolvedora_id" name="id">

                <div>
                    <label for="nome"
                        class="block text-[10px] font-black uppercase tracking-widest text-white/60 mb-2">Nome</label>
                    <input type="text" id="nome" name="nome" placeholder="Nome da desenvolvedora"
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

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(function() {

            const modal = $('#modal-desenvolvedora');

            // ---------- helpers ----------
            function escapar(texto) {
                return $('<div>').text(texto ?? '').html();
            }

            function abrirModalNovo() {
                $('#form-desenvolvedora')[0].reset();
                $('#desenvolvedora_id').val('');
                $('#modal-titulo').text('Nova Desenvolvedora');
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

            function adicionarLinha(desenvolvedora) {
                $('#linha-vazia').remove();
                const linha = `
                    <tr class="border-b border-white/5 hover:bg-[#25232F] transition">
                        <td class="px-5 py-4 font-bold">${escapar(desenvolvedora.nome)}</td>
                        <td class="px-5 py-4 text-right whitespace-nowrap">
                            <button type="button" data-editar-desenvolvedora="${desenvolvedora.id}"
                                class="text-[10px] font-black uppercase tracking-widest text-white/60 hover:text-[#6B5B9E] transition">Editar</button>
                            <button type="button" data-remover-desenvolvedora="${desenvolvedora.id}"
                                class="ml-4 text-[10px] font-black uppercase tracking-widest text-white/60 hover:text-red-400 transition">Excluir</button>
                        </td>
                    </tr>`;
                $('#tabela-desenvolvedoras').append(linha);
            }

            function atualizarLinha(desenvolvedora) {
                const botao = $('#tabela-desenvolvedoras').find('[data-editar-desenvolvedora="' + desenvolvedora
                    .id + '"]');
                const linha = botao.closest('tr');
                linha.find('td').eq(0).text(desenvolvedora.nome);
            }

            // ---------- modal (abrir / fechar) ----------
            $('#btn-nova-desenvolvedora').on('click', abrirModalNovo);
            $('[data-fechar-modal]').on('click', fecharModal);

            // ---------- abrir modal em modo edição ----------
            $('#tabela-desenvolvedoras').on('click', '[data-editar-desenvolvedora]', function() {
                const id = $(this).attr('data-editar-desenvolvedora');
                const nome = $(this).closest('tr').find('td').eq(0).text().trim();

                $('#desenvolvedora_id').val(id);
                $('#nome').val(nome);
                $('#modal-titulo').text('Editar Desenvolvedora');
                $('#erros').addClass('hidden').empty();
                $('#mensagem').addClass('hidden').empty();
                modal.removeClass('hidden');
                $('#nome').trigger('focus');
            });

            // ---------- incluir / editar ----------
            const urlEditarBase = "{{ route('catalogo.desenvolvedora.editar', ['id' => 'ID_PLACEHOLDER']) }}";

            $('#form-desenvolvedora').on('submit', function(e) {
                e.preventDefault();
                $('#erros').addClass('hidden').empty();
                $('#mensagem').addClass('hidden').empty();

                const id = $('#desenvolvedora_id').val();

                const url = id ?
                    urlEditarBase.replace('ID_PLACEHOLDER', id) // tem id → editar
                    :
                    "{{ route('catalogo.desenvolvedora.criar') }}"; // sem id → criar

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
                            atualizarLinha(response.desenvolvedora);
                        } else {
                            adicionarLinha(response.desenvolvedora);
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
            const urlRemoverBase = "{{ route('catalogo.desenvolvedora.remover', ['id' => 'ID_PLACEHOLDER']) }}";

            $('#tabela-desenvolvedoras').on('click', '[data-remover-desenvolvedora]', function() {
                const id = $(this).attr('data-remover-desenvolvedora');
                const linha = $(this).closest('tr');

                if (!confirm('Remover esta desenvolvedora?')) {
                    return;
                }

                $('#mensagem').addClass('hidden').empty();

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
                        if ($('#tabela-desenvolvedoras tr').length === 0) {
                            $('#tabela-desenvolvedoras').html(
                                '<tr id="linha-vazia"><td colspan="2" class="px-5 py-12 text-center text-white/30 text-xs uppercase tracking-widest">Nenhuma desenvolvedora cadastrada</td></tr>'
                            );
                        }
                        mostrarSucesso(response.mensagem);
                    },
                    error: function(xhr) {
                        mostrarErroMensagem(xhr.responseJSON?.message || 'Erro ao remover.');
                    }
                });
            });

        });
    </script>






</body>

</html>
