<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Auto extends Model
{
    use HasFactory;

    protected $fillable = [
        'patente', 'titular', 'kilometros', 'cambio_aceite', 'combustible',
        'modelo', 'marca_id', 'tipo', 'color', 'estado', 'precio_alquiler', 'image',
    ];

    protected function casts(): array
    {
        return [
            'precio_alquiler' => 'decimal:2',
        ];
    }

    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class);
    }

    public function seguros(): HasMany
    {
        return $this->hasMany(Seguro::class);
    }

    public function polizas(): HasMany
    {
        return $this->hasMany(Poliza::class);
    }

    public function mantenimientos(): HasMany
    {
        return $this->hasMany(Mantenimiento::class);
    }

    public function rtos(): HasMany
    {
        return $this->hasMany(Rto::class);
    }

    public function cadenas(): HasMany
    {
        return $this->hasMany(Cadena::class);
    }

    public function matafuegos(): HasMany
    {
        return $this->hasMany(Matafuego::class);
    }

    public function sillas(): HasMany
    {
        return $this->hasMany(Silla::class);
    }

    public function alquileres(): HasMany
    {
        return $this->hasMany(Alquiler::class);
    }

    public function cajas(): HasMany
    {
        return $this->hasMany(Caja::class);
    }
}
