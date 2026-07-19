<?php


namespace App\Services\Catalogo\Interfaces;

use App\Http\DTO\Catalogo\GeneroDTO;
use App\Models\Catalogo\Genero;

interface IGeneroService {

public function criar(GeneroDTO $dados): Genero;







}