<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarPermissao
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $rota = $request->route()->getName();

        if (!possuiPermissao(str_rota: $rota)) {
            abort(code: 403, message: 'Você não tem permissão para acessar esta página');
        }

        return $next($request);
    }
}
