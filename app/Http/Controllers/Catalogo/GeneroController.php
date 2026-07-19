<?php

namespace App\Http\Controllers\Catalogo;

use App\Http\Controllers\Controller;
use App\Services\Catalogo\Interfaces\IGeneroService;
use Illuminate\Http\JsonResponse;

class GeneroController extends Controller {

public function __construct(protected IGeneroService $IgeneroService) {}






public function criar (Request $request):JsonResponse {








}



}