<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogos — Game Database</title>
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
                Jogos
            </h1>
            <button type="button" id="btn-novo-jogo"
                class="self-start sm:self-auto px-6 py-3 bg-[#6B5B9E] text-black font-black tracking-widest uppercase text-xs hover:bg-[#8674B8] transition">
                + Novo Jogo
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
                        <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-white/40">Capa</th>
                        <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-white/40">Nome</th>
                        <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-white/40">
                            Lançamento</th>
                        <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-white/40">
                            Desenvolvedora</th>
                        <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-white/40">Plataformas
                        </th>
                        <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-white/40">Gêneros
                        </th>
                        <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-white/40 text-right">
                            Ações</th>
                    </tr>
                </thead>
                <tbody id="tabela-jogos">
                    @forelse ($jogos as $jogo)
                        <tr class="border-b border-white/5 hover:bg-[#25232F] transition">
                            <td class="px-5 py-4">
                                @if ($jogo->capa(false))
                                    <img src="{{ $jogo->capa(false) }}" alt=""
                                        loading="lazy" decoding="async"
                                        class="w-12 h-16 object-cover border border-white/10">
                                @else
                                    <div class="w-12 h-16 bg-[#11101A] border border-white/10 flex items-center justify-center text-white/20 text-xs">—</div>
                                @endif
                            </td>
                            <td class="px-5 py-4 font-bold">{{ $jogo->nome }}</td>
                            <td class="px-5 py-4 text-white/70 whitespace-nowrap">{{ $jogo->lancamento?->format('d/m/Y') ?? '—' }}</td>
                            <td class="px-5 py-4 text-white/70">{{ $jogo->desenvolvedora?->nome ?? '—' }}</td>
                            <td class="px-5 py-4 text-white/70">
                                {{ $jogo->plataformas->pluck('nome')->implode(', ') ?: '—' }}</td>
                            <td class="px-5 py-4 text-white/70">
                                {{ $jogo->generos->pluck('nome')->implode(', ') ?: '—' }}</td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <button type="button" data-editar-jogo="{{ $jogo->id }}"
                                    data-desenvolvedora="{{ $jogo->desenvolvedora?->id }}"
                                    data-lancamento="{{ $jogo->lancamento?->format('Y-m-d') }}"
                                    data-plataformas="{{ $jogo->plataformas->pluck('id')->implode(',') }}"
                                    data-generos="{{ $jogo->generos->pluck('id')->implode(',') }}"
                                    data-imagem="{{ $jogo->capa(false) ?? '' }}"
                                    data-descricao="{{ $jogo->descricao }}"
                                    class="text-[10px] font-black uppercase tracking-widest text-white/60 hover:text-[#6B5B9E] transition">
                                    Editar
                                </button>
                                <button type="button" data-remover-jogo="{{ $jogo->id }}"
                                    class="ml-4 text-[10px] font-black uppercase tracking-widest text-white/60 hover:text-red-400 transition">
                                    Excluir
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7"
                                class="px-5 py-12 text-center text-white/30 text-xs uppercase tracking-widest">
                                Nenhum jogo cadastrado
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- paginação (jQuery preenche) --}}
        <div id="paginacao" class="mt-4"></div>
    </main>

    {{-- footer --}}
    <x-footer />

    {{-- ============ MODAL (novo / editar) ============ --}}
    <div id="modal-jogo" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        {{-- backdrop --}}
        <div class="absolute inset-0 bg-black/70" data-fechar-modal></div>

        {{-- caixa --}}
        <div class="relative w-full max-w-lg bg-[#1C1B26] border border-white/10 max-h-[90vh] overflow-y-auto">

            {{-- header do modal --}}
            <div class="flex items-center justify-between px-6 py-5 border-b border-white/10">
                <h2 id="modal-titulo"
                    class="text-lg font-black tracking-widest uppercase border-l-4 border-[#6B5B9E] pl-3">
                    Novo Jogo
                </h2>
                <button type="button" data-fechar-modal
                    class="text-white/40 hover:text-white text-2xl leading-none">&times;</button>
            </div>

            {{-- erros de validação (jQuery preenche) --}}
            <ul id="erros"
                class="hidden mx-6 mt-4 p-3 bg-red-500/10 border border-red-500/30 text-sm text-red-300 list-disc list-inside space-y-1">
            </ul>

            {{-- form --}}
            <form id="form-jogo" class="p-6 space-y-5">
                <input type="hidden" id="jogo_id" name="id">

                <div>
                    <label for="nome"
                        class="block text-[10px] font-black uppercase tracking-widest text-white/60 mb-2">Nome</label>
                    <input type="text" id="nome" name="nome" placeholder="Nome do jogo"
                        class="w-full px-4 py-3 bg-[#11101A] border border-white/10 text-white placeholder-white/30 focus:outline-none focus:border-[#6B5B9E] transition">
                </div>

                <div>
                    <label for="lancamento"
                        class="block text-[10px] font-black uppercase tracking-widest text-white/60 mb-2">Lançamento</label>
                    <input type="date" id="lancamento" name="lancamento"
                        class="w-full px-4 py-3 bg-[#11101A] border border-white/10 text-white [color-scheme:dark] focus:outline-none focus:border-[#6B5B9E] transition">
                </div>

                <div>
                    <label for="desenvolvedora_id"
                        class="block text-[10px] font-black uppercase tracking-widest text-white/60 mb-2">Desenvolvedora</label>
                    <select id="desenvolvedora_id" name="desenvolvedora_id"
                        class="w-full px-4 py-3 bg-[#11101A] border border-white/10 text-white focus:outline-none focus:border-[#6B5B9E] transition">
                        <option value="">— Nenhuma —</option>
                        @foreach ($desenvolvedoras ?? [] as $desenvolvedora)
                            <option value="{{ $desenvolvedora->id }}">{{ $desenvolvedora->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <span
                        class="block text-[10px] font-black uppercase tracking-widest text-white/60 mb-2">Plataformas</span>
                    <div
                        class="grid grid-cols-2 gap-2 max-h-40 overflow-y-auto p-3 bg-[#11101A] border border-white/10">
                        @forelse ($plataformas ?? [] as $plataforma)
                            <label class="flex items-center gap-2 cursor-pointer select-none text-sm text-white/80">
                                <input type="checkbox" name="plataformas[]" value="{{ $plataforma->id }}"
                                    class="w-4 h-4 bg-[#1C1B26] border border-white/20 accent-[#6B5B9E] cursor-pointer">
                                {{ $plataforma->nome }}
                            </label>
                        @empty
                            <span class="col-span-2 text-xs text-white/30 uppercase tracking-widest">Nenhuma plataforma
                                cadastrada</span>
                        @endforelse
                    </div>
                </div>

                <div>
                    <span
                        class="block text-[10px] font-black uppercase tracking-widest text-white/60 mb-2">Gêneros</span>
                    <div
                        class="grid grid-cols-2 gap-2 max-h-40 overflow-y-auto p-3 bg-[#11101A] border border-white/10">
                        @forelse ($generos ?? [] as $genero)
                            <label class="flex items-center gap-2 cursor-pointer select-none text-sm text-white/80">
                                <input type="checkbox" name="generos[]" value="{{ $genero->id }}"
                                    class="w-4 h-4 bg-[#1C1B26] border border-white/20 accent-[#6B5B9E] cursor-pointer">
                                {{ $genero->nome }}
                            </label>
                        @empty
                            <span class="col-span-2 text-xs text-white/30 uppercase tracking-widest">Nenhum gênero
                                cadastrado</span>
                        @endforelse
                    </div>
                </div>

                {{-- upload de capa --}}
                <div>
                    <label for="imagem"
                        class="block text-[10px] font-black uppercase tracking-widest text-white/60 mb-2">Capa</label>
                    <input type="file" id="imagem" name="imagem" accept="image/jpeg,image/png,image/webp"
                        class="w-full text-sm text-white/70 bg-[#11101A] border border-white/10 p-2 file:mr-4 file:py-2 file:px-4 file:border-0 file:bg-[#6B5B9E] file:text-black file:font-black file:uppercase file:text-xs file:tracking-widest file:cursor-pointer hover:file:bg-[#8674B8]">
                    <img id="preview-imagem" src="" alt=""
                        class="hidden mt-3 w-36 h-48 object-cover border border-white/10">
                </div>

                {{-- descrição --}}
                <div>
                    <label for="descricao"
                        class="block text-[10px] font-black uppercase tracking-widest text-white/60 mb-2">Descrição</label>
                    <textarea id="descricao" name="descricao" rows="4" placeholder="Sinopse do jogo..."
                        class="w-full px-4 py-3 bg-[#11101A] border border-white/10 text-white placeholder-white/30 focus:outline-none focus:border-[#6B5B9E] transition resize-y"></textarea>
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
                <p class="text-[10px] text-white/40 uppercase tracking-widest pt-3">Esta ação não pode ser desfeita.
                </p>
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

            const modal = $('#modal-jogo');

            // ---------- helpers ----------
            function escapar(texto) {
                return $('<div>').text(texto ?? '').html().replace(/"/g, '&quot;');
            }

            // coleta os ids marcados de um grupo de checkboxes → [1, 5, 12]
            function idsMarcados(name) {
                return $('input[name="' + name + '"]:checked').map(function() {
                    return parseInt($(this).val(), 10);
                }).get();
            }

            function limparCheckboxes() {
                $('input[name="plataformas[]"], input[name="generos[]"]').prop('checked', false);
            }

            function limparPreview() {
                $('#preview-imagem').addClass('hidden').attr('src', '');
            }

            function abrirModalNovo() {
                $('#form-jogo')[0].reset();
                limparCheckboxes();
                limparPreview();
                $('#jogo_id').val('');
                $('#modal-titulo').text('Novo Jogo');
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

            // ---------- render da linha ----------
            function listaNomes(itens) {
                if (!itens || itens.length === 0) return '—';
                return itens.map(function(i) {
                    return escapar(i.nome);
                }).join(', ');
            }

            // "2006-11-19" → "19/11/2006" (split simples, sem new Date pra não pegar fuso)
            function formatarData(iso) {
                if (!iso) return '—';
                const [ano, mes, dia] = iso.split('-');
                return `${dia}/${mes}/${ano}`;
            }

            function capaHtml(jogo) {
                return jogo.imagem_pequena
                    ? `<img src="${jogo.imagem_pequena}" alt="" loading="lazy" decoding="async" class="w-12 h-16 object-cover border border-white/10">`
                    : `<div class="w-12 h-16 bg-[#11101A] border border-white/10 flex items-center justify-center text-white/20 text-xs">—</div>`;
            }

            function linhaHtml(jogo) {
                const dev = jogo.desenvolvedora ? escapar(jogo.desenvolvedora.nome) : '—';
                const devId = jogo.desenvolvedora ? jogo.desenvolvedora.id : '';
                const platIds = jogo.plataformas.map(function(p) {
                    return p.id;
                }).join(',');
                const genIds = jogo.generos.map(function(g) {
                    return g.id;
                }).join(',');
                const imagem = jogo.imagem_pequena || '';

                return `
                    <tr class="border-b border-white/5 hover:bg-[#25232F] transition">
                        <td class="px-5 py-4">${capaHtml(jogo)}</td>
                        <td class="px-5 py-4 font-bold">${escapar(jogo.nome)}</td>
                        <td class="px-5 py-4 text-white/70 whitespace-nowrap">${formatarData(jogo.lancamento)}</td>
                        <td class="px-5 py-4 text-white/70">${dev}</td>
                        <td class="px-5 py-4 text-white/70">${listaNomes(jogo.plataformas)}</td>
                        <td class="px-5 py-4 text-white/70">${listaNomes(jogo.generos)}</td>
                        <td class="px-5 py-4 text-right whitespace-nowrap">
                            <button type="button" data-editar-jogo="${jogo.id}"
                                data-desenvolvedora="${devId}" data-plataformas="${platIds}" data-generos="${genIds}"
                                data-imagem="${imagem}" data-lancamento="${jogo.lancamento || ''}" data-descricao="${escapar(jogo.descricao || '')}"
                                class="text-[10px] font-black uppercase tracking-widest text-white/60 hover:text-[#6B5B9E] transition">Editar</button>
                            <button type="button" data-remover-jogo="${jogo.id}"
                                class="ml-4 text-[10px] font-black uppercase tracking-widest text-white/60 hover:text-red-400 transition">Excluir</button>
                        </td>
                    </tr>`;
            }

            function renderizarTabela(jogos) {
                const tbody = $('#tabela-jogos');
                tbody.empty();

                if (jogos.length === 0) {
                    tbody.html(
                        '<tr id="linha-vazia"><td colspan="7" class="px-5 py-12 text-center text-white/30 text-xs uppercase tracking-widest">Nenhum jogo encontrado</td></tr>'
                    );
                    return;
                }

                jogos.forEach(function(jogo) {
                    tbody.append(linhaHtml(jogo));
                });
            }

            // ---------- modal (abrir / fechar) ----------
            $('#btn-novo-jogo').on('click', abrirModalNovo);
            $('[data-fechar-modal]').on('click', fecharModal);

            // ---------- preview ao escolher arquivo ----------
            $('#imagem').on('change', function() {
                const arquivo = this.files[0];
                if (arquivo) {
                    $('#preview-imagem').attr('src', URL.createObjectURL(arquivo)).removeClass('hidden');
                }
            });

            const urlListar = "{{ route('catalogo.jogo.buscar') }}";
            let paginaAtual = 1;
            let termoBusca = '';
            let timerBusca = null;
            let reqLista = null;

            function carregar(pagina) {
                if (reqLista) reqLista.abort();

                reqLista = $.ajax({
                    url: urlListar,
                    method: 'GET',
                    data: {
                        page: pagina,
                        nome: termoBusca
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    success: function(resp) {
                        const meta = resp.jogos || resp.Jogos; // buscaPaginada envolve em { jogos: {...} }
                        // se a página esvaziou (ex.: removeu o último item dela), volta uma
                        if (meta.data.length === 0 && meta.current_page > 1) {
                            carregar(meta.current_page - 1);
                            return;
                        }
                        paginaAtual = meta.current_page;
                        renderizarTabela(meta.data);
                        renderizarPaginacao(meta);
                    },
                    error: function(xhr, status) {
                        if (status === 'abort') return;
                        mostrarErroMensagem(xhr.responseJSON?.message || 'Erro ao carregar os jogos.');
                    }
                });
            }

            function renderizarPaginacao(meta) {
                const cont = $('#paginacao');
                cont.empty();
                if (meta.total === 0) return;

                const base = 'px-3 py-2 text-[10px] font-black uppercase tracking-widest border transition';
                const ativo = 'bg-[#6B5B9E] text-black border-[#6B5B9E]';
                const inativo = 'border-white/15 text-white/60 hover:border-[#6B5B9E] hover:text-white';
                const off = 'border-white/10 text-white/20 cursor-not-allowed';

                function botao(pagina, rotulo, estado) {
                    const desabilitado = estado === 'off' ? 'disabled' : '';
                    const classe = estado === 'ativo' ? ativo : (estado === 'off' ? off : inativo);
                    return `<button type="button" data-pagina="${pagina}" ${desabilitado} class="${base} ${classe}">${rotulo}</button>`;
                }

                let botoes = botao(meta.current_page - 1, '‹', meta.current_page === 1 ? 'off' : 'on');

                // janela de páginas: atual ±2
                const inicio = Math.max(1, meta.current_page - 2);
                const fim = Math.min(meta.last_page, meta.current_page + 2);
                for (let p = inicio; p <= fim; p++) {
                    botoes += botao(p, p, p === meta.current_page ? 'ativo' : 'on');
                }

                botoes += botao(meta.current_page + 1, '›', meta.current_page === meta.last_page ? 'off' : 'on');

                const resumo =
                    `<span class="text-[10px] font-black uppercase tracking-widest text-white/40">Mostrando ${meta.from}–${meta.to} de ${meta.total}</span>`;
                cont.html(
                    `<div class="flex items-center justify-between gap-4 flex-wrap">${resumo}<div class="flex gap-1">${botoes}</div></div>`
                );
            }

            // clique nos botões de página
            $('#paginacao').on('click', '[data-pagina]', function() {
                const p = parseInt($(this).attr('data-pagina'), 10);
                if (!isNaN(p)) carregar(p);
            });

            // busca (com debounce) — sempre reinicia na página 1
            $('#busca').on('input', function() {
                const termo = $(this).val();
                clearTimeout(timerBusca);
                timerBusca = setTimeout(function() {
                    termoBusca = termo;
                    carregar(1);
                }, 300);
            });

            // estado inicial: a página 1 já veio renderizada pelo servidor (novo());
            // aqui só desenhamos os controles a partir do meta do paginator, sem re-buscar.
            paginaAtual = {{ $jogos->currentPage() }};
            renderizarPaginacao({
                current_page: {{ $jogos->currentPage() }},
                last_page: {{ $jogos->lastPage() }},
                from: {{ $jogos->firstItem() ?? 0 }},
                to: {{ $jogos->lastItem() ?? 0 }},
                total: {{ $jogos->total() }}
            });

            // ---------- abrir modal em modo edição ----------
            $('#tabela-jogos').on('click', '[data-editar-jogo]', function() {
                const botao = $(this);
                const id = botao.attr('data-editar-jogo');
                const nome = botao.closest('tr').find('td').eq(1).text().trim();
                const devId = botao.attr('data-desenvolvedora') || '';
                const platIds = (botao.attr('data-plataformas') || '').split(',').filter(Boolean);
                const genIds = (botao.attr('data-generos') || '').split(',').filter(Boolean);
                const imagem = botao.attr('data-imagem') || '';
                const lancamento = botao.attr('data-lancamento') || '';
                const descricao = botao.attr('data-descricao') || '';

                $('#form-jogo')[0].reset();
                limparCheckboxes();

                $('#jogo_id').val(id);
                $('#nome').val(nome);
                $('#lancamento').val(lancamento);
                $('#descricao').val(descricao);
                $('#desenvolvedora_id').val(devId);
                platIds.forEach(function(pid) {
                    $('input[name="plataformas[]"][value="' + pid + '"]').prop('checked', true);
                });
                genIds.forEach(function(gid) {
                    $('input[name="generos[]"][value="' + gid + '"]').prop('checked', true);
                });

                // mostra a capa atual (se tiver); o input de arquivo fica vazio
                if (imagem) {
                    $('#preview-imagem').attr('src', imagem).removeClass('hidden');
                } else {
                    limparPreview();
                }

                $('#modal-titulo').text('Editar Jogo');
                $('#erros').addClass('hidden').empty();
                $('#mensagem').addClass('hidden').empty();
                modal.removeClass('hidden');
                $('#nome').trigger('focus');
            });

            // ---------- incluir / editar ----------
            const urlEditarBase = "{{ route('catalogo.jogo.editar', ['id' => 'ID_PLACEHOLDER']) }}";

            $('#form-jogo').on('submit', function(e) {
                e.preventDefault();
                $('#erros').addClass('hidden').empty();
                $('#mensagem').addClass('hidden').empty();

                const id = $('#jogo_id').val();

                const url = id ?
                    urlEditarBase.replace('ID_PLACEHOLDER', id) // tem id → editar
                    :
                    "{{ route('catalogo.jogo.criar') }}"; // sem id → criar

                // FormData (arquivo não vai por JSON)
                const formData = new FormData();
                formData.append('nome', $('#nome').val());

                const lancamento = $('#lancamento').val();
                if (lancamento) formData.append('lancamento', lancamento);

                const descricao = $('#descricao').val();
                if (descricao) formData.append('descricao', descricao);

                const dev = $('#desenvolvedora_id').val();
                if (dev) formData.append('desenvolvedora', dev);

                idsMarcados('plataformas[]').forEach(function(pid) {
                    formData.append('plataformas[]', pid);
                });
                idsMarcados('generos[]').forEach(function(gid) {
                    formData.append('generos[]', gid);
                });

                const arquivo = $('#imagem')[0].files[0];
                if (arquivo) formData.append('imagem', arquivo); // só manda se escolheu

                if (id) formData.append('id', id);

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false, // não serializa o FormData
                    contentType: false, // deixa o browser pôr o boundary do multipart
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        fecharModal();
                        mostrarSucesso(response.mensagem);
                        carregar(paginaAtual); // recarrega a página atual pra manter a paginação consistente
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
            const urlRemoverBase = "{{ route('catalogo.jogo.remover', ['id' => 'ID_PLACEHOLDER']) }}";
            const modalRemover = $('#modal-remover');
            let removerId = null;
            let removerLinha = null;

            function fecharRemocao() {
                modalRemover.addClass('hidden');
                removerId = null;
                removerLinha = null;
            }

            $('#tabela-jogos').on('click', '[data-remover-jogo]', function() {
                removerId = $(this).attr('data-remover-jogo');
                removerLinha = $(this).closest('tr');
                const nome = removerLinha.find('td').eq(1).text().trim();

                $('#nome-remocao').text(nome);
                $('#mensagem').addClass('hidden').empty();
                modalRemover.removeClass('hidden');
            });

            $('[data-fechar-remocao]').on('click', fecharRemocao);

            $('#btn-confirmar-remocao').on('click', function() {
                if (!removerId) return;

                const id = removerId;
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
                        fecharRemocao();
                        mostrarSucesso(response.mensagem);
                        carregar(paginaAtual); // re-busca a página (o guard volta uma se esvaziou)
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
