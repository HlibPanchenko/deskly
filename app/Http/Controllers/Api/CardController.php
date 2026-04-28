<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Card\StoreCardData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Card\StoreCardRequest;
use App\Http\Requests\Card\UpdateCardRequest;
use App\Http\Resources\CardResource;
use App\Models\Card;
use App\Models\Column;
use App\Services\CardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function __construct(private readonly CardService $cardService) {}

    public function store(StoreCardRequest $request, Column $column): JsonResponse
    {
        $card = $this->cardService->create($column, StoreCardData::fromRequest($request));

        return response()->json(new CardResource($card), 201);
    }

    public function show(Request $request, Card $card): JsonResponse
    {
        $card = $this->cardService->findForUser($request->user(), $card);

        return response()->json(new CardResource($card));
    }

    public function update(UpdateCardRequest $request, Card $card): JsonResponse
    {
        $card = $this->cardService->update($card, $request->validated());

        return response()->json(new CardResource($card));
    }

    public function destroy(Request $request, Card $card): JsonResponse
    {
        $this->cardService->deleteForUser($request->user(), $card);

        return response()->json(null, 204);
    }
}
