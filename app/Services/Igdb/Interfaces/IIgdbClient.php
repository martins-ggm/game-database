<?php

namespace App\Services\Igdb\Interfaces;

interface IIgdbClient
{
    public function consultar(String $endpoint, String $query): array;

    public function token(): string;
}
