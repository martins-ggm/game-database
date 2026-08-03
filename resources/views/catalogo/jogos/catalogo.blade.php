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
        html {
            scroll-behavior: smooth;
            /* desconta a altura do navbar sticky quando pula pra uma âncora de categoria */
            scroll-padding-top: 6rem;
        }

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
            <p class="text-[10px] font-black tracking-widest uppercase text-white/40 mb-3">Explorar</p>
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-black tracking-tight uppercase leading-[0.9]">
                Catálogo
            </h1>
            <p class="text-sm text-white/50 mt-4">
                Os jogos do sistema, organizados por categoria.
                <span class="text-white/30">·</span>
                {{ $generos->count() }} {{ $generos->count() === 1 ? 'categoria' : 'categorias' }}
            </p>
        </section>

        {{-- atalhos de categoria --}}
        @if ($generos->isNotEmpty())
            <div class="border-y border-white/10">
                <div class="max-w-[1600px] mx-auto w-full px-6 sm:px-12 py-4 flex gap-1 overflow-x-auto">
                    @foreach ($generos as $genero)
                        <a href="#genero-{{ $genero->id }}"
                            class="flex-shrink-0 px-4 py-2 text-[10px] font-black tracking-widest uppercase border border-white/15 text-white/60 hover:border-[#6B5B9E] hover:text-white transition">
                            {{ $genero->nome }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- uma linha (scroll horizontal) por categoria --}}
        @forelse ($generos as $genero)
            <section id="genero-{{ $genero->id }}" class="border-b border-white/10">
                <div class="max-w-[1600px] mx-auto w-full px-6 sm:px-12 py-10">
                    <div class="flex items-center justify-between mb-6">
                        <h2
                            class="text-xl sm:text-2xl font-black tracking-widest uppercase border-l-4 border-[#6B5B9E] pl-4">
                            {{ $genero->nome }}
                        </h2>
                        <span class="text-[10px] font-black tracking-widest uppercase text-white/40">
                            {{ $genero->jogos->count() }} {{ $genero->jogos->count() === 1 ? 'jogo' : 'jogos' }}
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
                    <p class="text-white/30 text-sm tracking-widest uppercase font-bold">Nenhum jogo cadastrado ainda</p>
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

</body>

</html>
