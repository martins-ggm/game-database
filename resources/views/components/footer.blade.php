<footer class="border-t border-white/10">
    <div class="max-w-[1600px] mx-auto px-6 sm:px-12 py-6 flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-[#C6C2D9] opacity-60 hover:opacity-100 transition"
                style="-webkit-mask: url('{{ asset('misc/espurr.svg') }}') center/contain no-repeat; mask: url('{{ asset('misc/espurr.svg') }}') center/contain no-repeat;"
                role="img" aria-label="Espurr — mascote"></div>
            <p class="text-[10px] tracking-widest text-white/40 uppercase font-bold">
                &copy; 2026 Game Database
                @if ($versao)
                    <span class="text-white/25">·</span>
                    <span class="text-[#6B5B9E]">v{{ $versao }}</span>
                @endif
            </p>
        </div>
        <p class="text-[10px] tracking-widest text-white/40 uppercase font-bold">Built with love ;)</p>
    </div>
</footer>
