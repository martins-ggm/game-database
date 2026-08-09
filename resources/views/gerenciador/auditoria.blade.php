<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log de auditoria — Game Database</title>
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
        // rótulo a partir do nome da rota: catalogo.jogo.criar → Jogo / cadastrado
        $rotuloAuditoria = function (?string $rota) {
            $partes = explode('.', (string) $rota);
            $acao = end($partes);
            $entidade = $partes[count($partes) - 2] ?? '';
            $entidades = [
                'jogo' => 'Jogo', 'desenvolvedora' => 'Desenvolvedora', 'plataforma' => 'Plataforma',
                'genero' => 'Gênero', 'review' => 'Review', 'usuario' => 'Usuário', 'colecao' => 'Coleção',
            ];
            $acoes = [
                'criar' => 'cadastrado', 'editar' => 'atualizado', 'remover' => 'removido',
                'atualizar' => 'atualizado', 'adicionar' => 'adicionado', 'logout' => 'logout',
            ];
            return [
                'tipo' => $entidades[$entidade] ?? ucfirst($entidade ?: '—'),
                'acao' => $acoes[$acao] ?? $acao,
            ];
        };
    @endphp

    <main class="flex-1">

        {{-- cabeçalho --}}
        <section class="max-w-[1600px] mx-auto w-full px-6 sm:px-12 pt-10 pb-8">
            <a href="{{ route('gerenciador.admin.visualizar') }}"
                class="inline-flex items-center gap-2 text-[10px] font-black tracking-widest uppercase text-white/40 hover:text-[#6B5B9E] transition">
                <span class="text-base leading-none">&larr;</span> Voltar ao painel
            </a>

            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mt-6">
                <div>
                    <p class="text-[10px] font-black tracking-widest uppercase text-white/40 mb-2">Auditoria</p>
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight uppercase leading-[0.95]">
                        Log completo
                    </h1>
                </div>
                <span class="text-[10px] font-black tracking-widest uppercase text-white/40">
                    {{ $auditorias->total() }} {{ $auditorias->total() === 1 ? 'registro' : 'registros' }}
                </span>
            </div>
        </section>

        {{-- tabela (página 1 no servidor; próximas via AJAX) --}}
        <section class="max-w-[1600px] mx-auto w-full px-6 sm:px-12 pb-12">
            <div class="bg-[#1C1B26] border border-white/10 overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-white/10 text-left">
                            <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-white/40">Tipo</th>
                            <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-white/40">Ação</th>
                            <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-white/40">Usuário</th>
                            <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-white/40">Alvo</th>
                            <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-white/40">Rota</th>
                            <th class="px-5 py-4 text-[10px] font-black uppercase tracking-widest text-white/40 whitespace-nowrap">Quando</th>
                        </tr>
                    </thead>
                    <tbody id="log-corpo">
                        @forelse ($auditorias as $item)
                            @php $r = $rotuloAuditoria($item->rota); @endphp
                            <tr class="border-b border-white/5 hover:bg-[#25232F] transition">
                                <td class="px-5 py-4">
                                    <span class="inline-block px-2 py-0.5 text-[10px] font-black tracking-widest uppercase bg-[#6B5B9E] text-black">{{ $r['tipo'] }}</span>
                                </td>
                                <td class="px-5 py-4 text-white/80">{{ $r['acao'] }}</td>
                                <td class="px-5 py-4 text-white/80">{{ $item->usuario?->nome ?? 'sistema' }}</td>
                                <td class="px-5 py-4 text-white/50">{{ $item->alvo_id ? '#' . $item->alvo_id : '—' }}</td>
                                <td class="px-5 py-4 text-white/30 text-[11px]">{{ $item->rota }}</td>
                                <td class="px-5 py-4 text-white/50 whitespace-nowrap">{{ $item->created_at?->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-white/30 text-xs uppercase tracking-widest font-bold">
                                    Nenhum registro de auditoria
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- controles de paginação (montados pelo JS a partir do meta inicial) --}}
            <div id="paginacao" class="flex items-center justify-center gap-4 mt-6"></div>
        </section>

    </main>

    {{-- footer --}}
    <x-footer />

    @if ($auditorias->total() > 0)
        {{-- paginação AJAX (bate em gerenciador.admin.auditoria com wantsJson) --}}
        <script>
            (function () {
                const corpo = document.getElementById('log-corpo');
                const paginacao = document.getElementById('paginacao');
                if (!corpo || !paginacao) return;

                @php
                    $urlBase = route('gerenciador.admin.auditoria');
                    $metaInicial = [
                        'current_page' => $auditorias->currentPage(),
                        'last_page'    => $auditorias->lastPage(),
                    ];
                @endphp

                const urlBase = @json($urlBase);
                const metaInicial = @json($metaInicial);

                // mesmo mapa do lado PHP, pros registros que chegam via AJAX
                const ENTIDADES = {
                    jogo: 'Jogo', desenvolvedora: 'Desenvolvedora', plataforma: 'Plataforma',
                    genero: 'Gênero', review: 'Review', usuario: 'Usuário', colecao: 'Coleção',
                };
                const ACOES = {
                    criar: 'cadastrado', editar: 'atualizado', remover: 'removido',
                    atualizar: 'atualizado', adicionar: 'adicionado', logout: 'logout',
                };

                function escapar(texto) {
                    const div = document.createElement('div');
                    div.textContent = texto ?? '';
                    return div.innerHTML;
                }

                function rotulo(rota) {
                    const p = (rota || '').split('.');
                    const acao = p[p.length - 1] || '';
                    const entidade = p[p.length - 2] || '';
                    return {
                        tipo: ENTIDADES[entidade] || (entidade ? entidade[0].toUpperCase() + entidade.slice(1) : '—'),
                        acao: ACOES[acao] || acao,
                    };
                }

                function linhaHtml(a) {
                    const r = rotulo(a.rota);
                    const usuario = a.usuario ? escapar(a.usuario.nome) : 'sistema';
                    const alvo = a.alvo_id ? '#' + a.alvo_id : '—';
                    return `<tr class="border-b border-white/5 hover:bg-[#25232F] transition">
                        <td class="px-5 py-4"><span class="inline-block px-2 py-0.5 text-[10px] font-black tracking-widest uppercase bg-[#6B5B9E] text-black">${escapar(r.tipo)}</span></td>
                        <td class="px-5 py-4 text-white/80">${escapar(r.acao)}</td>
                        <td class="px-5 py-4 text-white/80">${usuario}</td>
                        <td class="px-5 py-4 text-white/50">${alvo}</td>
                        <td class="px-5 py-4 text-white/30 text-[11px]">${escapar(a.rota || '')}</td>
                        <td class="px-5 py-4 text-white/50 whitespace-nowrap">${escapar(a.horario || '')}</td>
                    </tr>`;
                }

                function botao(pagina, texto) {
                    return `<button type="button" data-pagina="${pagina}" class="px-4 py-2 text-[11px] font-black tracking-widest uppercase border border-white/15 text-white/70 hover:border-[#6B5B9E] hover:text-white transition">${texto}</button>`;
                }

                function botaoInativo(texto) {
                    return `<span class="px-4 py-2 text-[11px] font-black tracking-widest uppercase border border-white/5 text-white/20 cursor-not-allowed">${texto}</span>`;
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
                    corpo.style.opacity = '0.4';
                    try {
                        const url = new URL(urlBase, window.location.origin);
                        url.searchParams.set('page', pagina);

                        const resp = await fetch(url, { headers: { 'Accept': 'application/json' } });
                        if (!resp.ok) return;

                        const meta = (await resp.json()).auditorias;
                        corpo.innerHTML = meta.data.map(linhaHtml).join('');
                        paginacao.innerHTML = controlesHtml(meta);

                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    } finally {
                        corpo.style.opacity = '1';
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
