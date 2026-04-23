<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Board;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $boards = $request->user()->boards()->latest()->get();

        return response()->json($boards);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $board = $request->user()->boards()->create($data);

        return response()->json($board, 201);
    }

    public function show(Request $request, Board $board): JsonResponse
    {
        $this->authorizeBoard($request, $board);

        $board->load('columns.cards');

        return response()->json($board);
    }

    public function update(Request $request, Board $board): JsonResponse
    {
        $this->authorizeBoard($request, $board);

        $data = $request->validate([
            'name'        => 'sometimes|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $board->update($data);

        return response()->json($board);
    }

    public function destroy(Request $request, Board $board): JsonResponse
    {
        $this->authorizeBoard($request, $board);

        $board->delete();

        return response()->json(null, 204);
    }

    private function authorizeBoard(Request $request, Board $board): void
    {
        abort_if($board->user_id !== $request->user()->id, 403);
    }
}
