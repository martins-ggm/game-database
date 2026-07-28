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
        // ---- placeholders fixos (o back ainda não tem esses campos) ----
        $notaMedia = 4.3;
        $totalAvaliacoes = 128;
        $avaliacoes = [
            ['autor' => 'Ryn', 'nota' => 5, 'data' => '2026-05-21', 'texto' => 'Experiência absurda do início ao fim. Level design impecável e uma trilha que não sai da cabeça.'],
            ['autor' => 'Kaz', 'nota' => 4, 'data' => '2026-05-18', 'texto' => 'Muito bom, mas o ritmo cai um pouco no meio. Ainda assim, recomendo demais.'],
            ['autor' => 'Mel', 'nota' => 5, 'data' => '2026-05-10', 'texto' => 'Um dos melhores que já joguei. Cada detalhe foi pensado com carinho.'],
        ];

        // estrelas a partir de uma nota (0–5) → "★★★★☆"
        $estrelas = fn (float $n) => str_repeat('★', (int) round($n)) . str_repeat('☆', 5 - (int) round($n));
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

                        {{-- média de avaliação (placeholder) --}}
                        <div class="inline-flex items-center gap-4 bg-[#1C1B26] border border-white/10 px-5 py-4 mb-8">
                            <div class="text-4xl font-black text-[#6B5B9E] leading-none">{{ number_format($notaMedia, 1) }}</div>
                            <div>
                                <div class="text-[#6B5B9E] text-lg tracking-widest leading-none">{{ $estrelas($notaMedia) }}</div>
                                <p class="text-[10px] uppercase tracking-widest text-white/40 mt-1 font-bold">
                                    {{ $totalAvaliacoes }} avaliações
                                    <span class="ml-1 text-[#6B5B9E]/70">· em breve</span>
                                </p>
                            </div>
                        </div>

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

                        {{-- ações (placeholder) --}}
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button type="button" disabled
                                class="px-6 py-3 bg-[#6B5B9E] text-black font-black tracking-widest uppercase text-xs opacity-50 cursor-not-allowed">
                                Avaliar
                            </button>
                            <button type="button" disabled
                                class="px-6 py-3 border border-white/30 text-white font-black tracking-widest uppercase text-xs opacity-50 cursor-not-allowed">
                                + Minha lista
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ============ SOBRE (descrição — placeholder) ============ --}}
        <section class="border-b border-white/10">
            <div class="max-w-[1600px] mx-auto px-6 sm:px-12 py-10">
                <div class="flex items-center gap-3 mb-6">
                    <h2 class="text-xl sm:text-2xl font-black tracking-widest uppercase border-l-4 border-[#6B5B9E] pl-4">
                        Sobre o jogo
                    </h2>
                    <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-widest bg-[#6B5B9E]/15 text-[#6B5B9E] border border-[#6B5B9E]/30">
                        Em breve
                    </span>
                </div>

                <div class="max-w-3xl space-y-4 text-white/70 leading-relaxed text-sm sm:text-base">
                    <p>
                        {{ $jogo->nome }} é um jogo @if ($jogo->generos->isNotEmpty()) de {{ $jogo->generos->pluck('nome')->take(2)->implode(' e ') }}@endif
                        desenvolvido pela {{ $jogo->desenvolvedora?->nome ?? 'desenvolvedora desconhecida' }}@if ($jogo->lancamento), lançado em {{ $jogo->lancamento->format('Y') }}@endif.
                    </p>
                    <p>
                        A descrição completa deste jogo será exibida aqui em breve — com sinopse, principais
                        características e destaques da crítica. Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                        sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                        nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                    </p>
                </div>
            </div>
        </section>

        {{-- ============ AVALIAÇÕES (placeholder) ============ --}}
        <section>
            <div class="max-w-[1600px] mx-auto px-6 sm:px-12 py-10">
                <div class="flex items-center gap-3 mb-8">
                    <h2 class="text-xl sm:text-2xl font-black tracking-widest uppercase border-l-4 border-[#6B5B9E] pl-4">
                        Avaliações
                    </h2>
                    <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-widest bg-[#6B5B9E]/15 text-[#6B5B9E] border border-[#6B5B9E]/30">
                        Em breve
                    </span>
                </div>

                {{-- caixa "deixe sua avaliação" --}}
                @auth
                    <div class="bg-[#1C1B26] border border-white/10 p-5 sm:p-6 mb-8">
                        <p class="text-[10px] font-black tracking-widest uppercase text-white/60 mb-3">Sua avaliação</p>
                        <div class="text-2xl text-white/20 tracking-widest mb-3 select-none">☆☆☆☆☆</div>
                        <textarea rows="3" disabled placeholder="Conte o que achou do jogo... (em breve)"
                            class="w-full px-4 py-3 bg-[#11101A] border border-white/10 text-white placeholder-white/30 text-sm resize-none opacity-60 cursor-not-allowed"></textarea>
                        <div class="flex justify-end mt-3">
                            <button type="button" disabled
                                class="px-6 py-3 bg-[#6B5B9E] text-black font-black tracking-widest uppercase text-xs opacity-50 cursor-not-allowed">
                                Enviar avaliação
                            </button>
                        </div>
                    </div>
                @else
                    <div class="bg-[#1C1B26] border border-white/10 p-5 sm:p-6 mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <p class="text-sm text-white/60">Entre na sua conta para avaliar este jogo.</p>
                        <a href="{{ route('gerenciador.usuario.login') }}"
                            class="self-start sm:self-auto px-6 py-3 bg-[#6B5B9E] text-black font-black tracking-widest uppercase text-xs hover:bg-[#8674B8] transition">
                            Entrar
                        </a>
                    </div>
                @endauth

                {{-- lista de avaliações (placeholder) --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-1">
                    @foreach ($avaliacoes as $avaliacao)
                        <article class="bg-[#1C1B26] p-5 flex gap-4">
                            <div
                                class="w-12 h-12 flex-shrink-0 bg-[#11101A] border border-white/10 flex items-center justify-center text-[#6B5B9E] font-black text-lg uppercase">
                                {{ mb_substr($avaliacao['autor'], 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-3 mb-1">
                                    <span class="font-bold text-sm tracking-wide">{{ $avaliacao['autor'] }}</span>
                                    <span class="text-[10px] text-white/40 flex-shrink-0">
                                        {{ \Illuminate\Support\Carbon::parse($avaliacao['data'])->format('d/m/Y') }}
                                    </span>
                                </div>
                                <div class="text-[#6B5B9E] text-sm tracking-widest mb-2">{{ $estrelas($avaliacao['nota']) }}</div>
                                <p class="text-sm text-white/60 leading-relaxed">{{ $avaliacao['texto'] }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

    </main>

    {{-- footer --}}
    <footer class="border-t border-white/10">
        <div
            class="max-w-[1600px] mx-auto px-6 sm:px-12 py-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-[10px] tracking-widest text-white/40 uppercase font-bold">&copy; 2026 Game Database</p>
            <p class="text-[10px] tracking-widest text-white/40 uppercase font-bold">Built with the SISP architecture</p>
        </div>
    </footer>

</body>

</html>
