<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coleção — Game Database</title>
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
        // id do dono vem do parâmetro da rota (/colecao/visualizar/{id}) — robusto mesmo se a coleção estiver vazia.
        $usuarioId = request()->route('id');
        // dono derivado da 1ª entrada (situacao/jogo já vêm eager-loaded; usuario é lazy — 1 query só).
        $dono = $colecao->first()?->usuario;
        // situações distintas presentes na coleção, pros filtros.
        $situacoes = $colecao->pluck('situacao.nome')->filter()->unique()->values();
    @endphp

    <main class="flex-1">

        {{-- cabeçalho --}}
        <section class="max-w-[1600px] mx-auto w-full px-6 sm:px-12 pt-10 pb-8">
            <a href="{{ route('gerenciador.usuario.perfil', $usuarioId) }}"
                class="inline-flex items-center gap-2 text-[10px] font-black tracking-widest uppercase text-white/40 hover:text-[#6B5B9E] transition">
                <span class="text-base leading-none">&larr;</span> Voltar ao perfil
            </a>

            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mt-6">
                <div>
                    <p class="text-[10px] font-black tracking-widest uppercase text-white/40 mb-2">Biblioteca</p>
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight uppercase leading-[0.95]">
                        Coleção @if ($dono) <span class="text-[#6B5B9E]">de {{ $dono->nome }}</span>@endif
                    </h1>
                </div>
                <span class="text-[10px] font-black tracking-widest uppercase text-white/40">
                    {{ $colecao->count() }} {{ $colecao->count() === 1 ? 'jogo' : 'jogos' }}
                </span>
            </div>
        </section>

        {{-- filtros por situação --}}
        @if ($situacoes->isNotEmpty())
            <section class="border-t border-white/10">
                <div class="max-w-[1600px] mx-auto w-full px-6 sm:px-12 py-5 flex flex-wrap gap-1">
                    <button type="button"
                        class="filtro-situacao px-4 py-2 text-[10px] font-black tracking-widest uppercase border border-[#6B5B9E] bg-[#6B5B9E] text-black transition"
                        data-filtro="todos">Todos</button>
                    @foreach ($situacoes as $situacao)
                        <button type="button"
                            class="filtro-situacao px-4 py-2 text-[10px] font-black tracking-widest uppercase border border-white/15 text-white/60 hover:border-[#6B5B9E] hover:text-white transition"
                            data-filtro="{{ $situacao }}">{{ $situacao }}</button>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- grade de jogos --}}
        <section class="border-t border-white/10">
            <div class="max-w-[1600px] mx-auto w-full px-6 sm:px-12 py-10">

                @if ($colecao->isEmpty())
                    <div class="py-20 text-center">
                        <p class="text-white/30 text-sm tracking-widest uppercase font-bold">
                            Nenhum jogo na coleção ainda
                        </p>
                    </div>
                @else
                    <div id="grid-colecao"
                        class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-1">
                        @foreach ($colecao as $item)
                            @continue(!$item->jogo)
                            <article data-situacao="{{ $item->situacao?->nome }}"
                                class="colecao-card group bg-[#1C1B26] hover:bg-[#25232F] transition">
                                <a href="{{ route('catalogo.jogo.visualizar', $item->jogo->id) }}"
                                    class="flex flex-col h-full">
                                    <div class="aspect-[3/4] bg-[#11101A] overflow-hidden border-b border-white/5">
                                        @if ($item->jogo->capa(false))
                                            <img src="{{ $item->jogo->capa(false) }}"
                                                alt="Capa de {{ $item->jogo->nome }}" loading="lazy" decoding="async"
                                                class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <span class="text-white/15 text-[10px] tracking-widest uppercase">Sem capa</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-3 flex-1 flex flex-col">
                                        @if ($item->situacao)
                                            <span
                                                class="self-start inline-block px-2 py-0.5 mb-2 text-[10px] font-black tracking-widest uppercase bg-[#6B5B9E] text-black">
                                                {{ $item->situacao->nome }}
                                            </span>
                                        @endif
                                        <h3 class="text-sm font-bold leading-snug line-clamp-2">{{ $item->jogo->nome }}</h3>
                                        <span class="text-[10px] text-white/40 mt-auto pt-2">
                                            {{ $item->jogo->lancamento?->format('Y') ?? '—' }}
                                        </span>
                                    </div>
                                </a>
                            </article>
                        @endforeach
                    </div>
                @endif

            </div>
        </section>

    </main>

    {{-- footer --}}
    <x-footer />

    {{-- filtro por situação (vanilla JS — funciona pra guest e logado, sem depender do jQuery do navbar) --}}
    <script>
        (function () {
            const botoes = document.querySelectorAll('.filtro-situacao');
            const cards = document.querySelectorAll('#grid-colecao .colecao-card');
            if (!botoes.length) return;

            const ativo = ['bg-[#6B5B9E]', 'text-black', 'border-[#6B5B9E]'];
            const inativo = ['text-white/60', 'border-white/15'];

            botoes.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    botoes.forEach(function (b) {
                        b.classList.remove(...ativo);
                        b.classList.add(...inativo);
                    });
                    btn.classList.remove(...inativo);
                    btn.classList.add(...ativo);

                    const filtro = btn.dataset.filtro;
                    cards.forEach(function (card) {
                        const mostrar = filtro === 'todos' || card.dataset.situacao === filtro;
                        card.classList.toggle('hidden', !mostrar);
                    });
                });
            });
        })();
    </script>

</body>

</html>
