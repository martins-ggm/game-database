<?php

namespace App\Services\Imagem;

use Intervention\Image\Laravel\Facades\Image;
use App\Services\Imagem\Interfaces\IImagemService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ImagemService implements IImagemService
{

    private string $disco;

    public function __construct()
    {

        $this->disco = config('filesystems.imagens_disco');
    }


    public function salvarJogo(UploadedFile $arquivo): array
    {

        $nome = Str::uuid() . '.webp';
        $grande = (string) Image::read($arquivo)->scaleDown(width: 600)->toWebp(90);
        $pequena = (string) Image::read($arquivo)->scaleDown(width: 300)->toWebp(90);

        Storage::disk($this->disco)->put("imagens/jogos/grande/{$nome}", $grande);
        Storage::disk($this->disco)->put("imagens/jogos/pequena/{$nome}", $pequena);

        return [
            'grande' => "imagens/jogos/grande/{$nome}",
            'pequena' => "imagens/jogos/pequena/{$nome}"
        ];
    }


    public function remover(array $caminhos): void
    {
        Storage::disk($this->disco)->delete(array_filter($caminhos));
    }


    public function salvarPerfil(UploadedFile $arquivo): array
    {

        $nome = Str::uuid() . '.webp';
        $grande = (string) Image::read($arquivo)->scaleDown(width: 300, height: 300)->toWebp(80);
        $pequena = (string) Image::read($arquivo)->scaleDown(width: 100, height: 100)->toWebp(80);

        Storage::disk($this->disco)->put("imagens/perfil/grande/{$nome}", $grande);
        Storage::disk($this->disco)->put("imagens/perfil/pequena/{$nome}", $pequena);

        return [
            'grande' => "imagens/perfil/grande/{$nome}",
            'pequena' => "imagens/perfil/pequena/{$nome}"
        ];
    }

    /**
     * Ponto único de geração de URL. Resolve pelo disco configurado, então no
     * dia do bucket nenhuma view precisa mudar — só IMAGENS_DISK no .env.
     */
    public function url(?string $caminho): ?string
    {
        if (! $caminho) {
            return null;
        }

        return Storage::disk($this->disco)->url($caminho);
    }


    /**
     * Mesma conversão de salvarJogo(), mas a partir de bytes crus — é o que o
     * job de capa usa depois de baixar do CDN do IGDB, sem precisar forjar um
     * UploadedFile a partir de arquivo temporário.
     */
    public function salvarJogoDeBytes(string $bytes): array
    {
        $nome = Str::uuid() . '.webp';

        $grande  = (string) Image::read($bytes)->scaleDown(width: 600)->toWebp(90);
        $pequena = (string) Image::read($bytes)->scaleDown(width: 300)->toWebp(90);

        Storage::disk($this->disco)->put("imagens/jogos/grande/{$nome}", $grande);
        Storage::disk($this->disco)->put("imagens/jogos/pequena/{$nome}", $pequena);

        return [
            'grande'  => "imagens/jogos/grande/{$nome}",
            'pequena' => "imagens/jogos/pequena/{$nome}",
        ];
    }
}
