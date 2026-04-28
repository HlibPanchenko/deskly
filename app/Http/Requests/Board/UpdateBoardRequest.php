<?php

namespace App\Http\Requests\Board;

use App\Models\Board;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBoardRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Board $board */
        $board = $this->route('board');

        return $board->user_id === $this->user()->id;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
