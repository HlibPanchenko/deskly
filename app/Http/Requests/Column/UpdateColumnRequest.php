<?php

namespace App\Http\Requests\Column;

use App\Models\Column;
use Illuminate\Foundation\Http\FormRequest;

class UpdateColumnRequest extends FormRequest
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
            'name' => ['sometimes', 'string', 'max:255'],
            'position' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
