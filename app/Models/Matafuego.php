<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Matafuego extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'auto_id', 'fecha_inicio', 'fecha_fin', 'valor', 'estado'];

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

    public function alquileres(): HasMany
    {
        return $this->hasMany(Alquiler::class);
    }
}
