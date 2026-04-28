<?php

namespace App\Services;

use App\DTOs\Board\StoreBoardData;
use App\Models\Board;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class BoardService
{
    public function listForUser(User $user): Collection
    {
        return $user->boards()->latest()->get();
    }

    public function create(User $user, StoreBoardData $data): Board
    {
        return $user->boards()->create([
            'name' => $data->name,
            'description' => $data->description,
        ]);
    }

    public function findForUser(User $user, Board $board): Board
    {
        abort_if($board->user_id !== $user->id, 403);

        return $board->load('columns.cards');
    }

    public function update(Board $board, array $data): Board
    {
        $board->update($data);

        return $board;
    }

    public function deleteForUser(User $user, Board $board): void
    {
        abort_if($board->user_id !== $user->id, 403);

        $board->delete();
    }
}
