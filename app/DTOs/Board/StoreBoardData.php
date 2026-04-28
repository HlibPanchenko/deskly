<?php

namespace App\DTOs\Board;

use App\Http\Requests\Board\StoreBoardRequest;

readonly class StoreBoardData
{
    public function __construct(
        public string $name,
        public ?string $description,
    ) {}

    public static function fromRequest(StoreBoardRequest $request): self
    {
        return new self(
            name: $request->validated('name'),
            description: $request->validated('description'),
        );
    }
}
