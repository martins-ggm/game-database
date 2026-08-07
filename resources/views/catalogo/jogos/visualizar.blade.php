<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $jogo->nome }} — Game Database</title>
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
        $reviews = $reviews ?? collect();
        $reviewUsuario = $reviewUsuario ?? null;

        $totalReviews = $reviews->count();
        $notaMedia = $totalReviews ? round($reviews->avg('nota'), 1) : null;

        // reviews dos outros usuários (a do próprio aparece no bloco "Sua avaliação")
        $outrasReviews = $reviewUsuario
            ? $reviews->reject(fn ($r) => $r->id === $reviewUsuario->id)->values()
            : $reviews;

        // estrelas a partir de uma nota (0–5) → "★★★★☆"
        $estrelas = fn ($n) => str_repeat('★', (int) round((float) $n)) . str_repeat('☆', 5 - (int) round((float) $n));
    @endphp

    <main class="flex-1">

        {{-- ============ HERO ============ --}}
        <section class="border-b border-white/10">
            <div class="max-w-[1600px] mx-auto px-6 sm:px-12 py-10 sm:py-14">

                <a href="{{ route('gerenciador.dashboard.visualizar') }}"
                    class="inline-block text-[10px] font-black tracking-widest uppercase text-white/40 hover:text-[#6B5B9E] transition mb-8">
                    &larr; Voltar ao catálogo
                </a>

                <div class="flex flex-col md:flex-row gap-8 lg:gap-12">

                    {{-- capa --}}
                    <div class="w-full max-w-[260px] mx-auto md:mx-0 flex-shrink-0">
                        <div class="aspect-[3/4] bg-[#1C1B26] border border-white/10 overflow-hidden">
                            @if ($jogo->url_imagem_grande)
                                <img src="{{ asset('storage/' . $jogo->url_imagem_grande) }}"
                                    alt="Capa de {{ $jogo->nome }}" class="w-full h-full object-cover">
                            @else
                                <div
                                    class="w-full h-full flex items-center justify-center text-white/15 text-xs uppercase tracking-widest">
                                    Sem capa
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- informações --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] font-black tracking-widest uppercase text-white/40 mb-3">Jogo</p>
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight uppercase leading-[0.95] mb-4">
                            {{ $jogo->nome }}
                        </h1>

                        <p class="text-sm text-white/60 mb-6">
                            {{ $jogo->desenvolvedora?->nome ?? 'Desenvolvedora desconhecida' }}
                            <span class="text-white/20 mx-2">•</span>
                            {{ $jogo->lancamento?->format('d/m/Y') ?? 'Lançamento desconhecido' }}
                        </p>

                        {{-- média de avaliação --}}
                        @if ($notaMedia !== null)
                            <div class="inline-flex items-center gap-4 bg-[#1C1B26] border border-white/10 px-5 py-4 mb-8">
                                <div class="text-4xl font-black text-[#6B5B9E] leading-none">{{ number_format($notaMedia, 1) }}</div>
                                <div>
                                    <div class="text-[#6B5B9E] text-lg tracking-widest leading-none">{{ $estrelas($notaMedia) }}</div>
                                    <p class="text-[10px] uppercase tracking-widest text-white/40 mt-1 font-bold">
                                        {{ $totalReviews }} {{ $totalReviews === 1 ? 'avaliação' : 'avaliações' }}
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="inline-flex items-center bg-[#1C1B26] border border-white/10 px-5 py-4 mb-8">
                                <span class="text-white/30 text-xs uppercase tracking-widest font-bold">Sem avaliações ainda</span>
                            </div>
                        @endif

                        {{-- plataformas --}}
                        <div class="mb-5">
                            <p class="text-[10px] font-black tracking-widest uppercase text-white/40 mb-2">Plataformas</p>
                            <div class="flex flex-wrap gap-2">
                                @forelse ($jogo->plataformas as $plataforma)
                                    <span
                                        class="px-3 py-1 border border-white/15 text-[10px] font-black uppercase tracking-widest text-white/70">
                                        {{ $plataforma->nome }}
                                    </span>
                                @empty
                                    <span class="text-white/30 text-xs uppercase tracking-widest">—</span>
                                @endforelse
                            </div>
                        </div>

                        {{-- gêneros --}}
                        <div class="mb-8">
                            <p class="text-[10px] font-black tracking-widest uppercase text-white/40 mb-2">Gêneros</p>
                            <div class="flex flex-wrap gap-2">
                                @forelse ($jogo->generos as $genero)
                                    <span
                                        class="px-3 py-1 bg-[#6B5B9E] text-black text-[10px] font-black uppercase tracking-widest">
                                        {{ $genero->nome }}
                                    </span>
                                @empty
                                    <span class="text-white/30 text-xs uppercase tracking-widest">—</span>
                                @endforelse
                            </div>
                        </div>

                        {{-- ações --}}
                        <div class="flex flex-col sm:flex-row gap-3">
                            @auth
                                <button type="button"
                                    class="js-abrir-review px-6 py-3 bg-[#6B5B9E] text-black font-black tracking-widest uppercase text-xs hover:bg-[#8674B8] transition">
                                    {{ $reviewUsuario ? 'Editar avaliação' : 'Avaliar' }}
                                </button>
                            @else
                                <a href="{{ route('gerenciador.usuario.login') }}"
                                    class="px-6 py-3 bg-[#6B5B9E] text-black font-black tracking-widest uppercase text-xs hover:bg-[#8674B8] transition text-center">
                                    Avaliar
                                </a>
                            @endauth

                            @auth
                                @if ($situacao ?? null)
                                    {{-- já está na biblioteca: exibe a situação (editar em breve) --}}
                                    <button type="button" disabled
                                        class="inline-flex items-center gap-2 px-6 py-3 bg-[#6B5B9E] text-black font-black tracking-widest uppercase text-xs cursor-not-allowed">
                                        {{ $situacao->situacao?->nome }}
                                        <span class="text-black/50 normal-case text-[10px] font-bold">· editar em breve</span>
                                    </button>
                                @else
                                    <button type="button" id="btn-add-colecao"
                                        class="px-6 py-3 border border-white/30 text-white font-black tracking-widest uppercase text-xs hover:border-[#6B5B9E] hover:text-[#6B5B9E] transition">
                                        + Minha lista
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('gerenciador.usuario.login') }}"
                                    class="px-6 py-3 border border-white/30 text-white font-black tracking-widest uppercase text-xs hover:border-[#6B5B9E] hover:text-[#6B5B9E] transition text-center">
                                    + Minha lista
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ============ SOBRE (descrição) ============ --}}
        <section class="border-b border-white/10">
            <div class="max-w-[1600px] mx-auto px-6 sm:px-12 py-10">
                <div class="flex items-center gap-3 mb-6">
                    <h2 class="text-xl sm:text-2xl font-black tracking-widest uppercase border-l-4 border-[#6B5B9E] pl-4">
                        Sobre o jogo
                    </h2>
                </div>

                <div class="space-y-4 text-white/70 leading-relaxed text-sm sm:text-base">
                    <p>
                        {{ $jogo->nome }} é um jogo @if ($jogo->generos->isNotEmpty()) de {{ $jogo->generos->pluck('nome')->take(2)->implode(' e ') }}@endif
                        desenvolvido pela {{ $jogo->desenvolvedora?->nome ?? 'desenvolvedora desconhecida' }}@if ($jogo->lancamento), lançado em {{ $jogo->lancamento->format('Y') }}@endif.
                    </p>
                    <p>
                        {{ $jogo->descricao }}
                    </p>
                </div>
            </div>
        </section>

        {{-- ============ AVALIAÇÕES ============ --}}
        <section>
            <div class="max-w-[1600px] mx-auto px-6 sm:px-12 py-10">
                <div class="flex items-center justify-between gap-3 mb-8">
                    <h2 class="text-xl sm:text-2xl font-black tracking-widest uppercase border-l-4 border-[#6B5B9E] pl-4">
                        Avaliações
                    </h2>
                    <span class="text-[10px] font-black tracking-widest uppercase text-white/40">
                        {{ $totalReviews }} {{ $totalReviews === 1 ? 'avaliação' : 'avaliações' }}
                    </span>
                </div>

                {{-- sua avaliação / CTA --}}
                @auth
                    @if ($reviewUsuario)
                        <div class="bg-[#1C1B26] border border-[#6B5B9E]/40 p-5 sm:p-6 mb-8">
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div class="flex gap-4 min-w-0">
                                    <div
                                        class="w-12 h-12 flex-shrink-0 bg-[#11101A] border border-white/10 overflow-hidden flex items-center justify-center text-[#6B5B9E] font-black text-lg uppercase">
                                        @if (auth()->user()->url_imagem_pequena)
                                            <img src="{{ Storage::url(auth()->user()->url_imagem_pequena) }}"
                                                alt="{{ auth()->user()->nome }}" class="w-full h-full object-cover">
                                        @else
                                            {{ mb_substr(auth()->user()->nome, 0, 1) }}
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-bold text-sm tracking-wide truncate">{{ auth()->user()->nome }}</span>
                                            <span class="flex-shrink-0 text-[9px] font-black tracking-widest uppercase bg-[#6B5B9E] text-black px-1.5 py-0.5">Você</span>
                                        </div>
                                        <div class="text-[#6B5B9E] text-lg tracking-widest leading-none">
                                            {{ $estrelas($reviewUsuario->nota) }}
                                            <span class="text-white/40 text-xs font-bold ml-1">{{ number_format((float) $reviewUsuario->nota, 1) }}/5</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-2 flex-shrink-0">
                                    <button type="button"
                                        class="js-abrir-review px-4 py-2 border border-white/30 text-white font-black tracking-widest uppercase text-[10px] hover:border-[#6B5B9E] hover:text-[#6B5B9E] transition">
                                        Editar
                                    </button>
                                    <button type="button" id="btn-remover-review"
                                        class="px-4 py-2 border border-red-500/40 text-red-300 font-black tracking-widest uppercase text-[10px] hover:bg-red-500/10 transition">
                                        Remover
                                    </button>
                                </div>
                            </div>
                            @if ($reviewUsuario->review)
                                <p class="text-sm text-white/70 leading-relaxed">{{ $reviewUsuario->review }}</p>
                            @endif
                        </div>
                    @else
                        <div class="bg-[#1C1B26] border border-white/10 p-5 sm:p-6 mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <p class="text-sm text-white/60">Você ainda não avaliou este jogo.</p>
                            <button type="button"
                                class="js-abrir-review self-start sm:self-auto px-6 py-3 bg-[#6B5B9E] text-black font-black tracking-widest uppercase text-xs hover:bg-[#8674B8] transition">
                                Avaliar agora
                            </button>
                        </div>
                    @endif
                @else
                    <div class="bg-[#1C1B26] border border-white/10 p-5 sm:p-6 mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <p class="text-sm text-white/60">Entre na sua conta para avaliar este jogo.</p>
                        <a href="{{ route('gerenciador.usuario.login') }}"
                            class="self-start sm:self-auto px-6 py-3 bg-[#6B5B9E] text-black font-black tracking-widest uppercase text-xs hover:bg-[#8674B8] transition">
                            Entrar
                        </a>
                    </div>
                @endauth

                {{-- lista de avaliações --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-1">
                    @forelse ($outrasReviews as $review)
                        <article class="bg-[#1C1B26] p-5 flex gap-4">
                            <div
                                class="w-12 h-12 flex-shrink-0 bg-[#11101A] border border-white/10 overflow-hidden flex items-center justify-center text-[#6B5B9E] font-black text-lg uppercase">
                                @if ($review->usuario?->url_imagem_pequena)
                                    <img src="{{ Storage::url($review->usuario->url_imagem_pequena) }}"
                                        alt="{{ $review->usuario->nome }}" class="w-full h-full object-cover">
                                @else
                                    {{ mb_substr($review->usuario?->nome ?? '?', 0, 1) }}
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-3 mb-1">
                                    <a href="{{ route('gerenciador.usuario.perfil', $review->usuario?->id) }}" class="font-bold text-sm tracking-wide truncate">{{ $review->usuario?->nome ?? 'Usuário' }}</a>
                                    <span class="text-[10px] text-white/40 flex-shrink-0">{{ $review->created_at?->format('d/m/Y') }}</span>
                                </div>
                                <div class="text-[#6B5B9E] text-sm tracking-widest mb-2">{{ $estrelas($review->nota) }}</div>
                                @if ($review->review)
                                    <p class="text-sm text-white/60 leading-relaxed">{{ $review->review }}</p>
                                @endif
                            </div>
                        </article>
                    @empty
                        <div class="lg:col-span-2 py-12 text-center">
                            <p class="text-white/30 text-sm tracking-widest uppercase font-bold">
                                @if ($reviewUsuario) Ninguém mais avaliou este jogo @else Nenhuma avaliação ainda @endif
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

    </main>

    {{-- footer --}}
    <x-footer />

    {{-- ===== modal de avaliação (adicionar / editar) ===== --}}
    @auth
        <div id="modal-review" class="hidden fixed inset-0 z-50 bg-black/70 items-center justify-center px-4">
            <div class="w-full max-w-md bg-[#1C1B26] border border-white/10 p-6 sm:p-8">
                <h2 class="text-xl font-black tracking-widest uppercase border-l-4 border-[#6B5B9E] pl-4 mb-4">
                    {{ $reviewUsuario ? 'Editar avaliação' : 'Avaliar' }}
                </h2>
                <p class="text-sm text-white/60 mb-6">{{ $jogo->nome }}</p>

                <ul id="review-erros"
                    class="hidden mb-4 p-3 bg-red-500/10 border border-red-500/30 text-sm text-red-300 list-disc list-inside space-y-1">
                </ul>

                <label class="block text-[10px] font-black uppercase tracking-widest text-white/60 mb-2">Sua nota</label>
                <div id="estrelas-input" class="flex gap-1 text-3xl text-white/20 cursor-pointer select-none mb-5">
                    @for ($i = 1; $i <= 5; $i++)
                        <span data-valor="{{ $i }}" class="transition">★</span>
                    @endfor
                </div>
                <input type="hidden" id="nota-input" value="{{ $reviewUsuario->nota ?? '' }}">

                <label for="review-texto"
                    class="block text-[10px] font-black uppercase tracking-widest text-white/60 mb-2">
                    Comentário <span class="text-white/30 normal-case">(opcional)</span>
                </label>
                <textarea id="review-texto" rows="4" placeholder="Conte o que achou do jogo..."
                    class="w-full px-4 py-3 bg-[#11101A] border border-white/10 text-white placeholder-white/30 text-sm resize-none focus:outline-none focus:border-[#6B5B9E] transition">{{ $reviewUsuario->review ?? '' }}</textarea>

                <div class="flex gap-3 pt-6">
                    <button type="button" id="cancelar-review"
                        class="flex-1 py-3 border border-white/30 text-white font-black tracking-widest uppercase text-xs hover:border-white/60 transition">
                        Cancelar
                    </button>
                    <button type="button" id="salvar-review"
                        class="flex-1 py-3 bg-[#6B5B9E] text-black font-black tracking-widest uppercase text-xs hover:bg-[#8674B8] transition">
                        {{ $reviewUsuario ? 'Salvar' : 'Enviar' }}
                    </button>
                </div>
            </div>
        </div>

        <script>
            $(function () {
                const $modal = $('#modal-review');
                const $estrelas = $('#estrelas-input span');
                let nota = parseInt($('#nota-input').val()) || 0;

                function pintar(n) {
                    $estrelas.each(function () {
                        const v = parseInt($(this).data('valor'));
                        $(this).toggleClass('text-[#6B5B9E]', v <= n);
                        $(this).toggleClass('text-white/20', v > n);
                    });
                }
                pintar(nota);

                $estrelas
                    .on('mouseenter', function () { pintar(parseInt($(this).data('valor'))); })
                    .on('mouseleave', function () { pintar(nota); })
                    .on('click', function () {
                        nota = parseInt($(this).data('valor'));
                        $('#nota-input').val(nota);
                        pintar(nota);
                    });

                function fechar() { $modal.addClass('hidden').removeClass('flex'); }
                $('.js-abrir-review').on('click', function () {
                    $('#review-erros').addClass('hidden').empty();
                    $modal.removeClass('hidden').addClass('flex');
                });
                $('#cancelar-review').on('click', fechar);
                $modal.on('click', function (e) { if (e.target === this) fechar(); });

                $('#salvar-review').on('click', function () {
                    $('#review-erros').addClass('hidden').empty();
                    if (!nota) {
                        $('#review-erros').append('<li>Escolha uma nota de 1 a 5.</li>').removeClass('hidden');
                        return;
                    }

                    const dados = {
                        jogo_id: {{ $jogo->id }},
                        usuario_id: {{ auth()->id() }},
                        nota: nota,
                        review: $('#review-texto').val()
                    };
                    @if ($reviewUsuario)
                        dados.id = {{ $reviewUsuario->id }};
                    @endif

                    $.ajax({
                        url: @if ($reviewUsuario) "{{ route('review.editar', $reviewUsuario->id) }}" @else "{{ route('review.criar') }}" @endif,
                        method: 'POST',
                        data: dados,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        success: function () { window.location.reload(); },
                        error: function (xhr) {
                            if (xhr.status === 422 && xhr.responseJSON?.errors) {
                                Object.values(xhr.responseJSON.errors).flat().forEach(function (m) {
                                    $('#review-erros').append('<li>' + m + '</li>');
                                });
                            } else {
                                $('#review-erros').append('<li>' + (xhr.responseJSON?.message || 'Erro ao enviar avaliação.') + '</li>');
                            }
                            $('#review-erros').removeClass('hidden');
                        }
                    });
                });

                @if ($reviewUsuario)
                    $('#btn-remover-review').on('click', function () {
                        if (!confirm('Remover sua avaliação?')) return;
                        $.ajax({
                            url: "{{ route('review.remover', $reviewUsuario->id) }}",
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            success: function () { window.location.reload(); },
                            error: function (xhr) { alert(xhr.responseJSON?.message || 'Erro ao remover.'); }
                        });
                    });
                @endif
            });
        </script>
    @endauth

    {{-- ===== modal: adicionar à coleção ===== --}}
    @auth
        @if (!($situacao ?? null))
            <div id="modal-colecao" class="hidden fixed inset-0 z-50 bg-black/70 items-center justify-center px-4">
                <div class="w-full max-w-md bg-[#1C1B26] border border-white/10 p-6 sm:p-8">
                    <h2 class="text-xl font-black tracking-widest uppercase border-l-4 border-[#6B5B9E] pl-4 mb-4">
                        Adicionar à coleção
                    </h2>
                    <p class="text-sm text-white/60 mb-6">{{ $jogo->nome }}</p>

                    <ul id="colecao-erros"
                        class="hidden mb-4 p-3 bg-red-500/10 border border-red-500/30 text-sm text-red-300 list-disc list-inside space-y-1">
                    </ul>

                    <label for="situacao-select"
                        class="block text-[10px] font-black uppercase tracking-widest text-white/60 mb-2">Situação</label>
                    <select id="situacao-select"
                        class="w-full px-4 py-3 bg-[#11101A] border border-white/10 text-white focus:outline-none focus:border-[#6B5B9E] transition [color-scheme:dark]">
                        @forelse (($situacoes ?? collect()) as $sit)
                            <option value="{{ $sit->id }}">{{ $sit->nome }}</option>
                        @empty
                            <option value="" disabled>Nenhuma situação cadastrada</option>
                        @endforelse
                    </select>

                    <div class="flex gap-3 pt-6">
                        <button type="button" id="cancelar-colecao"
                            class="flex-1 py-3 border border-white/30 text-white font-black tracking-widest uppercase text-xs hover:border-white/60 transition">
                            Cancelar
                        </button>
                        <button type="button" id="salvar-colecao"
                            class="flex-1 py-3 bg-[#6B5B9E] text-black font-black tracking-widest uppercase text-xs hover:bg-[#8674B8] transition">
                            Adicionar
                        </button>
                    </div>
                </div>
            </div>

            <script>
                $(function () {
                    const $modal = $('#modal-colecao');

                    function fechar() { $modal.addClass('hidden').removeClass('flex'); }

                    $('#btn-add-colecao').on('click', function () {
                        $('#colecao-erros').addClass('hidden').empty();
                        $modal.removeClass('hidden').addClass('flex');
                    });
                    $('#cancelar-colecao').on('click', fechar);
                    $modal.on('click', function (e) { if (e.target === this) fechar(); });

                    $('#salvar-colecao').on('click', function () {
                        $('#colecao-erros').addClass('hidden').empty();

                        $.ajax({
                            url: "{{ route('colecao.adicionar') }}",
                            method: 'POST',
                            data: {
                                jogoID: {{ $jogo->id }},
                                situacaoID: $('#situacao-select').val()
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            success: function () { window.location.reload(); },
                            error: function (xhr) {
                                const msg = xhr.responseJSON?.message || 'Erro ao adicionar à coleção.';
                                $('#colecao-erros').append('<li>' + msg + '</li>').removeClass('hidden');
                            }
                        });
                    });
                });
            </script>
        @endif
    @endauth

</body>

</html>
