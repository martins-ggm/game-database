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
        <section class="max-w-[1600px] mx-auto w-full px-6 sm:px-12 pt-12 pb-8">
            <p class="text-[10px] font-black tracking-widest uppercase text-[#8B7BB8] mb-3">🔥 Em alta</p>
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-black tracking-tight uppercase leading-[0.9]">
                Catálogo
            </h1>
            <p class="text-sm text-white/50 mt-4">
                Os gêneros e jogos mais avaliados no momento.
                <span class="text-white/30">·</span>
                {{ $generosEmAlta->count() }} {{ $generosEmAlta->count() === 1 ? 'gênero em alta' : 'gêneros em alta' }}
            </p>
        </section>

        {{-- filtro de gêneros (só front por enquanto — o backend do filtro é implementado depois) --}}
        @if ($generos->isNotEmpty())
            <div class="border-y border-white/10">
                <div class="max-w-[1600px] mx-auto w-full px-6 sm:px-12 py-4 flex items-center gap-4 flex-wrap">
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

        {{-- uma linha (scroll horizontal) por gênero em alta --}}
        @forelse ($generosEmAlta as $genero)
            <section class="border-b border-white/10">
                <div class="max-w-[1600px] mx-auto w-full px-6 sm:px-12 py-10">
                    <div class="flex items-center justify-between mb-6">
                        <h2
                            class="text-xl sm:text-2xl font-black tracking-widest uppercase border-l-4 border-[#6B5B9E] pl-4">
                            {{ $genero->nome }}
                        </h2>
                        <span class="text-[10px] font-black tracking-widest uppercase text-white/40">
                            {{ $genero->jogos_com_reviews_count }}
                            {{ $genero->jogos_com_reviews_count === 1 ? 'jogo em alta' : 'jogos em alta' }}
                        </span>
                    </div>

                    <div class="flex gap-1 overflow-x-auto pb-2">
                        @foreach ($genero->jogos as $jogo)
                            <a href="{{ route('catalogo.jogo.visualizar', $jogo->id) }}"
                                class="group flex-shrink-0 w-40 sm:w-44 bg-[#1C1B26] hover:bg-[#25232F] transition flex flex-col">
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
                                <div class="p-3 flex-1 flex flex-col">
                                    <h3 class="text-sm font-bold leading-snug line-clamp-2">{{ $jogo->nome }}</h3>
                                    <span class="text-[10px] text-white/40 mt-auto pt-2">
                                        {{ $jogo->lancamento?->format('Y') ?? '—' }}
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>
        @empty
            <section class="border-t border-white/10">
                <div class="max-w-[1600px] mx-auto w-full px-6 sm:px-12 py-24 text-center">
                    <p class="text-white/30 text-sm tracking-widest uppercase font-bold">
                        Nenhum jogo em alta no período
                    </p>
                    <p class="text-white/20 text-xs mt-2">
                        Ainda não há reviews recentes o suficiente pra montar o ranking.
                    </p>
                </div>
            </section>
        @endforelse

    </main>

    {{-- footer --}}
    <footer class="border-t border-white/10">
        <div
            class="max-w-[1600px] mx-auto px-6 sm:px-12 py-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-[#C6C2D9] opacity-60 hover:opacity-100 transition"
                    style="-webkit-mask: url('{{ asset('misc/espurr.svg') }}') center/contain no-repeat; mask: url('{{ asset('misc/espurr.svg') }}') center/contain no-repeat;"
                    role="img" aria-label="Espurr — mascote"></div>
                <p class="text-[10px] tracking-widest text-white/40 uppercase font-bold">&copy; 2026 Game Database</p>
            </div>
            <p class="text-[10px] tracking-widest text-white/40 uppercase font-bold">Built with the SISP architecture</p>
        </div>
    </footer>

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

            // abre/fecha o painel
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                painel.classList.toggle('hidden');
            });

            // cliques dentro do painel não fecham
            painel.addEventListener('click', e => e.stopPropagation());

            // clique fora fecha
            document.addEventListener('click', function () {
                painel.classList.add('hidden');
            });

            // limpar seleção
            limpar.addEventListener('click', function () {
                checks().forEach(c => c.checked = false);
                atualizarContador();
            });

            // mantém o contador em dia
            document.addEventListener('change', function (e) {
                if (e.target.matches('input[name="generos[]"]')) atualizarContador();
            });

            atualizarContador(); // reflete o que já veio marcado da request
        })();
    </script>

</body>

</html>
