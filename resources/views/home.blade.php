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
                        class="text-sm font-bold tracking-widest uppercase text-white/60 hover:text-[#6B5B9E] transition">Novo
                        aqui?</a>
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
            <h2 class="text-xl sm:text-2xl font-black tracking-widest uppercase border-l-4 border-[#6B5B9E] pl-4">
                ÚLTIMAS NOTÍCIAS
            </h2>
            <a href="#"
                class="px-5 py-2 border border-white/30 text-white font-black tracking-widest uppercase text-[10px] hover:border-[#6B5B9E] hover:text-[#6B5B9E] transition">MORE
                NEWS</a>
        </div>

        <div class="flex gap-1 overflow-x-auto pb-2 sm:grid sm:grid-cols-2 lg:grid-cols-3 sm:overflow-visible">
            <article
                class="bg-[#1C1B26] hover:bg-[#25232F] transition cursor-pointer flex flex-col flex-shrink-0 w-72 sm:w-auto">
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

            <article
                class="bg-[#1C1B26] hover:bg-[#25232F] transition cursor-pointer flex flex-col flex-shrink-0 w-72 sm:w-auto">
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

            <article
                class="bg-[#1C1B26] hover:bg-[#25232F] transition cursor-pointer flex flex-col flex-shrink-0 w-72 sm:w-auto">
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
                EM DESTAQUE</h2>
        </div>
        <div class="flex gap-1 overflow-x-auto pb-2 md:justify-center">
            @forelse ($jogosEmDestaque as $jogo)
                <a href="{{ route('catalogo.jogo.visualizar', $jogo->id) }}"
                    class="group relative flex-shrink-0 w-64 md:w-80 aspect-[3/4] bg-[#1C1B26] overflow-hidden border border-white/5 flex items-end">
                    {{-- imagem de fundo (capa em retrato 3:4) --}}
                    @if ($jogo->capa())
                        <img src="{{ $jogo->capa() }}" alt="Capa de {{ $jogo->nome }}"
                            loading="lazy" decoding="async"
                            class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @endif

                    {{-- gradiente pra deixar o texto legível sobre a imagem --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>

                    {{-- conteúdo --}}
                    <div class="relative p-5">
                        @if ($jogo->generos->isNotEmpty())
                            <span
                                class="inline-block px-2 py-0.5 mb-2 text-[10px] font-black tracking-widest uppercase bg-[#6B5B9E] text-black">
                                {{ $jogo->generos->first()->nome }}
                            </span>
                        @endif
                        <h3 class="text-xl sm:text-2xl font-black tracking-tight uppercase leading-none">
                            {{ $jogo->nome }}
                        </h3>
                        <p class="text-xs text-white/60 mt-2">
                            {{ $jogo->desenvolvedora?->nome ?? 'Desenvolvedora desconhecida' }}
                            @if ($jogo->lancamento)
                                <span class="text-white/30">·</span> {{ $jogo->lancamento->format('Y') }}
                            @endif
                        </p>
                    </div>
                </a>
            @empty
                <article class="w-full bg-[#1C1B26] border border-white/5 flex items-center justify-center py-16 px-8">
                    <p class="text-white/30 text-sm tracking-widest uppercase font-bold text-center">
                        Nenhum jogo em destaque no momento
                    </p>
                </article>
            @endforelse
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
                @if ($patchNotes->currentPage() === 1 && $patchNotes->total() > 0)
                    <span class="text-[10px] font-black tracking-widest uppercase text-white/40">
                        Versão atual v{{ $patchNotes->getCollection()->first()->versao }}
                    </span>
                @endif
            </div>

            <div id="patch-notes-lista"
                class="flex gap-1 overflow-x-auto pb-2 md:grid md:grid-cols-1 md:overflow-visible">
                @forelse ($patchNotes as $nota)
                    <article
                        class="bg-[#1C1B26] p-5 sm:p-6 flex flex-col md:flex-row gap-5 flex-shrink-0 w-80 md:w-auto">
                        {{-- coluna da versão --}}
                        <div
                            class="md:w-48 flex-shrink-0 flex md:flex-col items-baseline md:items-start gap-3 md:gap-1">
                            <span
                                class="inline-block px-2 py-0.5 text-[10px] font-black tracking-widest uppercase bg-[#6B5B9E] text-black">
                                v{{ $nota->versao }}
                            </span>
                            <h3 class="text-base font-bold leading-snug">{{ $nota->titulo }}</h3>
                            <span
                                class="text-[10px] text-white/40 md:mt-1">{{ $nota->lancado_em->format('d/m/Y') }}</span>
                        </div>

                        {{-- lista de mudanças --}}
                        <ul class="flex-1 flex flex-col gap-2 md:border-l border-white/10 md:pl-6">
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
                    <article class="bg-[#1C1B26] p-8 text-center w-full">
                        <p class="text-white/30 text-sm tracking-widest uppercase font-bold">
                            Nenhuma atualização registrada ainda
                        </p>
                    </article>
                @endforelse
            </div>

            {{-- paginação dos patch notes (vanilla JS preenche) --}}
            <div id="patch-notes-paginacao" class="mt-4"></div>
        </div>
    </section>

    {{-- footer --}}
    <x-footer />

    {{-- paginação dos patch notes: vanilla JS (a home é pública, não depende do jQuery do @auth) --}}
    <script>
        (function() {
            const lista = document.getElementById('patch-notes-lista');
            const controles = document.getElementById('patch-notes-paginacao');
            if (!lista || !controles) return;

            // bate na própria home pedindo JSON — não precisa de rota nova e não quebra a página
            const urlPatchNotes = "{{ route('home') }}";

            const tags = {
                novo: {
                    rotulo: 'Novo',
                    classe: 'bg-[#6B5B9E] text-black'
                },
                melhoria: {
                    rotulo: 'Melhoria',
                    classe: 'border border-[#6B5B9E] text-[#8B7BB8]'
                },
                correcao: {
                    rotulo: 'Correção',
                    classe: 'border border-white/25 text-white/50'
                },
            };

            function escapar(txt) {
                const div = document.createElement('div');
                div.textContent = txt ?? '';
                return div.innerHTML;
            }

            function capitalizar(s) {
                return s ? s.charAt(0).toUpperCase() + s.slice(1) : s;
            }

            // "2026-08-05" -> "05/08/2026" (split simples, sem fuso)
            function formatarData(iso) {
                if (!iso) return '—';
                const [ano, mes, dia] = iso.split('-');
                return `${dia}/${mes}/${ano}`;
            }

            function artigoHtml(nota) {
                let mudancas = '';
                (nota.mudancas || []).forEach(function(m) {
                    const tag = tags[m.tipo] || {
                        rotulo: capitalizar(m.tipo),
                        classe: 'border border-white/25 text-white/50'
                    };
                    mudancas += `
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 flex-shrink-0 inline-block px-2 py-0.5 text-[9px] font-black tracking-widest uppercase ${tag.classe}">${tag.rotulo}</span>
                            <span class="text-sm text-white/70 leading-relaxed">${escapar(m.texto)}</span>
                        </li>`;
                });

                return `
                    <article class="bg-[#1C1B26] p-5 sm:p-6 flex flex-col md:flex-row gap-5 flex-shrink-0 w-80 md:w-auto">
                        <div class="md:w-48 flex-shrink-0 flex md:flex-col items-baseline md:items-start gap-3 md:gap-1">
                            <span class="inline-block px-2 py-0.5 text-[10px] font-black tracking-widest uppercase bg-[#6B5B9E] text-black">v${escapar(nota.versao)}</span>
                            <h3 class="text-base font-bold leading-snug">${escapar(nota.titulo)}</h3>
                            <span class="text-[10px] text-white/40 md:mt-1">${formatarData(nota.lancado_em)}</span>
                        </div>
                        <ul class="flex-1 flex flex-col gap-2 md:border-l border-white/10 md:pl-6">${mudancas}</ul>
                    </article>`;
            }

            function renderLista(dados) {
                if (!dados || dados.length === 0) {
                    lista.innerHTML =
                        '<article class="bg-[#1C1B26] p-8 text-center w-full"><p class="text-white/30 text-sm tracking-widest uppercase font-bold">Nenhuma atualização registrada ainda</p></article>';
                    return;
                }
                lista.innerHTML = dados.map(artigoHtml).join('');
            }

            function renderControles(meta) {
                controles.innerHTML = '';
                // só mostra controles quando há mais de uma página
                if (!meta || meta.total === 0 || meta.last_page <= 1) return;

                const base = 'px-3 py-2 text-[10px] font-black uppercase tracking-widest border transition';
                const ativo = 'bg-[#6B5B9E] text-black border-[#6B5B9E]';
                const inativo = 'border-white/15 text-white/60 hover:border-[#6B5B9E] hover:text-white';
                const off = 'border-white/10 text-white/20 cursor-not-allowed';

                function botao(pagina, rotulo, estado) {
                    const dis = estado === 'off' ? 'disabled' : '';
                    const cls = estado === 'ativo' ? ativo : (estado === 'off' ? off : inativo);
                    return `<button type="button" data-pn-pagina="${pagina}" ${dis} class="${base} ${cls}">${rotulo}</button>`;
                }

                let botoes = botao(meta.current_page - 1, '‹', meta.current_page === 1 ? 'off' : 'on');
                const inicio = Math.max(1, meta.current_page - 2);
                const fim = Math.min(meta.last_page, meta.current_page + 2);
                for (let p = inicio; p <= fim; p++) {
                    botoes += botao(p, p, p === meta.current_page ? 'ativo' : 'on');
                }
                botoes += botao(meta.current_page + 1, '›', meta.current_page === meta.last_page ? 'off' : 'on');

                const resumo =
                    `<span class="text-[10px] font-black uppercase tracking-widest text-white/40">Mostrando ${meta.from}–${meta.to} de ${meta.total}</span>`;
                controles.innerHTML =
                    `<div class="flex items-center justify-between gap-4 flex-wrap">${resumo}<div class="flex gap-1">${botoes}</div></div>`;
            }

            function carregar(pagina) {
                const url = new URL(urlPatchNotes, window.location.origin);
                url.searchParams.set('page', pagina);

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(resp => {
                        renderLista(resp.data);
                        renderControles(resp);
                    })
                    .catch(() => {
                        /* silencioso: página 1 já está renderizada pelo servidor */
                    });
            }

            // clique nos botões de página
            controles.addEventListener('click', function(e) {
                const btn = e.target.closest('[data-pn-pagina]');
                if (!btn || btn.disabled) return;
                const p = parseInt(btn.getAttribute('data-pn-pagina'), 10);
                if (!isNaN(p)) carregar(p);
            });

            // estado inicial: página 1 já veio do servidor; aqui só desenhamos os controles a partir do meta
            renderControles({
                current_page: {{ $patchNotes->currentPage() }},
                last_page: {{ $patchNotes->lastPage() }},
                from: {{ $patchNotes->firstItem() ?? 0 }},
                to: {{ $patchNotes->lastItem() ?? 0 }},
                total: {{ $patchNotes->total() }}
            });
        })();
    </script>

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
