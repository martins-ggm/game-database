<?php

namespace App\Services\Imagem;

use Intervention\Image\Laravel\Facades\Image;
use App\Services\Imagem\Interfaces\IImagemService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class ImagemService implements IImagemService
{

    public function salvarJogo(UploadedFile $arquivo): array
    {

        $nome = Str::uuid() . '.webp';
        $grande = (string) Image::read($arquivo)->scaleDown(width: 600)->toWebp(90);
        $pequena = (string) Image::read($arquivo)->scaleDown(width: 300)->toWebp(90);

        Storage::disk('public')->put("/imagens/jogos/grande/{$nome}", $grande);
        Storage::disk('public')->put("imagens/jogos/pequena/{$nome}", $pequena);

        return [
            'grande' => "imagens/jogos/grande/{$nome}",
            'pequena' => "imagens/jogos/pequena/{$nome}"
        ];
    }


    public function remover(array $caminhos): void
    {
        Storage::disk('public')->delete(array_filter($caminhos));
    }


    public function salvarPerfil(UploadedFile $arquivo): array
    {

        $nome = Str::uuid() . '.webp';
        $grande = (string) Image::read($arquivo)->scaleDown(width: 300, height: 300)->toWebp(80);
        $pequena = (string) Image::read($arquivo)->scaleDown(width: 100, height: 100)->toWebp(80);

        Storage::disk('public')->put("/imagens/perfil/grande/{$nome}", $grande);
        Storage::disk('public')->put("imagens/perfil/pequena/{$nome}", $pequena);

        return [
            'grande' => "imagens/perfil/grande/{$nome}",
            'pequena' => "imagens/perfil/pequena/{$nome}"
        ];
    }
}
