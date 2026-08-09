<?php

namespace App\Http\Middleware;

use App\Services\Gerenciador\Interfaces\IAuditoriaService;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class RegistrarAuditoria
{

        public function __construct(protected IAuditoriaService $auditoriaService) {}


        public function handle(Request $request, Closure $next): Response
        {


                $response = $next($request);

                if ($request->isMethod('post') && $response->isSuccessful() && auth()->check()) {

                        $this->auditoriaService->registrar(
                                usuarioID: auth()->id(),
                                rota: $request->route()?->getName(),
                                metodo: $request->method(),
                                alvoID: $this->alvoId($request, $response)
                        );
                }

                return $response;
        }

        public function alvoId(Request $request, Response $response): ?int
        {

                if ($request->route('id') !== null) {
                        return (int) $request->route('id');
                }

                if ($response instanceof JsonResponse) {
                        foreach ((array) $response->getData(true) as $valor) {

                                if (is_array($valor) && isset($valor['id'])) {
                                        return (int) $valor['id'];
                                }
                        }
                }

                return null;
        }
}
