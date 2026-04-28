<?php

namespace App\Services;

use App\DTOs\Column\StoreColumnData;
use App\Models\Board;
use App\Models\Column;
use App\Models\User;

class ColumnService
{
    public function create(Board $board, StoreColumnData $data): Column
    {
        $position = $board->columns()->max('position') + 1;

        return $board->columns()->create([
            'name' => $data->name,
            'position' => $position,
        ]);
    }

    public function update(Column $column, array $data): Column
    {
        $column->update($data);

        return $column;
    }

    public function deleteForUser(User $user, Column $column): void
    {
        abort_if($column->board->user_id !== $user->id, 403);

        $column->delete();
    }
}
