<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seguro extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'auto_id', 'valor', 'vigencia_hasta'];

    protected function casts(): array
    {
        return [
            'valor' => 'decimal:2',
            'vigencia_hasta' => 'date',
        ];
    }

    public function auto(): BelongsTo
    {
        return $this->belongsTo(Auto::class);
    }
}
