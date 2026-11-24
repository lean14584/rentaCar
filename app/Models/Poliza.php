<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Poliza extends Model
{
    use HasFactory;

    protected $fillable = ['empresa', 'auto_id', 'vigencia_hasta'];

    protected function casts(): array
    {
        return [
            'vigencia_hasta' => 'date',
        ];
    }

    public function auto(): BelongsTo
    {
        return $this->belongsTo(Auto::class);
    }
}
