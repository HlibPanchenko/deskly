<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Column;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function store(Request $request, Column $column): JsonResponse
    {
        abort_if($column->board->user_id !== $request->user()->id, 403);

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $position = $column->cards()->max('position') + 1;

        $card = $column->cards()->create([
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'position'    => $position,
        ]);

        return response()->json($card, 201);
    }

    public function show(Request $request, Card $card): JsonResponse
    {
        abort_if($card->column->board->user_id !== $request->user()->id, 403);

        return response()->json($card);
    }

    public function update(Request $request, Card $card): JsonResponse
    {
        abort_if($card->column->board->user_id !== $request->user()->id, 403);

        $data = $request->validate([
            'title'       => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'position'    => 'sometimes|integer|min:0',
            'column_id'   => 'sometimes|integer|exists:columns,id',
        ]);

        $card->update($data);

        return response()->json($card);
    }

    public function destroy(Request $request, Card $card): JsonResponse
    {
        abort_if($card->column->board->user_id !== $request->user()->id, 403);

        $card->delete();

        return response()->json(null, 204);
    }
}
