<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Database</title>
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

<body class="bg-[#11101A] text-white min-h-screen">

    {{-- navbar (centered logo, MetroUI flat) --}}
    <header class="border-b border-white/10">
        <nav class="max-w-[1600px] mx-auto px-6 sm:px-12 py-5 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="/"
                    class="text-sm font-bold tracking-widest uppercase hover:text-[#6B5B9E] transition">HOME</a>
                <a href="#destaques"
                    class="text-sm font-bold tracking-widest uppercase text-white/60 hover:text-[#6B5B9E] transition">GAMES</a>
            </div>

            <a href="/" class="text-2xl sm:text-3xl font-black tracking-widest">
                GAME<span class="text-[#6B5B9E]">DB</span>
            </a>

            <div class="flex items-center gap-8">
                <a href="#novidades"
                    class="text-sm font-bold tracking-widest uppercase text-white/60 hover:text-[#6B5B9E] transition">NEWS</a>
                <a href="{{ route('gerenciador.usuario.criar') }}"
                    class="text-sm font-bold tracking-widest uppercase text-white/60 hover:text-[#6B5B9E] transition">REGISTER</a>
                       <a href="{{ route('gerenciador.usuario.login') }}"
                    class="text-sm font-bold tracking-widest uppercase text-white/60 hover:text-[#6B5B9E] transition">LOGIN</a>
            </div>
        </nav>
    </header>

    {{-- hero --}}
    <section class="max-w-[1600px] mx-auto px-6 sm:px-12 py-20 sm:py-28">
        <div class="text-center max-w-3xl mx-auto">
            <span
                class="inline-block px-3 py-1 mb-6 text-[10px] font-black tracking-widest uppercase bg-[#6B5B9E] text-black">BETA</span>
            <h1 class="text-5xl sm:text-6xl md:text-7xl font-black tracking-tight uppercase mb-6 leading-[0.95]">Game
                Database</h1>
            <p class="text-base sm:text-lg text-white/60 mb-10 leading-relaxed">
                Sua biblioteca interativa de jogos. Catalogue, descubra e organize seu universo gamer em um só lugar.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('gerenciador.usuario.criar') }}"
                    class="px-8 py-3 bg-[#6B5B9E] text-black font-black tracking-widest uppercase text-xs hover:bg-[#8674B8] transition">
                    Começar Agora
                </a>
                <a href="#novidades"
                    class="px-8 py-3 border border-white/30 text-white font-black tracking-widest uppercase text-xs hover:border-[#6B5B9E] hover:text-[#6B5B9E] transition">
                    Explorar
                </a>
            </div>
        </div>
    </section>

    {{-- latest news --}}
    <section id="novidades" class="max-w-[1600px] mx-auto px-6 sm:px-12 pt-8 pb-12">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-xl sm:text-2xl font-black tracking-widest uppercase border-l-4 border-[#6B5B9E] pl-4">LATEST
                NEWS</h2>
            <a href="#"
                class="px-5 py-2 border border-white/30 text-white font-black tracking-widest uppercase text-[10px] hover:border-[#6B5B9E] hover:text-[#6B5B9E] transition">MORE
                NEWS</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-1">
            <article class="bg-[#1C1B26] hover:bg-[#25232F] transition cursor-pointer flex flex-col">
                <div class="aspect-video bg-[#11101A] flex items-center justify-center border-b border-white/5">
                    <span class="text-white/15 text-xs tracking-widest uppercase">PLACEHOLDER</span>
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    <span
                        class="self-start inline-block px-2 py-0.5 mb-3 text-[10px] font-black tracking-widest uppercase bg-[#6B5B9E] text-black">EVENT</span>
                    <h3 class="text-base font-bold leading-snug">Plataforma em construção</h3>
                    <div class="flex items-center justify-between mt-auto pt-4 border-t border-white/10">
                        <span class="text-[10px] font-black tracking-widest uppercase text-white/60">READ MORE</span>
                        <span class="text-[10px] text-white/40">24/05/2026</span>
                    </div>
                </div>
            </article>

            <article class="bg-[#1C1B26] hover:bg-[#25232F] transition cursor-pointer flex flex-col">
                <div class="aspect-video bg-[#11101A] flex items-center justify-center border-b border-white/5">
                    <span class="text-white/15 text-xs tracking-widest uppercase">PLACEHOLDER</span>
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    <span
                        class="self-start inline-block px-2 py-0.5 mb-3 text-[10px] font-black tracking-widest uppercase bg-[#6B5B9E] text-black">UPDATE</span>
                    <h3 class="text-base font-bold leading-snug">Cadastro de usuários disponível</h3>
                    <div class="flex items-center justify-between mt-auto pt-4 border-t border-white/10">
                        <span class="text-[10px] font-black tracking-widest uppercase text-white/60">READ MORE</span>
                        <span class="text-[10px] text-white/40">24/05/2026</span>
                    </div>
                </div>
            </article>

            <article class="bg-[#1C1B26] hover:bg-[#25232F] transition cursor-pointer flex flex-col">
                <div class="aspect-video bg-[#11101A] flex items-center justify-center border-b border-white/5">
                    <span class="text-white/15 text-xs tracking-widest uppercase">PLACEHOLDER</span>
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    <span
                        class="self-start inline-block px-2 py-0.5 mb-3 text-[10px] font-black tracking-widest uppercase bg-[#6B5B9E] text-black">EVENT</span>
                    <h3 class="text-base font-bold leading-snug">Catálogo de jogos em breve</h3>
                    <div class="flex items-center justify-between mt-auto pt-4 border-t border-white/10">
                        <span class="text-[10px] font-black tracking-widest uppercase text-white/60">READ MORE</span>
                        <span class="text-[10px] text-white/40">24/05/2026</span>
                    </div>
                </div>
            </article>
        </div>
    </section>

    {{-- featured titles --}}
    <section id="destaques" class="max-w-[1600px] mx-auto px-6 sm:px-12 pt-8 pb-16">
        <div class="flex items-center mb-8">
            <h2 class="text-xl sm:text-2xl font-black tracking-widest uppercase border-l-4 border-[#6B5B9E] pl-4">
                FEATURED TITLES</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-1">
            <article
                class="aspect-video bg-[#1C1B26] hover:bg-[#25232F] transition cursor-pointer flex items-end p-8 border border-white/5">
                <div>
                    <span
                        class="inline-block px-2 py-0.5 mb-3 text-[10px] font-black tracking-widest uppercase bg-[#6B5B9E] text-black">JRPG</span>
                    <h3 class="text-3xl sm:text-4xl font-black tracking-tight uppercase leading-none">Em breve</h3>
                    <p class="text-sm text-white/60 mt-3">Catálogo em construção</p>
                </div>
            </article>

            <article
                class="aspect-video bg-[#1C1B26] hover:bg-[#25232F] transition cursor-pointer flex items-end p-8 border border-white/5">
                <div>
                    <span
                        class="inline-block px-2 py-0.5 mb-3 text-[10px] font-black tracking-widest uppercase bg-[#6B5B9E] text-black">INDIE</span>
                    <h3 class="text-3xl sm:text-4xl font-black tracking-tight uppercase leading-none">Em breve</h3>
                    <p class="text-sm text-white/60 mt-3">Catálogo em construção</p>
                </div>
            </article>
        </div>
    </section>

    {{-- stats strip --}}
    <section class="border-t border-white/10">
        <div class="max-w-[1600px] mx-auto px-6 sm:px-12 py-10 grid grid-cols-3 gap-1">
            <div class="bg-[#1C1B26] py-8 text-center">
                <div class="text-4xl sm:text-5xl font-black text-[#6B5B9E]">&infin;</div>
                <p class="text-[10px] uppercase tracking-widest text-white/40 mt-2 font-bold">Jogos planejados</p>
            </div>
            <div class="bg-[#1C1B26] py-8 text-center">
                <div class="text-4xl sm:text-5xl font-black text-[#6B5B9E]">0</div>
                <p class="text-[10px] uppercase tracking-widest text-white/40 mt-2 font-bold">Reviews</p>
            </div>
            <div class="bg-[#1C1B26] py-8 text-center">
                <div class="text-4xl sm:text-5xl font-black text-[#6B5B9E]">1</div>
                <p class="text-[10px] uppercase tracking-widest text-white/40 mt-2 font-bold">Dev a bordo</p>
            </div>
        </div>
    </section>

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
