<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Board\StoreBoardData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Board\StoreBoardRequest;
use App\Http\Requests\Board\UpdateBoardRequest;
use App\Http\Resources\BoardResource;
use App\Models\Board;
use App\Services\BoardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function __construct(private readonly BoardService $boardService) {}

    public function index(Request $request): JsonResponse
    {
        $boards = $this->boardService->listForUser($request->user());

        return response()->json(BoardResource::collection($boards));
    }

    public function store(StoreBoardRequest $request): JsonResponse
    {
        $board = $this->boardService->create($request->user(), StoreBoardData::fromRequest($request));

        return response()->json(new BoardResource($board), 201);
    }

    public function show(Request $request, Board $board): JsonResponse
    {
        $board = $this->boardService->findForUser($request->user(), $board);

        return response()->json(new BoardResource($board));
    }

    public function update(UpdateBoardRequest $request, Board $board): JsonResponse
    {
        $board = $this->boardService->update($board, $request->validated());

        return response()->json(new BoardResource($board));
    }

    public function destroy(Request $request, Board $board): JsonResponse
    {
        $this->boardService->deleteForUser($request->user(), $board);

        return response()->json(null, 204);
    }
}
