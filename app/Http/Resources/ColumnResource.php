<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ColumnResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'position' => $this->position,
            'board_id' => $this->board_id,
            'cards' => CardResource::collection($this->whenLoaded('cards')),
        ];
    }
}
