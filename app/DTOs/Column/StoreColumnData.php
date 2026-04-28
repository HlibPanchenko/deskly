<?php

namespace App\DTOs\Column;

use App\Http\Requests\Column\StoreColumnRequest;

readonly class StoreColumnData
{
    public function __construct(
        public string $name,
    ) {}

    public static function fromRequest(StoreColumnRequest $request): self
    {
        return new self(
            name: $request->validated('name'),
        );
    }
}
