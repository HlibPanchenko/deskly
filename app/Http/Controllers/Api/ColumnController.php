<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Column\StoreColumnData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Column\StoreColumnRequest;
use App\Http\Requests\Column\UpdateColumnRequest;
use App\Http\Resources\ColumnResource;
use App\Models\Board;
use App\Models\Column;
use App\Services\ColumnService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ColumnController extends Controller
{
    public function __construct(private readonly ColumnService $columnService) {}

    public function store(StoreColumnRequest $request, Board $board): JsonResponse
    {
        $column = $this->columnService->create($board, StoreColumnData::fromRequest($request));

        return response()->json(new ColumnResource($column), 201);
    }

    public function update(UpdateColumnRequest $request, Column $column): JsonResponse
    {
        $column = $this->columnService->update($column, $request->validated());

        return response()->json(new ColumnResource($column));
    }

    public function destroy(Request $request, Column $column): JsonResponse
    {
        $this->columnService->deleteForUser($request->user(), $column);

        return response()->json(null, 204);
    }
}
