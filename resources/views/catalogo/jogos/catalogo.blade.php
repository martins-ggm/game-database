<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo — Game Database</title>
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

    <x-navbar />

    @php
        // Termo que veio na URL (?nome=): mantém a tela coerente num F5 ou num link colado.
        $termoBusca = trim((string) request('nome'));
    @endphp

    <main class="flex-1">

        {{-- cabeçalho + busca --}}
        <section class="w-full px-6 sm:px-12 pt-12 pb-8">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-black tracking-tight uppercase leading-[0.9]">
                Catálogo
            </h1>

            {{-- busca com debounce: filtra a própria grade (jQuery cuida) --}}
            <form id="form-busca" class="mt-6 max-w-2xl">
                <input type="search" id="busca-catalogo" name="nome" autocomplete="off" value="{{ $termoBusca }}"
                    placeholder="Buscar pelo nome do jogo..."
                    class="w-full px-4 py-3 bg-[#1C1B26] border border-white/10 text-white placeholder-white/30 focus:outline-none focus:border-[#6B5B9E] transition">
            </form>

            <p id="resumo-catalogo" class="text-sm text-white/50 mt-4">
                @if ($termoBusca !== '')
                    {{ $jogos->total() }} {{ $jogos->total() === 1 ? 'resultado' : 'resultados' }}
                    para &ldquo;{{ $termoBusca }}&rdquo;
                @else
                    Todos os jogos, ordenados pelos mais avaliados.
                    <span class="text-white/30">·</span>
                    {{ $jogos->total() }} {{ $jogos->total() === 1 ? 'jogo' : 'jogos' }}
                @endif
            </p>
        </section>

        {{-- filtro de gêneros (só front por enquanto — o backend do filtro é implementado depois) --}}
        @if ($generos->isNotEmpty())
            <div class="border-y border-white/10">
                <div class="w-full px-6 sm:px-12 py-4 flex items-center gap-4 flex-wrap">
                    <span class="text-[10px] font-black tracking-widest uppercase text-white/40">Filtrar por gênero</span>

                    <form method="GET" action="{{ route('catalogo.jogos') }}" class="relative" id="form-filtro">
                        {{-- gatilho do dropdown --}}
                        <button type="button" id="btn-filtro"
                            class="flex items-center gap-2 px-4 py-2 text-[11px] font-black tracking-widest uppercase border border-white/15 text-white/70 hover:border-[#6B5B9E] hover:text-white transition">
                            Selecionar gêneros
                            <span id="contador-filtro"
                                class="hidden min-w-[18px] px-1.5 py-0.5 text-[10px] leading-none bg-[#6B5B9E] text-white">0</span>
                            <svg class="w-3 h-3" viewBox="0 0 12 12" fill="none">
                                <path d="M2 4l4 4 4-4" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>

                        {{-- painel de opções --}}
                        <div id="painel-filtro"
                            class="hidden absolute z-30 mt-2 left-0 w-72 sm:w-80 bg-[#1C1B26] border border-white/15 shadow-2xl">
                            <div class="p-3 max-h-64 overflow-y-auto flex flex-wrap gap-1.5">
                                @foreach ($generos as $genero)
                                    <label class="cursor-pointer">
                                        <input type="checkbox" name="generos[]" value="{{ $genero->id }}"
                                            class="peer sr-only"
                                            @checked(in_array((string) $genero->id, (array) request('generos', [])))>
                                        <span
                                            class="inline-block px-3 py-1.5 text-[11px] font-bold tracking-widest uppercase border border-white/15 text-white/60 peer-checked:bg-[#6B5B9E] peer-checked:border-[#6B5B9E] peer-checked:text-white hover:border-white/40 transition">
                                            {{ $genero->nome }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            <div class="flex items-center justify-between gap-2 border-t border-white/10 p-3">
                                <button type="button" id="limpar-filtro"
                                    class="text-[10px] font-black tracking-widest uppercase text-white/40 hover:text-white transition">
                                    Limpar
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 text-[10px] font-black tracking-widest uppercase bg-[#6B5B9E] hover:bg-[#7A6BB0] text-white transition">
                                    Aplicar filtros
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- grade de jogos (página 1 no servidor; busca e demais páginas via AJAX) --}}
        <section class="w-full px-6 sm:px-12 py-10">

            {{-- sem `hidden` aqui de propósito: `hidden` e `grid` são ambos display e
                 brigariam pela ordem do CSS. Grade vazia já ocupa zero altura. --}}
            <div id="grid-jogos"
                class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-[repeat(auto-fill,minmax(180px,1fr))] gap-1 transition-opacity duration-150">
                @foreach ($jogos as $jogo)
                    <a href="{{ route('catalogo.jogo.visualizar', $jogo->id) }}"
                        class="group bg-[#1C1B26] hover:bg-[#25232F] transition flex flex-col">
                        <div class="aspect-[3/4] bg-[#11101A] overflow-hidden border-b border-white/5">
                            @if ($jogo->capa(false))
                                <img src="{{ $jogo->capa(false) }}" alt="Capa de {{ $jogo->nome }}" loading="lazy"
                                    decoding="async"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <span class="text-white/15 text-[10px] tracking-widest uppercase">Sem capa</span>
                                </div>
                            @endif
                        </div>
                        <div class="p-2 flex-1 flex flex-col">
                            <h3 class="text-xs font-bold leading-snug line-clamp-2">{{ $jogo->nome }}</h3>
                            <span class="text-[10px] text-white/40 mt-auto pt-1.5">
                                {{ $jogo->lancamento?->format('Y') ?? '—' }}
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- estado vazio (o JS alterna entre este e a grade) --}}
            <div id="estado-vazio" class="py-24 text-center @if ($jogos->total() > 0) hidden @endif">
                <p id="vazio-titulo" class="text-white/30 text-sm tracking-widest uppercase font-bold">
                    {{ $termoBusca !== '' ? 'Nenhum jogo encontrado' : 'Nenhum jogo em alta no período' }}
                </p>
                <p id="vazio-detalhe" class="text-white/20 text-xs mt-2">
                    {{ $termoBusca !== ''
                        ? 'Tente outro termo — a busca procura pelo nome do jogo.'
                        : 'Ainda não há reviews recentes o suficiente pra montar o ranking.' }}
                </p>
            </div>

            {{-- controles de paginação (renderizados pelo JS a partir do meta inicial) --}}
            <div id="paginacao" class="flex flex-wrap items-center justify-center gap-2 mt-10"></div>
        </section>

    </main>

    {{-- footer --}}
    <x-footer />

    {{-- dropdown do filtro: vanilla JS (a tela é pública, não depende do jQuery do @auth) --}}
    <script>
        (function () {
            const btn = document.getElementById('btn-filtro');
            const painel = document.getElementById('painel-filtro');
            const contador = document.getElementById('contador-filtro');
            const limpar = document.getElementById('limpar-filtro');

            if (!btn) return; // não há gêneros = sem filtro na tela

            const checks = () => Array.from(document.querySelectorAll('input[name="generos[]"]'));

            function atualizarContador() {
                const n = checks().filter(c => c.checked).length;
                if (n > 0) {
                    contador.textContent = n;
                    contador.classList.remove('hidden');
                } else {
                    contador.classList.add('hidden');
                }
            }

            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                painel.classList.toggle('hidden');
            });

            painel.addEventListener('click', e => e.stopPropagation());
            document.addEventListener('click', () => painel.classList.add('hidden'));

            limpar.addEventListener('click', function () {
                checks().forEach(c => c.checked = false);
                atualizarContador();
            });

            document.addEventListener('change', function (e) {
                if (e.target.matches('input[name="generos[]"]')) atualizarContador();
            });

            atualizarContador(); // reflete o que já veio marcado da request
        })();
    </script>

    {{-- jQuery: o catálogo é público e o navbar só carrega o jQuery pra quem está logado (@auth).
         Então garante o jQuery aqui pro visitante também — sem carregar 2x pra quem já tem. --}}
    <script>window.jQuery || document.write('<script src="https://code.jquery.com/jquery-3.7.1.min.js"><\/script>');</script>

    {{-- busca com debounce + paginação AJAX: um bloco só, porque a paginação
         precisa saber qual termo está ativo pra pedir a página certa. --}}
    <script>
        $(function () {

            @php
                $metaInicial = [
                    'current_page' => $jogos->currentPage(),
                    'last_page' => $jogos->lastPage(),
                    'total' => $jogos->total(),
                ];
            @endphp

            const urlBase        = @json(route('catalogo.jogos'));
            const rotaVisualizar = @json(route('catalogo.jogo.visualizar', ['id' => '__ID__']));
            const metaInicial    = @json($metaInicial);

            const $grid      = $('#grid-jogos');
            const $paginacao = $('#paginacao');
            const $resumo    = $('#resumo-catalogo');
            const $vazio     = $('#estado-vazio');
            const $input     = $('#busca-catalogo');

            const JANELA = 5;     // quantos números de página aparecem por vez
            const DEBOUNCE = 300; // ms — mesmo tempo da busca do dashboard

            let termo = @json($termoBusca); // termo que a grade está mostrando agora
            let meta = metaInicial;
            let timer = null;
            let req = null;

            function escapar(texto) {
                return $('<div>').text(texto ?? '').html();
            }

            // ---------- grade ----------

            function cardHtml(jogo) {
                const ano = jogo.lancamento ? jogo.lancamento.slice(0, 4) : '—';
                const href = rotaVisualizar.replace('__ID__', jogo.id);
                const capa = jogo.imagem_pequena
                    ? `<img src="${jogo.imagem_pequena}" alt="Capa de ${escapar(jogo.nome)}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">`
                    : `<div class="w-full h-full flex items-center justify-center"><span class="text-white/15 text-[10px] tracking-widest uppercase">Sem capa</span></div>`;

                return `<a href="${href}" class="group bg-[#1C1B26] hover:bg-[#25232F] transition flex flex-col">
                    <div class="aspect-[3/4] bg-[#11101A] overflow-hidden border-b border-white/5">${capa}</div>
                    <div class="p-2 flex-1 flex flex-col">
                        <h3 class="text-xs font-bold leading-snug line-clamp-2">${escapar(jogo.nome)}</h3>
                        <span class="text-[10px] text-white/40 mt-auto pt-1.5">${ano}</span>
                    </div>
                </a>`;
            }

            // ---------- paginação ----------

            function botao(pagina, rotulo, titulo) {
                return `<button type="button" data-pagina="${pagina}" title="${titulo ?? ('Página ' + pagina)}"
                    class="min-w-[38px] px-3 py-2 text-[11px] font-black tracking-widest uppercase border border-white/15 text-white/70 hover:border-[#6B5B9E] hover:text-white transition">${rotulo}</button>`;
            }

            function botaoAtual(pagina) {
                return `<span aria-current="page"
                    class="min-w-[38px] px-3 py-2 text-[11px] font-black tracking-widest uppercase text-center bg-[#6B5B9E] border border-[#6B5B9E] text-black">${pagina}</span>`;
            }

            function botaoInativo(rotulo) {
                return `<span
                    class="min-w-[38px] px-3 py-2 text-[11px] font-black tracking-widest uppercase text-center border border-white/5 text-white/20 cursor-not-allowed">${rotulo}</span>`;
            }

            function controlesHtml(meta) {
                if (meta.last_page <= 1) return '';

                const atual = meta.current_page;
                const ultima = meta.last_page;

                // A janela começa na página atual e anda junto com ela (1,2,3,4,5 → 2,3,4,5,6).
                // O Math.min segura a janela no fim, pra ela nunca encolher pra menos de 5 números.
                const inicio = Math.max(1, Math.min(atual, ultima - JANELA + 1));
                const fim = Math.min(ultima, inicio + JANELA - 1);

                let numeros = '';
                for (let pagina = inicio; pagina <= fim; pagina++) {
                    numeros += (pagina === atual) ? botaoAtual(pagina) : botao(pagina, pagina);
                }

                const primeira = atual > 1 ? botao(1, '«', 'Primeira página') : botaoInativo('«');
                const anterior = atual > 1 ? botao(atual - 1, '‹', 'Anterior') : botaoInativo('‹');
                const proxima = atual < ultima ? botao(atual + 1, '›', 'Próxima') : botaoInativo('›');
                const fimBotao = atual < ultima ? botao(ultima, '»', 'Última página') : botaoInativo('»');

                return `${primeira}${anterior}${numeros}${proxima}${fimBotao}
                    <span class="flex items-center gap-2 ml-2 sm:ml-6">
                        <label for="ir-pagina" class="text-[10px] font-black tracking-widest uppercase text-white/40">Ir para</label>
                        <input type="number" id="ir-pagina" min="1" max="${ultima}" placeholder="${atual}"
                            class="w-16 px-2 py-2 bg-[#1C1B26] border border-white/10 text-white text-[11px] text-center focus:outline-none focus:border-[#6B5B9E] transition">
                        <span class="text-[10px] font-black tracking-widest uppercase text-white/40">de ${ultima}</span>
                        <button type="button" id="btn-ir"
                            class="px-4 py-2 text-[11px] font-black tracking-widest uppercase bg-[#6B5B9E] text-black hover:bg-[#8674B8] transition">Ir</button>
                    </span>`;
            }

            // ---------- render ----------

            function resumoHtml(meta) {
                if (termo !== '') {
                    return `${meta.total} ${meta.total === 1 ? 'resultado' : 'resultados'} para &ldquo;${escapar(termo)}&rdquo;`;
                }

                return `Todos os jogos, ordenados pelos mais avaliados.
                    <span class="text-white/30">·</span>
                    ${meta.total} ${meta.total === 1 ? 'jogo' : 'jogos'}`;
            }

            function render(dados) {
                meta = dados;

                $resumo.html(resumoHtml(meta));

                if (meta.total === 0) {
                    $grid.empty();
                    $paginacao.empty();
                    $vazio.removeClass('hidden');
                    $('#vazio-titulo').text(termo !== '' ? 'Nenhum jogo encontrado' : 'Nenhum jogo em alta no período');
                    $('#vazio-detalhe').text(termo !== ''
                        ? 'Tente outro termo — a busca procura pelo nome do jogo.'
                        : 'Ainda não há reviews recentes o suficiente pra montar o ranking.');
                    return;
                }

                $vazio.addClass('hidden');
                $grid.html(meta.data.map(cardHtml).join(''));
                $paginacao.html(controlesHtml(meta));
            }

            // ---------- carga ----------

            function carregar(pagina, rolar) {
                if (req) req.abort();
                $grid.css('opacity', '0.4');

                const dados = { page: pagina };
                if (termo !== '') dados.nome = termo;

                req = $.ajax({
                    url: urlBase,
                    method: 'GET',
                    data: dados,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    success: function (resposta) {
                        render(resposta.jogos);
                        $grid.css('opacity', '1');
                        if (rolar) window.scrollTo({ top: 0, behavior: 'smooth' });
                    },
                    error: function (xhr, status) {
                        if (status === 'abort') return; // outra requisição assumiu o lugar
                        $grid.css('opacity', '1');
                    }
                });
            }

            // ---------- eventos ----------

            $input.on('input', function () {
                const valor = $(this).val().trim();
                clearTimeout(timer);

                // 1 caractere é ruído (e um ILIKE '%a%' varre o catálogo inteiro).
                // Campo vazio conta: é o gesto de "limpar a busca" e volta pro em alta.
                if (valor.length === 1) return;

                timer = setTimeout(function () {
                    if (valor === termo) return;
                    termo = valor;
                    carregar(1, false); // sem rolar: o usuário está digitando aqui em cima
                }, DEBOUNCE);
            });

            // a busca é ao vivo — Enter não pode recarregar a página
            $('#form-busca').on('submit', function (e) {
                e.preventDefault();
            });

            $paginacao.on('click', '[data-pagina]', function () {
                carregar(Number($(this).data('pagina')), true);
            });

            function irParaDigitada() {
                const pagina = parseInt($paginacao.find('#ir-pagina').val(), 10);

                if (!pagina || pagina < 1 || pagina > meta.last_page || pagina === meta.current_page) return;

                carregar(pagina, true);
            }

            $paginacao.on('click', '#btn-ir', irParaDigitada);
            $paginacao.on('keydown', '#ir-pagina', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    irParaDigitada();
                }
            });

            // controles iniciais a partir do meta da página 1 (já renderizada pelo servidor)
            $paginacao.html(controlesHtml(metaInicial));
        });
    </script>

</body>

</html>
