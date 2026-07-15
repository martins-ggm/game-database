<?php

namespace App\Services\Plataforma\Interfaces;

use App\Http\DTO\Catalogo\PlataformaDTO;
use App\Models\Catalogo\Plataforma;

Interface IPlataformaService{


public function criar(PlataformaDTO $dados): Plataforma;


}