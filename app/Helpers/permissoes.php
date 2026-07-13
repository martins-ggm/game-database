<?php


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

if (!function_exists('possuiPermissao')) {

    function possuiPermissao(string $str_rota): bool
    {

        if (!Auth::check()) {
            return false;
        }

        $permissoes = Cache::remember(
            key: 'permissoes_usuario_' . Auth::id(),
            ttl: 3600,
            callback: function (): array {

                $usuario = Auth::user();
                return $usuario->perfil?->permissoes->pluck('str_name')->toArray() ?? [];
            },
        );

        return in_array(needle: $str_rota, haystack: $permissoes);
    }
}
