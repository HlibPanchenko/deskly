<?php

namespace App\Http\Requests\Card;

use App\Models\Column;
use Illuminate\Foundation\Http\FormRequest;

class StoreCardRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Column $column */
        $column = $this->route('column');

        return $column->board->user_id === $this->user()->id;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }
}
