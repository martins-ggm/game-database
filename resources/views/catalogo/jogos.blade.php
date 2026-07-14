<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogos — Game Database</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
    </style>
</head>

<body class="bg-[#11101A] text-white min-h-screen flex flex-col">

    {{-- topbar --}}
    <header class="border-b border-white/10">
        <div class="max-w-[1600px] mx-auto px-6 sm:px-12 py-5 flex items-center justify-between">
            <a href="/" class="text-2xl sm:text-3xl font-black tracking-widest">
                GAME<span class="text-[#6B5B9E]">DB</span>
            </a>
            <a href="{{ route('gerenciador.dashboard.visualizar') }}"
                class="text-sm font-bold tracking-widest uppercase text-white/60 hover:text-[#6B5B9E] transition">
                &larr; Dashboard
            </a>
        </div>
    </header>

    {{-- conteúdo --}}
    <main class="flex-1 max-w-[1600px] w-full mx-auto px-6 sm:px-12 py-12">

        {{-- cabeçalho da página --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <h1 class="text-2xl sm:text-3xl font-black tracking-widest uppercase border-l-4 border-[#6B5B9E] pl-4">
                Jogos
            </h1>
            <button type="button" id="btn-novo-jogo"
                class="self-start sm:self-auto px-6 py-3 bg-[#6B5B9E] text-black font-black tracking-widest uppercase text-xs hover:bg-[#8674B8] transition">
                + Novo Jogo
            </button>
        </div>

        {{-- feedback (o jQuery vai usar depois) --}}
        <div id="mensagem" class="hidden mb-4 p-3 border text-sm font-bold tracking-wide"></div>

        {{-- tabela --}}
        <div class="bg-[#1C1B26] border border-white/10 overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-white/10 text-left">
                        <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-white/40">Nome</th>
                        <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-white/40">Desenvolvedora</th>
                        <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-white/40">Plataformas</th>
                        <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-white/40">Gêneros</th>
                        <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-white/40 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody id="tabela-jogos">
                    @forelse ($jogos ?? [] as $jogo)
                        <tr class="border-b border-white/5 hover:bg-[#25232F] transition">
                            <td class="px-5 py-4 font-bold">{{ $jogo->nome }}</td>
                            <td class="px-5 py-4 text-white/70">{{ $jogo->desenvolvedora?->nome ?? '—' }}</td>
                            <td class="px-5 py-4 text-white/70">{{ $jogo->plataformas->pluck('nome')->implode(', ') ?: '—' }}</td>
                            <td class="px-5 py-4 text-white/70">{{ $jogo->generos->pluck('nome')->implode(', ') ?: '—' }}</td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <button type="button" data-editar-jogo="{{ $jogo->id }}"
                                    class="text-[10px] font-black uppercase tracking-widest text-white/60 hover:text-[#6B5B9E] transition">
                                    Editar
                                </button>
                                <button type="button" data-remover-jogo="{{ $jogo->id }}"
                                    class="ml-4 text-[10px] font-black uppercase tracking-widest text-white/60 hover:text-red-400 transition">
                                    Excluir
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr id="linha-vazia">
                            <td colspan="5" class="px-5 py-12 text-center text-white/30 text-xs uppercase tracking-widest">
                                Nenhum jogo cadastrado
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>

    {{-- footer --}}
    <footer class="border-t border-white/10">
        <div class="max-w-[1600px] mx-auto px-6 sm:px-12 py-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-[10px] tracking-widest text-white/40 uppercase font-bold">&copy; 2026 Game Database</p>
            <p class="text-[10px] tracking-widest text-white/40 uppercase font-bold">Built with the SISP architecture</p>
        </div>
    </footer>

    {{-- ============ MODAL (novo / editar) ============ --}}
    <div id="modal-jogo" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        {{-- backdrop --}}
        <div class="absolute inset-0 bg-black/70" data-fechar-modal></div>

        {{-- caixa --}}
        <div class="relative w-full max-w-lg bg-[#1C1B26] border border-white/10 max-h-[90vh] overflow-y-auto">

            {{-- header do modal --}}
            <div class="flex items-center justify-between px-6 py-5 border-b border-white/10">
                <h2 id="modal-titulo" class="text-lg font-black tracking-widest uppercase border-l-4 border-[#6B5B9E] pl-3">
                    Novo Jogo
                </h2>
                <button type="button" data-fechar-modal
                    class="text-white/40 hover:text-white text-2xl leading-none">&times;</button>
            </div>

            {{-- erros de validação (jQuery preenche) --}}
            <ul id="erros"
                class="hidden mx-6 mt-4 p-3 bg-red-500/10 border border-red-500/30 text-sm text-red-300 list-disc list-inside space-y-1"></ul>

            {{-- form --}}
            <form id="form-jogo" class="p-6 space-y-5">
                <input type="hidden" id="jogo_id" name="id">

                <div>
                    <label for="nome" class="block text-[10px] font-black uppercase tracking-widest text-white/60 mb-2">Nome</label>
                    <input type="text" id="nome" name="nome" placeholder="Nome do jogo"
                        class="w-full px-4 py-3 bg-[#11101A] border border-white/10 text-white placeholder-white/30 focus:outline-none focus:border-[#6B5B9E] transition">
                </div>

                <div>
                    <label for="desenvolvedora_id" class="block text-[10px] font-black uppercase tracking-widest text-white/60 mb-2">Desenvolvedora</label>
                    <select id="desenvolvedora_id" name="desenvolvedora_id"
                        class="w-full px-4 py-3 bg-[#11101A] border border-white/10 text-white focus:outline-none focus:border-[#6B5B9E] transition">
                        <option value="">— Nenhuma —</option>
                        @foreach ($desenvolvedoras ?? [] as $desenvolvedora)
                            <option value="{{ $desenvolvedora->id }}">{{ $desenvolvedora->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <span class="block text-[10px] font-black uppercase tracking-widest text-white/60 mb-2">Plataformas</span>
                    <div class="grid grid-cols-2 gap-2 max-h-40 overflow-y-auto p-3 bg-[#11101A] border border-white/10">
                        @forelse ($plataformas ?? [] as $plataforma)
                            <label class="flex items-center gap-2 cursor-pointer select-none text-sm text-white/80">
                                <input type="checkbox" name="plataformas[]" value="{{ $plataforma->id }}"
                                    class="w-4 h-4 bg-[#1C1B26] border border-white/20 accent-[#6B5B9E] cursor-pointer">
                                {{ $plataforma->nome }}
                            </label>
                        @empty
                            <span class="col-span-2 text-xs text-white/30 uppercase tracking-widest">Nenhuma plataforma cadastrada</span>
                        @endforelse
                    </div>
                </div>

                <div>
                    <span class="block text-[10px] font-black uppercase tracking-widest text-white/60 mb-2">Gêneros</span>
                    <div class="grid grid-cols-2 gap-2 max-h-40 overflow-y-auto p-3 bg-[#11101A] border border-white/10">
                        @forelse ($generos ?? [] as $genero)
                            <label class="flex items-center gap-2 cursor-pointer select-none text-sm text-white/80">
                                <input type="checkbox" name="generos[]" value="{{ $genero->id }}"
                                    class="w-4 h-4 bg-[#1C1B26] border border-white/20 accent-[#6B5B9E] cursor-pointer">
                                {{ $genero->nome }}
                            </label>
                        @empty
                            <span class="col-span-2 text-xs text-white/30 uppercase tracking-widest">Nenhum gênero cadastrado</span>
                        @endforelse
                    </div>
                </div>

                {{-- ações do modal --}}
                <div class="flex flex-col sm:flex-row gap-3 sm:justify-end pt-2">
                    <button type="button" data-fechar-modal
                        class="px-6 py-3 border border-white/30 text-white font-black tracking-widest uppercase text-xs hover:border-white/60 transition">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-[#6B5B9E] text-black font-black tracking-widest uppercase text-xs hover:bg-[#8674B8] transition">
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        // -------------------------------------------------------------
        // Só o toggle visual do modal (abrir/fechar).
        // A lógica de CRUD (buscar, incluir, alterar, remover via AJAX)
        // será implementada aqui depois, junto com o controller/rotas.
        // -------------------------------------------------------------
        (function () {
            const modal = document.getElementById('modal-jogo');

            document.getElementById('btn-novo-jogo').addEventListener('click', function () {
                document.getElementById('form-jogo').reset();
                document.getElementById('jogo_id').value = '';
                document.getElementById('modal-titulo').textContent = 'Novo Jogo';
                document.getElementById('erros').classList.add('hidden');
                modal.classList.remove('hidden');
            });

            document.querySelectorAll('[data-fechar-modal]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    modal.classList.add('hidden');
                });
            });
        })();
    </script>

</body>

</html>
