<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil — Game Database</title>
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
        <nav class="max-w-[1600px] mx-auto px-6 sm:px-12 py-5 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ route('gerenciador.dashboard.visualizar') }}"
                    class="text-sm font-bold tracking-widest uppercase text-white/60 hover:text-[#6B5B9E] transition">HOME</a>
                <a href="{{ route('gerenciador.dashboard.visualizar') }}#catalogo"
                    class="text-sm font-bold tracking-widest uppercase text-white/60 hover:text-[#6B5B9E] transition">CATÁLOGO</a>
                <a href="#"
                    class="text-sm font-bold tracking-widest uppercase text-white/60 hover:text-[#6B5B9E] transition">LISTAS</a>
            </div>

            <a href="{{ route('gerenciador.dashboard.visualizar') }}"
                class="text-2xl sm:text-3xl font-black tracking-widest">
                GAME<span class="text-[#6B5B9E]">DB</span>
            </a>

            <div class="flex items-center gap-8">
                <a href="{{ route('gerenciador.perfil.visualizar') }}"
                    class="text-sm font-bold tracking-widest uppercase hover:text-[#6B5B9E] transition">PERFIL</a>
                <a href="#"
                    class="text-sm font-bold tracking-widest uppercase text-white/60 hover:text-[#6B5B9E] transition">SAIR</a>
            </div>
        </nav>
    </header>

    <main class="flex-1">

        {{-- seção 1: identidade do usuário --}}
        <section class="max-w-[1600px] mx-auto px-6 sm:px-12 pt-12 pb-10">
            <div class="flex flex-col sm:flex-row items-start gap-6 sm:gap-8">

                {{-- avatar --}}
                <div
                    class="w-32 h-32 sm:w-40 sm:h-40 bg-[#1C1B26] border border-white/10 flex items-center justify-center flex-shrink-0">
                    <span class="text-5xl sm:text-6xl font-black text-[#6B5B9E]">G</span>
                </div>

                {{-- nome + stats --}}
                <div class="flex-1 w-full">
                    <p class="text-[10px] font-black tracking-widest uppercase text-white/40 mb-2">Jogador</p>
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight uppercase leading-[0.95] mb-3">
                        Guilherme Martins
                    </h1>
                    <p class="text-sm text-white/60 mb-6">square.ggm@gmail.com · Membro desde 2026</p>

                    <div class="grid grid-cols-2 gap-1 max-w-md">
                        <div class="bg-[#1C1B26] py-6 text-center">
                            <div class="text-3xl sm:text-4xl font-black text-[#6B5B9E]">42</div>
                            <p class="text-[10px] uppercase tracking-widest text-white/40 mt-2 font-bold">Jogos</p>
                        </div>
                        <div class="bg-[#1C1B26] py-6 text-center">
                            <div class="text-3xl sm:text-4xl font-black text-[#6B5B9E]">17</div>
                            <p class="text-[10px] uppercase tracking-widest text-white/40 mt-2 font-bold">Reviews</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- seção 2: scroll horizontal — 10 últimos jogos --}}
        <section class="border-t border-white/10">
            <div class="max-w-[1600px] mx-auto px-6 sm:px-12 py-10">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-xl sm:text-2xl font-black tracking-widest uppercase border-l-4 border-[#6B5B9E] pl-4">
                        Últimos jogos
                    </h2>
                    <span class="text-[10px] font-black tracking-widest uppercase text-white/40">10 mais recentes</span>
                </div>

                <div class="flex gap-1 overflow-x-auto pb-2">
                    @for ($i = 1; $i <= 10; $i++)
                        <article
                            class="flex-shrink-0 w-56 sm:w-64 bg-[#1C1B26] hover:bg-[#25232F] transition cursor-pointer flex flex-col">
                            <div class="aspect-video bg-[#11101A] flex items-center justify-center border-b border-white/5">
                                <span class="text-white/15 text-xs tracking-widest uppercase">PLACEHOLDER</span>
                            </div>
                            <div class="p-4 flex-1 flex flex-col">
                                <span
                                    class="self-start inline-block px-2 py-0.5 mb-2 text-[10px] font-black tracking-widest uppercase bg-[#6B5B9E] text-black">RPG</span>
                                <h3 class="text-sm font-bold leading-snug">Jogo #{{ $i }}</h3>
                                <span class="text-[10px] text-white/40 mt-auto pt-3">Registrado em 2026-05-{{ str_pad(28 - ($i - 1), 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </article>
                    @endfor
                </div>
            </div>
        </section>

        {{-- seção 3: últimas reviews --}}
        <section class="border-t border-white/10">
            <div class="max-w-[1600px] mx-auto px-6 sm:px-12 py-10">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-xl sm:text-2xl font-black tracking-widest uppercase border-l-4 border-[#6B5B9E] pl-4">
                        Últimas reviews
                    </h2>
                    <a href="#"
                        class="px-5 py-2 border border-white/30 text-white font-black tracking-widest uppercase text-[10px] hover:border-[#6B5B9E] hover:text-[#6B5B9E] transition">
                        Ver todas
                    </a>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-1">
                    @for ($i = 1; $i <= 6; $i++)
                        @php
                            $nota = 5 - (($i - 1) % 3);
                            $estrelas = str_repeat('★', $nota) . str_repeat('☆', 5 - $nota);
                        @endphp
                        <article class="bg-[#1C1B26] hover:bg-[#25232F] transition p-5 flex gap-4">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 flex-shrink-0 bg-[#11101A] border border-white/5 flex items-center justify-center">
                                <span class="text-white/15 text-[10px] tracking-widest uppercase">CAPA</span>
                            </div>
                            <div class="flex-1 min-w-0 flex flex-col">
                                <div class="flex items-start justify-between gap-3 mb-1">
                                    <h3 class="text-base font-bold leading-snug">Jogo Review #{{ $i }}</h3>
                                    <span class="text-[10px] text-white/40 flex-shrink-0">2026-05-{{ str_pad(28 - ($i - 1) * 2, 2, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <div class="text-[#6B5B9E] text-sm font-black tracking-widest mb-2">
                                    {{ $estrelas }} <span class="text-white/40 text-[10px] font-bold ml-1">{{ $nota }}/5</span>
                                </div>
                                <p class="text-sm text-white/60 leading-relaxed line-clamp-2">
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor
                                    incididunt ut labore et dolore magna aliqua.
                                </p>
                            </div>
                        </article>
                    @endfor
                </div>
            </div>
        </section>

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
            <p class="text-[10px] tracking-widest text-white/40 uppercase font-bold">Built with the SISP architecture
            </p>
        </div>
    </footer>

</body>

</html>
