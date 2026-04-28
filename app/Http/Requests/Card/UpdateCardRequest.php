<?php

namespace App\Http\Requests\Card;

use App\Models\Card;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCardRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Card $card */
        $card = $this->route('card');

        return $card->column->board->user_id === $this->user()->id;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'position' => ['sometimes', 'integer', 'min:0'],
            'column_id' => ['sometimes', 'integer', 'exists:columns,id'],
        ];
    }
}
