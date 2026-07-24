<?php

namespace App\Services\Imagem\Interfaces;

use Illuminate\Http\UploadedFile;


Interface IImagemService{

public function salvar(UploadedFile $arquivo): array;

public function remover(array $caminhos): void;


}