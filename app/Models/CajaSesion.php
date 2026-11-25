<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CajaSesion extends Model
{
    use HasFactory;

    // Eloquent pluraliza "CajaSesion" al inglés ("caja_sesions") — la tabla
    // real está en español ("caja_sesiones").
    protected $table = 'caja_sesiones';

    protected $fillable = [
        'fecha', 'usuario_apertura_id', 'usuario_cierre_id', 'monto_apertura',
        'monto_cierre', 'monto_esperado', 'diferencia', 'hora_apertura', 'hora_cierre', 'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'monto_apertura' => 'decimal:2',
            'monto_cierre' => 'decimal:2',
            'monto_esperado' => 'decimal:2',
            'diferencia' => 'decimal:2',
            'hora_apertura' => 'datetime',
            'hora_cierre' => 'datetime',
        ];
    }

    public function usuarioApertura(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_apertura_id');
    }

    public function usuarioCierre(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_cierre_id');
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(Caja::class);
    }

    public static function abierta(): ?self
    {
        return static::where('estado', 'Abierta')->first();
    }
}
