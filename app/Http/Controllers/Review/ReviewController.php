<?php

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Controller;
use App\Http\DTO\Review\ReviewDTO;
use App\Http\Resources\Review\ReviewResource;
use App\Services\Review\Interfaces\IReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class ReviewController extends Controller
{


    public function __construct(protected IReviewService $reviewService) {}


    public function criar(Request $request): JsonResponse
    {

        $dto = ReviewDTO::fromRequest($request, true);

        abort_unless(auth()->id() === $dto->usuario_id, 403, 'Ação não autorizada.');
        
        $review = $this->reviewService->criar($dto);

        return response()->json(
            [
                'mensagem' => 'Review enviada com sucesso!',
                'review' => ReviewResource::criar($review)
            ],
            status: 200
        );
    }

    public function editar(Request $request): JsonResponse
    {

        $dto = ReviewDTO::fromRequest($request, false);

        abort_unless(auth()->id() === $dto->usuario_id, 403, 'Ação não autorizada.');

        $review = $this->reviewService->editar($dto);

        return response()->json(
            [
                'mensagem' => 'Review atualizada com sucesso!',
                'review' => ReviewResource::criar($review)
            ],
            status: 200
        );
    }

    public function remover(Request $request): JsonResponse
    {

        $this->reviewService->remover($request->id, auth()->id());

        return response()->json(['mensagem' => 'Removido com sucesso!'], status: 200);
    }
}
