<?php

namespace App\Services\Imagem\Interfaces;

use Illuminate\Http\UploadedFile;


Interface IImagemService{

public function salvarJogo(UploadedFile $arquivo): array;

public function remover(array $caminhos): void;

public function salvarPerfil(UploadedFile $arquivo): array;



}