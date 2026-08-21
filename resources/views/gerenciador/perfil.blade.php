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

    <x-navbar />

    @php
        // dono = tem alguém logado E é o mesmo usuário do perfil aberto.
        // guest -> auth()->id() é null -> false -> botão/modal nem são renderizados.
        $ehDono = auth()->check() && auth()->id() === $usuario->id;
    @endphp

    <main class="flex-1">

        {{-- seção 1: identidade do usuário --}}
        <section class="max-w-[1600px] mx-auto px-6 sm:px-12 pt-12 pb-10">
            <div class="flex flex-col sm:flex-row items-start gap-6 sm:gap-8">

                {{-- avatar --}}
                <div id="avatar-container"
                    class="w-32 h-32 sm:w-40 sm:h-40 bg-[#1C1B26] border border-white/10 flex items-center justify-center flex-shrink-0 overflow-hidden">
                    @if ($usuario->url_imagem_grande)
                        <img id="avatar-img" src="{{ imagem_url($usuario->url_imagem_grande) }}"
                            alt="Foto de {{ $usuario->nome }}" class="w-full h-full object-cover">
                    @else
                        <span
                            class="text-5xl sm:text-6xl font-black text-[#6B5B9E] uppercase">{{ mb_substr($usuario->nome, 0, 1) }}</span>
                    @endif
                </div>

                {{-- nome + stats --}}
                <div class="flex-1 w-full">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-[10px] font-black tracking-widest uppercase text-white/40 mb-2">Jogador</p>
                            <div class="flex items-center gap-3 mb-3">
                                <h1 id="perfil-nome"
                                    class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight uppercase leading-[0.95]">
                                    {{ $usuario->nome }}
                                </h1>
                                @if ($usuario->admin)
                                    <span title="Administrador" aria-label="Administrador"
                                        class="flex-shrink-0 text-[#6B5B9E]">
                                        <x-icon name="escudo-check" class="w-7 h-7 sm:w-8 sm:h-8" />
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm text-white/60 mb-6">{{ $usuario->email }} · Membro desde
                                {{ $usuario->created_at?->format('Y') }}</p>
                        </div>

                        @if ($ehDono)
                            <button id="abrir-editar"
                                class="flex-shrink-0 px-5 py-2 border border-white/30 text-white font-black tracking-widest uppercase text-[10px] hover:border-[#6B5B9E] hover:text-[#6B5B9E] transition">
                                Editar perfil
                            </button>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-1 max-w-md">
                        <div class="bg-[#1C1B26] py-6 text-center">
                            <div class="text-3xl sm:text-4xl font-black text-[#6B5B9E]">{{ $totalJogos }}</div>
                            <p class="text-[10px] uppercase tracking-widest text-white/40 mt-2 font-bold">Jogos</p>
                        </div>
                        <div class="bg-[#1C1B26] py-6 text-center">
                            <div class="text-3xl sm:text-4xl font-black text-[#6B5B9E]">{{ $totalReviews }}</div>
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
                    <h2
                        class="text-xl sm:text-2xl font-black tracking-widest uppercase border-l-4 border-[#6B5B9E] pl-4">
                        Últimos jogos
                    </h2>
                    <a href="{{ route('colecao.visualizar', $usuario->id) }}"
                        class="flex-shrink-0 px-5 py-2 border border-white/30 text-white font-black tracking-widest uppercase text-[10px] hover:border-[#6B5B9E] hover:text-[#6B5B9E] transition">
                        Ver coleção
                    </a>
                </div>

                <div class="flex gap-1 overflow-x-auto pb-2">
                    @forelse ($ultimosJogos as $item)
                        @continue(!$item->jogo)
                        <a href="{{ route('catalogo.jogo.visualizar', $item->jogo->id) }}"
                            class="group flex-shrink-0 w-44 sm:w-52 bg-[#1C1B26] hover:bg-[#25232F] transition flex flex-col">
                            <div class="aspect-[3/4] bg-[#11101A] overflow-hidden border-b border-white/5">
                                @if ($item->jogo->capa(false))
                                    <img src="{{ $item->jogo->capa(false) }}"
                                        alt="Capa de {{ $item->jogo->nome }}" loading="lazy" decoding="async"
                                        class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="text-white/15 text-[10px] tracking-widest uppercase">Sem
                                            capa</span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4 flex-1 flex flex-col">
                                @if ($item->situacao)
                                    <span
                                        class="self-start inline-block px-2 py-0.5 mb-2 text-[10px] font-black tracking-widest uppercase bg-[#6B5B9E] text-black">{{ $item->situacao->nome }}</span>
                                @endif
                                <h3 class="text-sm font-bold leading-snug line-clamp-2">{{ $item->jogo->nome }}</h3>
                                <span class="text-[10px] text-white/40 mt-auto pt-3">Adicionado em
                                    {{ $item->created_at?->format('d/m/Y') }}</span>
                            </div>
                        </a>
                    @empty
                        <div class="w-full py-12 text-center">
                            <p class="text-white/30 text-sm tracking-widest uppercase font-bold">Nenhum jogo na coleção
                                ainda</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- seção 3: últimas reviews --}}
        <section class="border-t border-white/10">
            <div class="max-w-[1600px] mx-auto px-6 sm:px-12 py-10">
                <div class="flex items-center justify-between mb-8">
                    <h2
                        class="text-xl sm:text-2xl font-black tracking-widest uppercase border-l-4 border-[#6B5B9E] pl-4">
                        Últimas reviews
                    </h2>
                    <a href="{{ route('review.usuario', $usuario->id) }}"
                        class="px-5 py-2 border border-white/30 text-white font-black tracking-widest uppercase text-[10px] hover:border-[#6B5B9E] hover:text-[#6B5B9E] transition">
                        Ver todas
                    </a>
                </div>

                @if ($reviewsRecentes->isNotEmpty())
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-1">
                        @foreach ($reviewsRecentes as $review)
                            @php
                                $nota = (int) round((float) $review->nota);
                                $estrelas = str_repeat('★', $nota) . str_repeat('☆', 5 - $nota);
                            @endphp
                            <a href="{{ $review->jogo ? route('catalogo.jogo.visualizar', $review->jogo->id) : '#' }}"
                                class="bg-[#1C1B26] hover:bg-[#25232F] transition p-5 flex gap-4">
                                <div
                                    class="w-16 sm:w-20 aspect-[3/4] flex-shrink-0 bg-[#11101A] border border-white/5 overflow-hidden flex items-center justify-center">
                                    @if ($review->jogo?->capa(false))
                                        <img src="{{ $review->jogo->capa(false) }}"
                                            alt="Capa de {{ $review->jogo->nome }}" loading="lazy" decoding="async"
                                            class="w-full h-full object-cover">
                                    @else
                                        <span class="text-white/15 text-[10px] tracking-widest uppercase">Sem capa</span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0 flex flex-col">
                                    <div class="flex items-start justify-between gap-3 mb-1">
                                        <h3 class="text-base font-bold leading-snug line-clamp-1">
                                            {{ $review->jogo?->nome ?? 'Jogo removido' }}
                                        </h3>
                                        <span class="text-[10px] text-white/40 flex-shrink-0">
                                            {{ $review->created_at?->format('d/m/Y') }}
                                        </span>
                                    </div>
                                    <div class="text-[#6B5B9E] text-sm font-black tracking-widest mb-2">
                                        {{ $estrelas }} <span
                                            class="text-white/40 text-[10px] font-bold ml-1">{{ number_format((float) $review->nota, 1) }}/5</span>
                                    </div>
                                    @if ($review->review)
                                        <p class="text-sm text-white/60 leading-relaxed line-clamp-2">{{ $review->review }}</p>
                                    @else
                                        <p class="text-sm text-white/25 italic leading-relaxed">Sem comentário.</p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="py-12 text-center">
                        <p class="text-white/30 text-sm tracking-widest uppercase font-bold">Nenhuma review ainda</p>
                    </div>
                @endif
            </div>
        </section>

    </main>

    {{-- footer --}}
    <x-footer />

    @if ($ehDono)
        {{-- modal de edição do perfil (só o dono recebe esse HTML) --}}
        <div id="modal-editar" class="hidden fixed inset-0 z-50 bg-black/70 items-center justify-center px-4">
            <div class="w-full max-w-md bg-[#1C1B26] border border-white/10 p-6 sm:p-8">
                <h2 class="text-xl font-black tracking-widest uppercase border-l-4 border-[#6B5B9E] pl-4 mb-6">
                    Editar perfil
                </h2>

                <ul id="editar-erros"
                    class="hidden mb-4 p-3 bg-red-500/10 border border-red-500/30 text-sm text-red-300 list-disc list-inside space-y-1">
                </ul>

                <form id="form-editar-perfil" class="space-y-5">
                    <div>
                        <label for="editar-nome"
                            class="block text-[10px] font-black uppercase tracking-widest text-white/60 mb-2">Nome</label>
                        <input type="text" id="editar-nome" value="{{ $usuario->nome }}"
                            class="w-full px-4 py-3 bg-[#11101A] border border-white/10 text-white placeholder-white/30 focus:outline-none focus:border-[#6B5B9E] transition">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-white/60 mb-2">Foto de
                            perfil</label>
                        <div class="flex items-center gap-4">
                            <div
                                class="w-24 h-24 flex-shrink-0 bg-[#11101A] border border-white/10 overflow-hidden flex items-center justify-center">
                                <img id="preview-avatar"
                                    src="{{ $usuario->url_imagem_grande ? imagem_url($usuario->url_imagem_grande) : '' }}"
                                    class="w-full h-full object-cover {{ $usuario->url_imagem_grande ? '' : 'hidden' }}">
                                <span id="preview-avatar-vazio"
                                    class="text-[10px] text-white/30 uppercase tracking-widest {{ $usuario->url_imagem_grande ? 'hidden' : '' }}">Sem
                                    foto</span>
                            </div>
                            <input type="file" id="editar-imagem" accept="image/jpeg,image/png,image/webp"
                                class="text-xs text-white/60 file:mr-3 file:py-2 file:px-4 file:border-0 file:bg-[#6B5B9E] file:text-black file:font-black file:uppercase file:tracking-widest file:text-[10px] file:cursor-pointer">
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" id="cancelar-editar"
                            class="flex-1 py-3 border border-white/30 text-white font-black tracking-widest uppercase text-xs hover:border-white/60 transition">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="flex-1 py-3 bg-[#6B5B9E] text-black font-black tracking-widest uppercase text-xs hover:bg-[#8674B8] transition">
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- jQuery já foi carregado pelo x-navbar (bloco @auth), o dono está sempre autenticado --}}
        <script>
            $(function() {
                const $modal = $('#modal-editar');

                function abrirModal() {
                    $('#editar-erros').addClass('hidden').empty();
                    $modal.removeClass('hidden').addClass('flex');
                }

                function fecharModal() {
                    $modal.addClass('hidden').removeClass('flex');
                }

                $('#abrir-editar').on('click', abrirModal);
                $('#cancelar-editar').on('click', fecharModal);

                // fecha ao clicar fora do painel (no overlay)
                $modal.on('click', function(e) {
                    if (e.target === this) fecharModal();
                });

                // preview local da nova imagem
                $('#editar-imagem').on('change', function() {
                    const arquivo = this.files[0];
                    if (!arquivo) return;

                    $('#preview-avatar').attr('src', URL.createObjectURL(arquivo)).removeClass('hidden');
                    $('#preview-avatar-vazio').addClass('hidden');
                });

                $('#form-editar-perfil').on('submit', function(e) {
                    e.preventDefault();
                    $('#editar-erros').addClass('hidden').empty();

                    const dados = new FormData();
                    dados.append('id', '{{ $usuario->id }}');
                    dados.append('nome', $('#editar-nome').val());

                    const arquivo = $('#editar-imagem')[0].files[0];
                    if (arquivo) dados.append('imagem', arquivo);

                    $.ajax({
                        url: "{{ route('gerenciador.usuario.atualizar', $usuario->id) }}",
                        method: 'POST',
                        data: dados,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        success: function(response) {
                            const u = response.usuario;

                            $('#perfil-nome').text(u.nome);

                            if (u.imagem_grande) {
                                $('#avatar-container').html(
                                    '<img id="avatar-img" class="w-full h-full object-cover" alt="Foto de perfil" src="' +
                                    u.imagem_grande + '">'
                                );
                            }

                            fecharModal();
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                const erros = xhr.responseJSON.errors;
                                for (const campo in erros) {
                                    erros[campo].forEach(function(msg) {
                                        $('#editar-erros').append('<li>' + msg + '</li>');
                                    });
                                }
                            } else {
                                $('#editar-erros').append('<li>' + (xhr.responseJSON?.message ||
                                    'Erro inesperado.') + '</li>');
                            }
                            $('#editar-erros').removeClass('hidden');
                        }
                    });
                });
            });
        </script>
    @endif

</body>

</html>
