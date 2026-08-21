<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews — Game Database</title>
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
        // id do dono vem do parâmetro da rota (/reviews/{id}) — robusto mesmo se não houver reviews.
        $usuarioId = request()->route('id');
        // dono derivado da 1ª review; jogo já vem eager-loaded, usuario é lazy (1 query só).
        $dono = $reviews->first()?->usuario;
        // estrelas a partir de uma nota (0–5) → "★★★★☆"
        $estrelas = fn ($n) => str_repeat('★', (int) round((float) $n)) . str_repeat('☆', 5 - (int) round((float) $n));
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
                    <p class="text-[10px] font-black tracking-widest uppercase text-white/40 mb-2">Avaliações</p>
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight uppercase leading-[0.95]">
                        Reviews @if ($dono) <span class="text-[#6B5B9E]">de {{ $dono->nome }}</span>@endif
                    </h1>
                </div>
                <span class="text-[10px] font-black tracking-widest uppercase text-white/40">
                    {{ $reviews->total() }} {{ $reviews->total() === 1 ? 'review' : 'reviews' }}
                </span>
            </div>
        </section>

        {{-- lista de reviews (coluna legível; página 1 no servidor, próximas via AJAX) --}}
        <section class="border-t border-white/10">
            <div class="max-w-6xl mx-auto w-full px-6 sm:px-12 py-10">

                @if ($reviews->total() > 0)
                    <div id="lista-reviews" class="grid grid-cols-1 lg:grid-cols-2 gap-1">
                        @foreach ($reviews as $review)
                            <article class="bg-[#1C1B26] hover:bg-[#25232F] transition p-5 sm:p-6 flex gap-5">
                                {{-- capa (retrato 3:4) --}}
                                <a href="{{ $review->jogo ? route('catalogo.jogo.visualizar', $review->jogo->id) : '#' }}"
                                    class="w-24 sm:w-28 aspect-[3/4] flex-shrink-0 bg-[#11101A] border border-white/5 overflow-hidden flex items-center justify-center">
                                    @if ($review->jogo?->capa(false))
                                        <img src="{{ $review->jogo->capa(false) }}"
                                            alt="Capa de {{ $review->jogo->nome }}" loading="lazy" decoding="async"
                                            class="w-full h-full object-cover">
                                    @else
                                        <span class="text-white/15 text-[10px] tracking-widest uppercase text-center px-2">Sem capa</span>
                                    @endif
                                </a>

                                {{-- conteúdo --}}
                                <div class="flex-1 min-w-0 flex flex-col">
                                    <div class="flex items-start justify-between gap-4">
                                        <a href="{{ $review->jogo ? route('catalogo.jogo.visualizar', $review->jogo->id) : '#' }}"
                                            class="block min-w-0">
                                            <h2
                                                class="text-lg sm:text-xl font-black tracking-tight leading-tight hover:text-[#6B5B9E] transition">
                                                {{ $review->jogo?->nome ?? 'Jogo removido' }}
                                            </h2>
                                        </a>
                                        <span class="text-[10px] text-white/40 flex-shrink-0 whitespace-nowrap">
                                            {{ $review->created_at?->format('d/m/Y') }}
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-2 my-3">
                                        <span class="text-[#6B5B9E] text-base tracking-widest">{{ $estrelas($review->nota) }}</span>
                                        <span
                                            class="text-white/40 text-xs font-bold">{{ number_format((float) $review->nota, 1) }}/5</span>
                                    </div>

                                    @if ($review->review)
                                        <p class="text-sm text-white/70 leading-relaxed">{{ $review->review }}</p>
                                    @else
                                        <p class="text-sm text-white/25 italic leading-relaxed">Sem comentário.</p>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>

                    {{-- controles de paginação (montados pelo JS a partir do meta inicial) --}}
                    <div id="paginacao" class="flex items-center justify-center gap-4 mt-10"></div>
                @else
                    <div class="py-20 text-center">
                        <p class="text-white/30 text-sm tracking-widest uppercase font-bold">
                            Nenhuma review ainda
                        </p>
                        <p class="text-white/20 text-xs mt-2">
                            As avaliações que {{ $dono?->nome ?? 'esse usuário' }} fizer aparecem aqui.
                        </p>
                    </div>
                @endif

            </div>
        </section>

    </main>

    {{-- footer --}}
    <x-footer />

    @if ($reviews->total() > 0)
        {{-- paginação AJAX (mesmo padrão do catálogo: fetch na própria rota com wantsJson) --}}
        <script>
            (function () {
                const lista = document.getElementById('lista-reviews');
                const paginacao = document.getElementById('paginacao');
                if (!lista || !paginacao) return;

                @php
                    $rotaReviews = route('review.usuario', $usuarioId);
                    $rotaVisualizar = route('catalogo.jogo.visualizar', ['id' => '__ID__']);
                    $metaInicial = [
                        'current_page' => $reviews->currentPage(),
                        'last_page'    => $reviews->lastPage(),
                    ];
                @endphp

                const urlBase = @json($rotaReviews);
                const rotaVisualizar = @json($rotaVisualizar);
                const metaInicial = @json($metaInicial);

                function escapar(texto) {
                    const div = document.createElement('div');
                    div.textContent = texto ?? '';
                    return div.innerHTML;
                }

                function estrelas(nota) {
                    const n = Math.round(parseFloat(nota) || 0);
                    return '★'.repeat(n) + '☆'.repeat(5 - n);
                }

                function cardHtml(review) {
                    const jogo = review.jogo;
                    const href = jogo ? rotaVisualizar.replace('__ID__', jogo.id) : '#';
                    const nome = jogo ? escapar(jogo.nome) : 'Jogo removido';
                    const capa = jogo && jogo.imagem_pequena
                        ? `<img src="${jogo.imagem_pequena}" alt="Capa de ${nome}" loading="lazy" decoding="async" class="w-full h-full object-cover">`
                        : `<span class="text-white/15 text-[10px] tracking-widest uppercase text-center px-2">Sem capa</span>`;

                    const nota = parseFloat(review.nota) || 0;
                    const data = review.criado_em ? review.criado_em.slice(0, 10) : '';
                    const texto = review.review
                        ? `<p class="text-sm text-white/70 leading-relaxed">${escapar(review.review)}</p>`
                        : `<p class="text-sm text-white/25 italic leading-relaxed">Sem comentário.</p>`;

                    return `<article class="bg-[#1C1B26] hover:bg-[#25232F] transition p-5 sm:p-6 flex gap-5">
                        <a href="${href}" class="w-24 sm:w-28 aspect-[3/4] flex-shrink-0 bg-[#11101A] border border-white/5 overflow-hidden flex items-center justify-center">${capa}</a>
                        <div class="flex-1 min-w-0 flex flex-col">
                            <div class="flex items-start justify-between gap-4">
                                <a href="${href}" class="block min-w-0">
                                    <h2 class="text-lg sm:text-xl font-black tracking-tight leading-tight hover:text-[#6B5B9E] transition">${nome}</h2>
                                </a>
                                <span class="text-[10px] text-white/40 flex-shrink-0 whitespace-nowrap">${data}</span>
                            </div>
                            <div class="flex items-center gap-2 my-3">
                                <span class="text-[#6B5B9E] text-base tracking-widest">${estrelas(nota)}</span>
                                <span class="text-white/40 text-xs font-bold">${nota.toFixed(1)}/5</span>
                            </div>
                            ${texto}
                        </div>
                    </article>`;
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
                    lista.style.opacity = '0.4';
                    try {
                        const url = new URL(urlBase, window.location.origin);
                        url.searchParams.set('page', pagina);

                        const resp = await fetch(url, { headers: { 'Accept': 'application/json' } });
                        if (!resp.ok) return;

                        const meta = (await resp.json()).reviews;
                        lista.innerHTML = meta.data.map(cardHtml).join('');
                        paginacao.innerHTML = controlesHtml(meta);

                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    } finally {
                        lista.style.opacity = '1';
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
    @endif

</body>

</html>
