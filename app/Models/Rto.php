<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rto extends Model
{
    use HasFactory;

    protected $fillable = ['empresa', 'auto_id', 'fecha_inicio', 'fecha_fin', 'valor', 'metodo_pago'];

    protected function casts(): array
    {
        return [
            'valor' => 'decimal:2',
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
        ];
    }

    public function auto(): BelongsTo
    {
        return $this->belongsTo(Auto::class);
    }
}
