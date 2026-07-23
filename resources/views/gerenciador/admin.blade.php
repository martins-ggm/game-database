<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo — Game Database</title>
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

        {{-- hero --}}
        <section class="max-w-[1600px] mx-auto px-6 sm:px-12 pt-12 pb-8">
            <span
                class="inline-block px-2 py-0.5 mb-4 text-[10px] font-black tracking-widest uppercase bg-[#6B5B9E] text-black">Restrito</span>
            <h1
                class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight uppercase leading-[0.95] border-l-4 border-[#6B5B9E] pl-4">
                Painel Administrativo
            </h1>
            <p class="text-sm sm:text-base text-white/60 mt-4 ml-5 max-w-2xl">
                Cadastre e gerencie os dados que abastecem a biblioteca — jogos, desenvolvedoras, plataformas e gêneros.
            </p>
        </section>

        {{-- ações rápidas --}}
        <section class="max-w-[1600px] mx-auto px-6 sm:px-12 pb-10">
            <h2
                class="text-xs sm:text-sm font-black tracking-widest uppercase text-white/60 border-l-4 border-[#6B5B9E] pl-4 mb-4">
                Ações rápidas
            </h2>
            <div class="flex flex-col sm:flex-row flex-wrap gap-1">
                <a href="{{ route('catalogo.jogo.novo') }}"
                    class="px-6 py-3 bg-[#6B5B9E] text-black font-black tracking-widest uppercase text-xs hover:bg-[#8674B8] transition">
                    + Novo jogo
                </a>
                <a href="{{ Route('catalogo.desenvolvedora.novo') }}"
                    class="px-6 py-3 border border-white/30 text-white font-black tracking-widest uppercase text-xs hover:border-[#6B5B9E] hover:text-[#6B5B9E] transition">
                    + Desenvolvedora
                </a>
                <a href="{{ Route('catalogo.plataforma.novo') }}"
                    class="px-6 py-3 border border-white/30 text-white font-black tracking-widest uppercase text-xs hover:border-[#6B5B9E] hover:text-[#6B5B9E] transition">
                    + Plataforma
                </a>
                <a href="{{ Route('catalogo.genero.novo') }}"
                    class="px-6 py-3 border border-white/30 text-white font-black tracking-widest uppercase text-xs hover:border-[#6B5B9E] hover:text-[#6B5B9E] transition">
                    + Gênero
                </a>
            </div>
        </section>

        {{-- módulos --}}
        <section class="border-t border-white/10">
            <div class="max-w-[1600px] mx-auto px-6 sm:px-12 py-10">
                <h2
                    class="text-xl sm:text-2xl font-black tracking-widest uppercase border-l-4 border-[#6B5B9E] pl-4 mb-8">
                    Módulos
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-1">

                    @php
                        $modulos = [
                            [
                                'titulo' => 'Jogos',
                                'total' => $totalJogos,
                                'descricao' =>
                                    'Cadastre títulos, edite metadados, vincule a plataformas e desenvolvedoras.',
                            ],
                            [
                                'titulo' => 'Desenvolvedoras',
                                'total' => $totalDesenvolvedoras,
                                'descricao' => 'Mantenha o catálogo de estúdios e suas informações públicas.',
                            ],
                            [
                                'titulo' => 'Plataformas',
                                'total' => $totalPlataformas,
                                'descricao' => 'Consoles, PC, handhelds — todos os destinos onde um jogo roda.',
                            ],
                            [
                                'titulo' => 'Gêneros',
                                'total' => $totalGeneros,
                                'descricao' => 'Categorias usadas para filtrar e organizar o catálogo principal.',
                            ],
                        ];
                    @endphp

                    @foreach ($modulos as $modulo)
                        <article class="bg-[#1C1B26] hover:bg-[#25232F] transition flex flex-col">
                            <div
                                class="aspect-video bg-[#11101A] border-b border-white/5 flex items-center justify-center">
                                <span
                                    class="text-5xl sm:text-6xl font-black text-[#6B5B9E]">{{ $modulo['total'] }}</span>
                            </div>
                            <div class="p-5 flex-1 flex flex-col gap-3">
                                <h3 class="text-sm font-black tracking-widest uppercase">{{ $modulo['titulo'] }}</h3>
                                <p class="text-xs text-white/60 leading-relaxed">{{ $modulo['descricao'] }}</p>
                                <div class="flex items-center justify-between mt-auto pt-3 border-t border-white/10">
                                    <a href="#"
                                        class="text-[10px] font-black tracking-widest uppercase text-white/60 hover:text-[#6B5B9E] transition">Gerenciar</a>
                                    <a href="#"
                                        class="text-[10px] font-black tracking-widest uppercase text-[#6B5B9E] hover:text-[#8674B8] transition">+
                                        Novo</a>
                                </div>
                            </div>
                        </article>
                    @endforeach

                </div>
            </div>
        </section>

        {{-- atividade recente --}}
        <section class="border-t border-white/10">
            <div class="max-w-[1600px] mx-auto px-6 sm:px-12 py-10">
                <div class="flex items-center justify-between mb-8">
                    <h2
                        class="text-xl sm:text-2xl font-black tracking-widest uppercase border-l-4 border-[#6B5B9E] pl-4">
                        Atividade recente
                    </h2>
                    <a href="#"
                        class="px-5 py-2 border border-white/30 text-white font-black tracking-widest uppercase text-[10px] hover:border-[#6B5B9E] hover:text-[#6B5B9E] transition">Ver
                        log completo</a>
                </div>

                @php
                    $atividade = [
                        [
                            'tipo' => 'Jogo',
                            'nome' => 'Persona 5 Royal',
                            'acao' => 'cadastrado',
                            'data' => '2026-05-28 14:32',
                        ],
                        [
                            'tipo' => 'Plataforma',
                            'nome' => 'Nintendo Switch 2',
                            'acao' => 'cadastrada',
                            'data' => '2026-05-27 09:15',
                        ],
                        [
                            'tipo' => 'Desenvolvedora',
                            'nome' => 'Atlus',
                            'acao' => 'atualizada',
                            'data' => '2026-05-26 18:47',
                        ],
                        [
                            'tipo' => 'Jogo',
                            'nome' => 'Hollow Knight: Silksong',
                            'acao' => 'cadastrado',
                            'data' => '2026-05-26 11:03',
                        ],
                        [
                            'tipo' => 'Gênero',
                            'nome' => 'Metroidvania',
                            'acao' => 'cadastrado',
                            'data' => '2026-05-25 22:18',
                        ],
                    ];
                @endphp

                <div class="bg-[#1C1B26] border border-white/10">
                    @foreach ($atividade as $i => $item)
                        <div
                            class="flex items-center justify-between gap-4 px-5 py-4 {{ $i > 0 ? 'border-t border-white/5' : '' }}">
                            <div class="flex items-center gap-4 min-w-0">
                                <span
                                    class="flex-shrink-0 inline-block px-2 py-0.5 text-[10px] font-black tracking-widest uppercase bg-[#6B5B9E] text-black">{{ $item['tipo'] }}</span>
                                <p class="text-sm text-white truncate">
                                    <span class="font-bold">{{ $item['nome'] }}</span>
                                    <span class="text-white/40"> · {{ $item['acao'] }}</span>
                                </p>
                            </div>
                            <span
                                class="text-[10px] text-white/40 tracking-widest uppercase font-bold flex-shrink-0">{{ $item['data'] }}</span>
                        </div>
                    @endforeach
                </div>
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
