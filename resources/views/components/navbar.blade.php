{{-- CSRF token usado pelo AJAX de logout --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Topbar sticky compartilhada das telas internas --}}
<header class="sticky top-0 z-40 bg-[#11101A] border-b border-white/10">
    <nav class="max-w-[1600px] mx-auto px-6 sm:px-12 py-5 flex items-center justify-between">
        <div class="flex items-center gap-8">
            <a href="{{ route('gerenciador.dashboard.visualizar') }}"
                class="text-sm font-bold tracking-widest uppercase {{ request()->routeIs('gerenciador.dashboard.visualizar') ? '' : 'text-white/60' }} hover:text-[#6B5B9E] transition">HOME</a>
            <a href="{{ route('gerenciador.dashboard.visualizar') }}#catalogo"
                class="text-sm font-bold tracking-widest uppercase text-white/60 hover:text-[#6B5B9E] transition">CATÁLOGO</a>
            <a href="#"
                class="text-sm font-bold tracking-widest uppercase text-white/60 hover:text-[#6B5B9E] transition">LISTAS</a>
        </div>

        <a href="{{ route('gerenciador.dashboard.visualizar') }}"
            class="text-2xl sm:text-3xl font-black tracking-widest">
            GAME<span class="text-[#6B5B9E]">DB</span>
        </a>

        <div class="flex items-center gap-8">
            @if (ehAdmin())
                   <a href="{{ route('gerenciador.admin.visualizar') }}"
                class="text-sm font-bold tracking-widest uppercase {{ request()->routeIs('gerenciador.admin.visualizar') ? '' : 'text-white/60' }} hover:text-[#6B5B9E] transition">ADMIN</a>
            @endif
         
            <a href="{{ route('gerenciador.usuario.perfil', auth()->id()) }}"
                class="text-sm font-bold tracking-widest uppercase {{ request()->routeIs('gerenciador.usuario.perfil') ? '' : 'text-white/60' }} hover:text-[#6B5B9E] transition">PERFIL</a>
            @auth
                <a href="#" id="navbar-sair"
                    class="text-sm font-bold tracking-widest uppercase text-white/60 hover:text-[#6B5B9E] transition cursor-pointer">SAIR</a>
            @endauth
        </div>
    </nav>
</header>

@auth
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(function() {
            $('#navbar-sair').on('click', function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('gerenciador.usuario.logout') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        window.location.href = response.redirect;
                    },
                    error: function(xhr) {
                        console.error('Logout failed:', xhr);
                        window.location.href = "{{ route('gerenciador.usuario.login') }}";
                    }
                });
            });
        });
    </script>
@endauth
