<?php

namespace App\Services\Imagem\Interfaces;

use Illuminate\Http\UploadedFile;


interface IImagemService
{

    public function salvarJogo(UploadedFile $arquivo): array;

    public function remover(array $caminhos): void;

    public function salvarPerfil(UploadedFile $arquivo): array;

    public function url(?string $caminho): ?string;

    public function salvarJogoDeBytes(string $bytes): array;
}
