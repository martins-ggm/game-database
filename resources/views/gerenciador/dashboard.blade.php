<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Game Database</title>
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

        {{-- hero busca --}}
        <section class="max-w-[1600px] mx-auto px-6 sm:px-12 pt-16 pb-12">
            <div class="max-w-4xl mx-auto">
                <h1
                    class="text-4xl sm:text-5xl md:text-6xl font-black tracking-tight uppercase mb-4 leading-[0.95] border-l-4 border-[#6B5B9E] pl-4">
                    Dashboard
                </h1>
                <p class="text-sm sm:text-base text-white/60 mb-8 ml-5">
                    Sua biblioteca interativa de jogos — encontre, filtre e organize.
                </p>

                <form class="flex flex-col sm:flex-row gap-1">
                    <input type="search" name="busca" placeholder="Buscar por título, estúdio, gênero..."
                        class="flex-1 px-4 py-3 bg-[#1C1B26] border border-white/10 text-white placeholder-white/30 focus:outline-none focus:border-[#6B5B9E] transition">
                    <button type="submit"
                        class="px-8 py-3 bg-[#6B5B9E] text-black font-black tracking-widest uppercase text-xs hover:bg-[#8674B8] transition">
                        Buscar
                    </button>
                </form>

                <div class="flex flex-wrap gap-1 mt-4">
                    <button type="button"
                        class="px-3 py-1 text-[10px] font-black tracking-widest uppercase bg-[#6B5B9E] text-black">TODOS</button>
                    <button type="button"
                        class="px-3 py-1 text-[10px] font-black tracking-widest uppercase bg-[#1C1B26] text-white/60 hover:bg-[#25232F] hover:text-white transition">RPG</button>
                    <button type="button"
                        class="px-3 py-1 text-[10px] font-black tracking-widest uppercase bg-[#1C1B26] text-white/60 hover:bg-[#25232F] hover:text-white transition">INDIE</button>
                    <button type="button"
                        class="px-3 py-1 text-[10px] font-black tracking-widest uppercase bg-[#1C1B26] text-white/60 hover:bg-[#25232F] hover:text-white transition">FPS</button>
                    <button type="button"
                        class="px-3 py-1 text-[10px] font-black tracking-widest uppercase bg-[#1C1B26] text-white/60 hover:bg-[#25232F] hover:text-white transition">EM
                        ANDAMENTO</button>
                    <button type="button"
                        class="px-3 py-1 text-[10px] font-black tracking-widest uppercase bg-[#1C1B26] text-white/60 hover:bg-[#25232F] hover:text-white transition">COMPLETOS</button>
                </div>
            </div>
        </section>

        {{-- visão geral (stats) --}}
        <section class="border-t border-white/10">
            <div class="max-w-[1600px] mx-auto px-6 sm:px-12 py-10">
                <h2
                    class="text-xl sm:text-2xl font-black tracking-widest uppercase border-l-4 border-[#6B5B9E] pl-4 mb-8">
                    VISÃO GERAL
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-1">
                    <div class="bg-[#1C1B26] py-8 text-center">
                        <div class="text-4xl sm:text-5xl font-black text-[#6B5B9E]">0</div>
                        <p class="text-[10px] uppercase tracking-widest text-white/40 mt-2 font-bold">Total no catálogo
                        </p>
                    </div>
                    <div class="bg-[#1C1B26] py-8 text-center">
                        <div class="text-4xl sm:text-5xl font-black text-[#6B5B9E]">0</div>
                        <p class="text-[10px] uppercase tracking-widest text-white/40 mt-2 font-bold">Em andamento</p>
                    </div>
                    <div class="bg-[#1C1B26] py-8 text-center">
                        <div class="text-4xl sm:text-5xl font-black text-[#6B5B9E]">0</div>
                        <p class="text-[10px] uppercase tracking-widest text-white/40 mt-2 font-bold">Completos</p>
                    </div>
                    <div class="bg-[#1C1B26] py-8 text-center">
                        <div class="text-4xl sm:text-5xl font-black text-[#6B5B9E]">0</div>
                        <p class="text-[10px] uppercase tracking-widest text-white/40 mt-2 font-bold">Listas</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- catálogo --}}
        <section id="catalogo" class="max-w-[1600px] mx-auto px-6 sm:px-12 pt-8 pb-16">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl sm:text-2xl font-black tracking-widest uppercase border-l-4 border-[#6B5B9E] pl-4">
                    CATÁLOGO
                </h2>
                <a href="#"
                    class="px-5 py-2 border border-white/30 text-white font-black tracking-widest uppercase text-[10px] hover:border-[#6B5B9E] hover:text-[#6B5B9E] transition">VER
                    TUDO</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-1">

                @for ($i = 1; $i <= 8; $i++)
                    <article class="bg-[#1C1B26] hover:bg-[#25232F] transition cursor-pointer flex flex-col">
                        <div class="aspect-video bg-[#11101A] flex items-center justify-center border-b border-white/5">
                            <span class="text-white/15 text-xs tracking-widest uppercase">PLACEHOLDER</span>
                        </div>
                        <div class="p-5 flex-1 flex flex-col">
                            <span
                                class="self-start inline-block px-2 py-0.5 mb-3 text-[10px] font-black tracking-widest uppercase bg-[#6B5B9E] text-black">RPG</span>
                            <h3 class="text-base font-bold leading-snug">Jogo #{{ $i }}</h3>
                            <div class="flex items-center justify-between mt-auto pt-4 border-t border-white/10">
                                <span class="text-[10px] font-black tracking-widest uppercase text-white/60">VER
                                    MAIS</span>
                                <span class="text-[10px] text-white/40">2026</span>
                            </div>
                        </div>
                    </article>
                @endfor

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
