<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar — Game Database</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
    </style>
</head>

<body class="bg-[#11101A] text-white min-h-screen flex flex-col">

    {{-- topbar com a marca --}}
    <header class="border-b border-white/10">
        <div class="max-w-[1600px] mx-auto px-6 sm:px-12 py-5 flex items-center justify-between">
            <a href="/" class="text-2xl sm:text-3xl font-black tracking-widest">
                GAME<span class="text-[#6B5B9E]">DB</span>
            </a>
            <a href="/" class="text-sm font-bold tracking-widest uppercase text-white/60 hover:text-[#6B5B9E] transition">
                &larr; VOLTAR
            </a>
        </div>
    </header>

    {{-- form --}}
    <main class="flex-1 flex items-center justify-center px-6 py-12 sm:py-20">
        <div class="w-full max-w-md">

            <div class="mb-8">
                <h1 class="text-2xl sm:text-3xl font-black tracking-widest uppercase border-l-4 border-[#6B5B9E] pl-4">
                    Entrar
                </h1>
                <p class="text-sm text-white/60 mt-3 ml-5">Acesse sua conta no GAMEDB</p>
            </div>

            <div id="mensagem" class="hidden mb-4 p-3 border text-sm font-bold tracking-wide"></div>

            <ul id="erros" class="hidden mb-4 p-3 bg-red-500/10 border border-red-500/30 text-sm text-red-300 list-disc list-inside space-y-1"></ul>

            <form id="form-login" class="space-y-5 bg-[#1C1B26] border border-white/10 p-6 sm:p-8">
                <div>
                    <label for="email" class="block text-[10px] font-black uppercase tracking-widest text-white/60 mb-2">Email</label>
                    <input type="email" id="email" name="email" placeholder="seu@email.com"
                        class="w-full px-4 py-3 bg-[#11101A] border border-white/10 text-white placeholder-white/30 focus:outline-none focus:border-[#6B5B9E] transition">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-[10px] font-black uppercase tracking-widest text-white/60">Senha</label>
                        <a href="#" class="text-[10px] font-bold uppercase tracking-widest text-white/40 hover:text-[#6B5B9E] transition">Esqueci</a>
                    </div>
                    <input type="password" id="password" name="password" placeholder="Sua senha"
                        class="w-full px-4 py-3 bg-[#11101A] border border-white/10 text-white placeholder-white/30 focus:outline-none focus:border-[#6B5B9E] transition">
                </div>

                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" id="lembrar" name="lembrar"
                        class="w-4 h-4 bg-[#11101A] border border-white/20 accent-[#6B5B9E] cursor-pointer">
                    <span class="text-xs text-white/60">Lembrar de mim neste dispositivo</span>
                </label>

                <button type="submit"
                    class="w-full mt-2 py-3 bg-[#6B5B9E] text-black font-black tracking-widest uppercase text-xs hover:bg-[#8674B8] transition">
                    Entrar
                </button>
            </form>

            <p class="text-center text-xs text-white/40 mt-6 tracking-wide">
                Ainda não tem conta?
                <a href="{{ route('gerenciador.usuario.criar') }}"
                    class="text-[#6B5B9E] font-bold hover:text-[#8674B8] uppercase tracking-widest">Cadastre-se</a>
            </p>
        </div>
    </main>

    {{-- footer --}}
    <footer class="border-t border-white/10">
        <div class="max-w-[1600px] mx-auto px-6 sm:px-12 py-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-[#C6C2D9] opacity-60 hover:opacity-100 transition"
                    style="-webkit-mask: url('{{ asset('misc/espurr.svg') }}') center/contain no-repeat; mask: url('{{ asset('misc/espurr.svg') }}') center/contain no-repeat;"
                    role="img" aria-label="Espurr — mascote"></div>
                <p class="text-[10px] tracking-widest text-white/40 uppercase font-bold">&copy; 2026 Game Database</p>
            </div>
            <p class="text-[10px] tracking-widest text-white/40 uppercase font-bold">Built with the SISP architecture</p>
        </div>
    </footer>

</body>

</html>
