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
                    Sua biblioteca de jogos — busque pelo nome.
                </p>

                <div id="busca-wrapper" class="relative">
                    <form id="form-busca" class="flex flex-col sm:flex-row gap-1">
                        <input type="search" id="busca-jogo" name="busca" autocomplete="off"
                            placeholder="Buscar pelo nome do jogo..."
                            class="flex-1 px-4 py-3 bg-[#1C1B26] border border-white/10 text-white placeholder-white/30 focus:outline-none focus:border-[#6B5B9E] transition">
                        <button type="submit"
                            class="px-8 py-3 bg-[#6B5B9E] text-black font-black tracking-widest uppercase text-xs hover:bg-[#8674B8] transition">
                            Buscar
                        </button>
                    </form>

                    {{-- dropdown de resultados (jQuery preenche) --}}
                    <div id="resultados-busca"
                        class="hidden absolute z-30 left-0 right-0 mt-1 bg-[#1C1B26] border border-white/10 max-h-96 overflow-y-auto shadow-2xl shadow-black/50">
                    </div>
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
                        <div class="text-4xl sm:text-5xl font-black text-[#6B5B9E]">{{ $totalPlataformas }}</div>
                        <p class="text-[10px] uppercase tracking-widest text-white/40 mt-2 font-bold">Plataformas
                        </p>
                    </div>
                    <div class="bg-[#1C1B26] py-8 text-center">
                        <div class="text-4xl sm:text-5xl font-black text-[#6B5B9E]"> {{ $totalEmpresas }}</div>
                        <p class="text-[10px] uppercase tracking-widest text-white/40 mt-2 font-bold">Empresas
                        </p>
                    </div>
                    <div class="bg-[#1C1B26] py-8 text-center">
                        <div class="text-4xl sm:text-5xl font-black text-[#6B5B9E]">{{ $totalGeneros }}</div>
                        <p class="text-[10px] uppercase tracking-widest text-white/40 mt-2 font-bold">Generos</p>
                    </div>
                    <div class="bg-[#1C1B26] py-8 text-center">
                        <div class="text-4xl sm:text-5xl font-black text-[#6B5B9E]">{{ $totalJogos }}</div>
                        <p class="text-[10px] uppercase tracking-widest text-white/40 mt-2 font-bold">Jogos</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- catálogo --}}
        <section id="lancamentos" class="max-w-[1600px] mx-auto px-6 sm:px-12 pt-8 pb-16">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl sm:text-2xl font-black tracking-widest uppercase border-l-4 border-[#6B5B9E] pl-4">
                    Em alta
                </h2>
                <a href="#"
                    class="px-5 py-2 border border-white/30 text-white font-black tracking-widest uppercase text-[10px] hover:border-[#6B5B9E] hover:text-[#6B5B9E] transition">VER
                    TUDO</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-1">

                @forelse ($emAlta as $jogo)
                    <a href="{{ route('catalogo.jogo.visualizar', $jogo->id) }}" class="bg-[#1C1B26] hover:bg-[#25232F] transition cursor-pointer flex flex-col">
                        <div class="aspect-[3/4] bg-[#11101A] border-b border-white/5 overflow-hidden">
                            @if ($jogo->capa())
                                <img src="{{ $jogo->capa() }}"
                                    alt="Capa de {{ $jogo->nome }}" class="w-full h-full object-cover">
                            @else
                                <div
                                    class="w-full h-full flex items-center justify-center text-white/15 text-xs tracking-widest uppercase">
                                    Sem capa
                                </div>
                            @endif
                        </div>
                        <div class="p-5 flex-1 flex flex-col">
                            @if ($jogo->generos->isNotEmpty())
                                <span
                                    class="self-start inline-block px-2 py-0.5 mb-3 text-[10px] font-black tracking-widest uppercase bg-[#6B5B9E] text-black">{{ $jogo->generos->first()->nome }}</span>
                            @endif
                            <h3 class="text-base font-bold leading-snug">{{ $jogo->nome }}</h3>
                            <div class="flex items-center justify-between mt-auto pt-4 border-t border-white/10">
                                <span class="text-[10px] font-black tracking-widest uppercase text-white/60">VER
                                    MAIS</span>
                                <span class="text-[10px] text-white/40">{{ $jogo->lancamento?->format('Y') ?? '—' }}</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div
                        class="col-span-full bg-[#1C1B26] py-12 text-center text-white/30 text-xs uppercase tracking-widest">
                        Nenhum lançamento recente
                    </div>
                @endforelse

            </div>
        </section>

    </main>

    {{-- footer --}}
    <x-footer />

    {{-- jQuery: o dashboard é público e o navbar só carrega o jQuery pra quem está logado (@auth).
         Então garante o jQuery aqui pro visitante também — sem carregar 2x pra quem já tem. --}}
    <script>window.jQuery || document.write('<script src="https://code.jquery.com/jquery-3.7.1.min.js"><\/script>');</script>
    <script>
        $(function() {
            const urlBusca = "{{ route('catalogo.jogo.buscaSimples') }}";
            const urlVisualizarBase = "{{ route('catalogo.jogo.visualizar', ['id' => 'ID_PLACEHOLDER']) }}";

            const $input = $('#busca-jogo');
            const $resultados = $('#resultados-busca');
            let timer = null;
            let req = null;

            function escapar(texto) {
                return $('<div>').text(texto ?? '').html();
            }

            function esconder() {
                $resultados.addClass('hidden').empty();
            }

            function itemHtml(jogo) {
                const url = urlVisualizarBase.replace('ID_PLACEHOLDER', jogo.id);
                const capa = jogo.imagem_pequena ?
                    `<img src="${jogo.imagem_pequena}" alt="" class="w-10 h-14 object-cover border border-white/10 flex-shrink-0">` :
                    `<div class="w-10 h-14 bg-[#11101A] border border-white/10 flex-shrink-0"></div>`;
                const ano = jogo.lancamento ? jogo.lancamento.slice(0, 4) : '';

                return `
                    <a href="${url}"
                        class="flex items-center gap-3 px-4 py-3 hover:bg-[#25232F] transition border-b border-white/5 last:border-0">
                        ${capa}
                        <div class="min-w-0">
                            <div class="text-sm font-bold text-white truncate">${escapar(jogo.nome)}</div>
                            ${ano ? `<div class="text-[10px] text-white/40 uppercase tracking-widest">${ano}</div>` : ''}
                        </div>
                    </a>`;
            }

            $input.on('input', function() {
                const termo = $(this).val().trim();
                clearTimeout(timer);

                if (termo.length < 2) {
                    esconder();
                    return;
                }

                timer = setTimeout(function() {
                    if (req) req.abort();

                    req = $.ajax({
                        url: urlBusca,
                        method: 'GET',
                        data: { nome: termo },
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        success: function(response) {
                            const jogos = response.jogos || [];

                            if (jogos.length === 0) {
                                $resultados.html(
                                    '<div class="px-4 py-3 text-xs text-white/40 uppercase tracking-widest">Nenhum jogo encontrado</div>'
                                ).removeClass('hidden');
                                return;
                            }

                            $resultados.html(jogos.map(itemHtml).join('')).removeClass('hidden');
                        },
                        error: function(xhr, status) {
                            if (status === 'abort') return;
                            esconder();
                        }
                    });
                }, 300);
            });

            // busca é ao vivo — não deixa o form recarregar a página
            $('#form-busca').on('submit', function(e) {
                e.preventDefault();
            });

            // fecha o dropdown ao clicar fora ou apertar Esc
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#busca-wrapper').length) esconder();
            });
            $input.on('keydown', function(e) {
                if (e.key === 'Escape') esconder();
            });
        });
    </script>

</body>

</html>
