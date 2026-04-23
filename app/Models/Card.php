<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Card extends Model
{
    protected $fillable = ['title', 'description', 'position'];

    public function column(): BelongsTo
    {
        return $this->belongsTo(Column::class);
    }
}
