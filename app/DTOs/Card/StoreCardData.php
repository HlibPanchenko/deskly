<?php

namespace App\DTOs\Card;

use App\Http\Requests\Card\StoreCardRequest;

readonly class StoreCardData
{
    public function __construct(
        public string $title,
        public ?string $description,
    ) {}

    public static function fromRequest(StoreCardRequest $request): self
    {
        return new self(
            title: $request->validated('title'),
            description: $request->validated('description'),
        );
    }
}
