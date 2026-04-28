<?php

namespace App\Http\Requests\Column;

use App\Models\Board;
use Illuminate\Foundation\Http\FormRequest;

class StoreColumnRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
