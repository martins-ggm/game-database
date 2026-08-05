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
                <a href="#novidades"
                    class="text-sm font-bold tracking-widest uppercase text-white/60 hover:text-[#6B5B9E] transition">Notícias</a>
            </div>

            <a href="/" class="text-2xl sm:text-3xl font-black tracking-widest">
                GAME<span class="text-[#6B5B9E]">DB</span>
            </a>

            <div class="flex items-center gap-8">
                @auth
                    <p class="text-sm font-bold tracking-widest uppercase text-white/60 ">
                        Olá, <a href="{{ route('gerenciador.usuario.perfil', auth()->id()) }}"
                            class="text-sm font-bold tracking-widest uppercase text-white/60 hover:text-[#6B5B9E] transition">{{ auth()->user()->nome }}</a>!
                    </p>
                    <a href="#" id="sair"
                        class="text-sm font-bold tracking-widest uppercase text-white/60 hover:text-[#6B5B9E] transition cursor-pointer">SAIR</a>
                @else
                    <a href="#novidades"
                        class="text-sm font-bold tracking-widest uppercase text-white/60 hover:text-[#6B5B9E] transition">Notícias</a>
                    <a href="{{ route('gerenciador.usuario.criar') }}"
                        class="text-sm font-bold tracking-widest uppercase text-white/60 hover:text-[#6B5B9E] transition">Novo aqui?</a>
                    <a href="{{ route('gerenciador.usuario.login') }}"
                        class="text-sm font-bold tracking-widest uppercase text-white/60 hover:text-[#6B5B9E] transition">LOGIN</a>
                @endauth
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
                @auth
                    <a href="{{ route('gerenciador.dashboard.visualizar') }}"
                        class="px-8 py-3 bg-[#6B5B9E] text-black font-black tracking-widest uppercase text-xs hover:bg-[#8674B8] transition">
                        Acessar como {{ auth()->user()->nome }}.
                    </a>
                    <a href="#" id="trocar-usuario"
                        class="px-8 py-3 border border-white/30 text-white font-black tracking-widest uppercase text-xs hover:border-[#6B5B9E] hover:text-[#6B5B9E] transition">
                        Trocar usuário.
                    </a>
                @else
                    <a href="{{ route('gerenciador.usuario.criar') }}"
                        class="px-8 py-3 bg-[#6B5B9E] text-black font-black tracking-widest uppercase text-xs hover:bg-[#8674B8] transition">
                        Começar Agora
                    </a>

                    <a href="{{ route('gerenciador.dashboard.visualizar') }}"
                        class="px-8 py-3 border border-white/30 text-white font-black tracking-widest uppercase text-xs hover:border-[#6B5B9E] hover:text-[#6B5B9E] transition">
                        Explorar!
                    </a>
                @endauth
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

    {{-- patch notes --}}
    @php
        // estilo de cada tag por tipo de mudança (só apresentação — os dados vêm do controller)
        $tags = [
            'novo' => ['rotulo' => 'Novo', 'classe' => 'bg-[#6B5B9E] text-black'],
            'melhoria' => ['rotulo' => 'Melhoria', 'classe' => 'border border-[#6B5B9E] text-[#8B7BB8]'],
            'correcao' => ['rotulo' => 'Correção', 'classe' => 'border border-white/25 text-white/50'],
        ];
    @endphp

    <section id="patch-notes" class="border-t border-white/10">
        <div class="max-w-[1600px] mx-auto px-6 sm:px-12 pt-12 pb-16">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl sm:text-2xl font-black tracking-widest uppercase border-l-4 border-[#6B5B9E] pl-4">
                    PATCH NOTES</h2>
                @if ($patchNotes->isNotEmpty())
                    <span class="text-[10px] font-black tracking-widest uppercase text-white/40">
                        Versão atual v{{ $patchNotes->first()->versao }}
                    </span>
                @endif
            </div>

            <div class="grid grid-cols-1 gap-1">
                @forelse ($patchNotes as $nota)
                    <article class="bg-[#1C1B26] p-5 sm:p-6 flex flex-col sm:flex-row gap-5">
                        {{-- coluna da versão --}}
                        <div class="sm:w-48 flex-shrink-0 flex sm:flex-col items-baseline sm:items-start gap-3 sm:gap-1">
                            <span
                                class="inline-block px-2 py-0.5 text-[10px] font-black tracking-widest uppercase bg-[#6B5B9E] text-black">
                                v{{ $nota->versao }}
                            </span>
                            <h3 class="text-base font-bold leading-snug">{{ $nota->titulo }}</h3>
                            <span class="text-[10px] text-white/40 sm:mt-1">{{ $nota->lancado_em->format('d/m/Y') }}</span>
                        </div>

                        {{-- lista de mudanças --}}
                        <ul class="flex-1 flex flex-col gap-2 sm:border-l border-white/10 sm:pl-6">
                            @foreach ($nota->mudancas as $mudanca)
                                @php($tag = $tags[$mudanca['tipo']] ?? ['rotulo' => ucfirst($mudanca['tipo']), 'classe' => 'border border-white/25 text-white/50'])
                                <li class="flex items-start gap-3">
                                    <span
                                        class="mt-0.5 flex-shrink-0 inline-block px-2 py-0.5 text-[9px] font-black tracking-widest uppercase {{ $tag['classe'] }}">
                                        {{ $tag['rotulo'] }}
                                    </span>
                                    <span class="text-sm text-white/70 leading-relaxed">{{ $mudanca['texto'] }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </article>
                @empty
                    <article class="bg-[#1C1B26] p-8 text-center">
                        <p class="text-white/30 text-sm tracking-widest uppercase font-bold">
                            Nenhuma atualização registrada ainda
                        </p>
                    </article>
                @endforelse
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
            <p class="text-[10px] tracking-widest text-white/40 uppercase font-bold">Built with love ;)
            </p>
        </div>
    </footer>
</body>

</html>

@auth
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(function() {
            $('#sair').on('click', function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('gerenciador.usuario.logout') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        window.location.href = response.redirect;
                    },
                    error: function(xhr) {
                        console.error('Logout failed:', xhr);
                        window.location.href = "{{ route('gerenciador.usuario.login') }}";
                    }
                });
            });
        });
        $(function() {
            $('#trocar-usuario').on('click', function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('gerenciador.usuario.logout') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        window.location.href = response.redirect;
                    },
                    error: function(xhr) {
                        console.error('Logout failed:', xhr);
                        window.location.href = "{{ route('gerenciador.usuario.login') }}";
                    }
                });
            });
        });
    </script>
@endauth
