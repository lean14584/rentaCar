<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Caja extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id', 'auto_id', 'alquiler_id', 'mantenimiento_id', 'rto_id',
        'tipo', 'monto', 'metodo_pago', 'descripcion',
    ];

    protected function casts(): array
    {
        return [
            'monto' => 'decimal:2',
        ];
    }

    /** Movimientos cargados a mano: sin vínculo con Alquileres ni Gastos. */
    public function esManual(): bool
    {
        return $this->alquiler_id === null && $this->mantenimiento_id === null && $this->rto_id === null;
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function auto(): BelongsTo
    {
        return $this->belongsTo(Auto::class);
    }

    public function alquiler(): BelongsTo
    {
        return $this->belongsTo(Alquiler::class);
    }

    public function mantenimiento(): BelongsTo
    {
        return $this->belongsTo(Mantenimiento::class);
    }

    public function rto(): BelongsTo
    {
        return $this->belongsTo(Rto::class);
    }
}
