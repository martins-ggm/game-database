<?php

namespace App\Services\Igdb;

use App\Services\Igdb\Interfaces\IIgdbClient;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class IgdbClient implements IIgdbClient
{

    private const MARGEM_EXPIRACAO = 60;


    public function consultar(string $endpoint, string $query): array
    {


        $resposta = Http::withHeaders([
            'Client-ID' => config('igdb.client_id'),
            'Authorization' => 'Bearer ' . $this->token(),
            'Accept' => 'application/json',
        ])->withBody($query, 'text/plain')->retry(
            times: 3,
            sleepMilliseconds: 500,
            when: fn(\Throwable $e) => $e instanceof RequestException
                && in_array($e->response->status(), [429, 500, 502, 503, 504]),
            throw: false
        )->post(config('igdb.base_url') . '/' . ltrim($endpoint, '/'));

        throw_unless(
            $resposta->successful(),
            new \Exception("IGDB / {$endpoint} respondeu {$resposta->status()}: {$resposta->body()}")
        );

        return $resposta->json() ?? [];
    }

    public function token(): string
    {
        $chave = config('igdb.cache_key');

        if ($token = Cache::get($chave)) {
            return $token;
        }


        $resposta = Http::asForm()->post(config('igdb.token_url'), [
            'client_id' => config('igdb.client_id'),
            'client_secret' => config('igdb.client_secret'),
            'grant_type' => 'client_credentials',


        ]);

        throw_unless(
            $resposta->successful(),
            new \Exception("Falha ao obter token na Twitch ({$resposta->status()}): {$resposta->body()} ")
        );

        $token = $resposta->json('access_token');
        $expiraEm = (int) $resposta->json('expires_in');

        throw_unless($token, new \Exception('A Twitch não devolveu access_token.'));

        Cache::put($chave, $token, max($expiraEm - self::MARGEM_EXPIRACAO, 60));

        return $token;
    }
}
