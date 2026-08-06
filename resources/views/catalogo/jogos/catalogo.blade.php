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

    <main class="flex-1">

        {{-- cabeçalho --}}
        <section class="w-full px-6 sm:px-12 pt-12 pb-8">
            <p class="text-[10px] font-black tracking-widest uppercase text-[#8B7BB8] mb-3">🔥 Em alta</p>
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-black tracking-tight uppercase leading-[0.9]">
                Catálogo
            </h1>
            <p class="text-sm text-white/50 mt-4">
                Todos os jogos, ordenados pelos mais avaliados.
                <span class="text-white/30">·</span>
                {{ $jogos->total() }} {{ $jogos->total() === 1 ? 'jogo' : 'jogos' }}
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

        {{-- grade de jogos (página 1 no servidor; próximas páginas via AJAX) --}}
        <section class="w-full px-6 sm:px-12 py-10">
            @if ($jogos->total() > 0)
                <div id="grid-jogos" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-[repeat(auto-fill,minmax(180px,1fr))] gap-1">
                    @foreach ($jogos as $jogo)
                        <a href="{{ route('catalogo.jogo.visualizar', $jogo->id) }}"
                            class="group bg-[#1C1B26] hover:bg-[#25232F] transition flex flex-col">
                            <div class="aspect-[3/4] bg-[#11101A] overflow-hidden border-b border-white/5">
                                @if ($jogo->url_imagem_pequena)
                                    <img src="{{ Storage::url($jogo->url_imagem_pequena) }}"
                                        alt="Capa de {{ $jogo->nome }}" loading="lazy" decoding="async"
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

                {{-- controles de paginação (renderizados pelo JS a partir do meta inicial) --}}
                <div id="paginacao" class="flex items-center justify-center gap-4 mt-10"></div>
            @else
                <div class="py-24 text-center">
                    <p class="text-white/30 text-sm tracking-widest uppercase font-bold">
                        Nenhum jogo em alta no período
                    </p>
                    <p class="text-white/20 text-xs mt-2">
                        Ainda não há reviews recentes o suficiente pra montar o ranking.
                    </p>
                </div>
            @endif
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

    {{-- paginação AJAX da grade de jogos --}}
    <script>
        (function () {
            const grid = document.getElementById('grid-jogos');
            const paginacao = document.getElementById('paginacao');
            if (!grid || !paginacao) return; // catálogo vazio: sem grade pra paginar

            @php
                $rotaVisualizar = route('catalogo.jogo.visualizar', ['id' => '__ID__']);
                $metaInicial = [
                    'current_page' => $jogos->currentPage(),
                    'last_page'    => $jogos->lastPage(),
                    'total'        => $jogos->total(),
                ];
            @endphp

            const urlBase = @json(route('catalogo.jogos'));
            const rotaVisualizar = @json($rotaVisualizar);

            // meta da página 1, já renderizada pelo servidor
            const metaInicial = @json($metaInicial);

            function escapar(texto) {
                const div = document.createElement('div');
                div.textContent = texto ?? '';
                return div.innerHTML;
            }

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

            function botao(pagina, rotulo) {
                return `<button type="button" data-pagina="${pagina}" class="px-4 py-2 text-[11px] font-black tracking-widest uppercase border border-white/15 text-white/70 hover:border-[#6B5B9E] hover:text-white transition">${rotulo}</button>`;
            }

            function botaoInativo(rotulo) {
                return `<span class="px-4 py-2 text-[11px] font-black tracking-widest uppercase border border-white/5 text-white/20 cursor-not-allowed">${rotulo}</span>`;
            }

            function controlesHtml(meta) {
                if (meta.last_page <= 1) return '';
                const anterior = meta.current_page > 1 ? botao(meta.current_page - 1, 'Anterior') : botaoInativo('Anterior');
                const proxima = meta.current_page < meta.last_page ? botao(meta.current_page + 1, 'Próxima') : botaoInativo('Próxima');
                return `${anterior}
                    <span class="text-[11px] font-black tracking-widest uppercase text-white/40">Página ${meta.current_page} de ${meta.last_page}</span>
                    ${proxima}`;
            }

            async function carregar(pagina) {
                grid.style.opacity = '0.4';
                try {
                    const url = new URL(urlBase, window.location.origin);
                    url.searchParams.set('page', pagina);

                    const resp = await fetch(url, { headers: { 'Accept': 'application/json' } });
                    if (!resp.ok) return;

                    const meta = (await resp.json()).jogos;
                    grid.innerHTML = meta.data.map(cardHtml).join('');
                    paginacao.innerHTML = controlesHtml(meta);

                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } finally {
                    grid.style.opacity = '1';
                }
            }

            paginacao.addEventListener('click', function (e) {
                const btn = e.target.closest('[data-pagina]');
                if (btn) carregar(btn.dataset.pagina);
            });

            // controles iniciais a partir do meta da página 1
            paginacao.innerHTML = controlesHtml(metaInicial);
        })();
    </script>

</body>

</html>
