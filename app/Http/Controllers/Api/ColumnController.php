<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\Column;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ColumnController extends Controller
{
    public function store(Request $request, Board $board): JsonResponse
    {
        abort_if($board->user_id !== $request->user()->id, 403);

        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $position = $board->columns()->max('position') + 1;

        $column = $board->columns()->create([
            'name'     => $data['name'],
            'position' => $position,
        ]);

        return response()->json($column, 201);
    }

    public function update(Request $request, Column $column): JsonResponse
    {
        abort_if($column->board->user_id !== $request->user()->id, 403);

        $data = $request->validate([
            'name'     => 'sometimes|string|max:255',
            'position' => 'sometimes|integer|min:0',
        ]);

        $column->update($data);

        return response()->json($column);
    }

    public function destroy(Request $request, Column $column): JsonResponse
    {
        abort_if($column->board->user_id !== $request->user()->id, 403);

        $column->delete();

        return response()->json(null, 204);
    }
}
