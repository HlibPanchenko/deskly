<?php

namespace App\Services;

use App\DTOs\Card\StoreCardData;
use App\Models\Card;
use App\Models\Column;
use App\Models\User;

class CardService
{
    public function create(Column $column, StoreCardData $data): Card
    {
        $position = $column->cards()->max('position') + 1;

        return $column->cards()->create([
            'title' => $data->title,
            'description' => $data->description,
            'position' => $position,
        ]);
    }

    public function findForUser(User $user, Card $card): Card
    {
        abort_if($card->column->board->user_id !== $user->id, 403);

        return $card;
    }

    public function update(Card $card, array $data): Card
    {
        $card->update($data);

        return $card;
    }

    public function deleteForUser(User $user, Card $card): void
    {
        abort_if($card->column->board->user_id !== $user->id, 403);

        $card->delete();
    }
}
