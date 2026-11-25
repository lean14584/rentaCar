<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alquiler extends Model
{
    use HasFactory;

    protected $fillable = [
        'auto_id', 'cliente_id', 'cadena_id', 'matafuego_id', 'silla_id', 'estado',
        'fecha_inicio', 'fecha_fin', 'lugar_retiro', 'lugar_devolucion', 'metodo_pago',
        'total', 'entrega', 'saldo', 'dias_alquiler', 'importe_por_dia', 'image',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
            'total' => 'decimal:2',
            'entrega' => 'decimal:2',
            'saldo' => 'decimal:2',
            'importe_por_dia' => 'decimal:2',
        ];
    }

    public function auto(): BelongsTo
    {
        return $this->belongsTo(Auto::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function cadena(): BelongsTo
    {
        return $this->belongsTo(Cadena::class);
    }

    public function matafuego(): BelongsTo
    {
        return $this->belongsTo(Matafuego::class);
    }

    public function silla(): BelongsTo
    {
        return $this->belongsTo(Silla::class);
    }

    /**
     * Alquileres NO finalizados/cancelados del mismo auto cuyo rango de
     * fechas se cruza con [$inicio, $fin] (ambos límites inclusive, igual
     * que la app vieja que cuenta el día de devolución como parte del
     * alquiler). La app vieja no validaba esto — el mismo auto se podía
     * reservar dos veces para las mismas fechas sin ningún aviso.
     */
    public function scopeSuperpuestoCon(Builder $query, int $autoId, string $inicio, string $fin, ?int $exceptId = null): Builder
    {
        return $query
            ->where('auto_id', $autoId)
            ->whereNotIn('estado', ['Finalizado', 'Cancelado'])
            ->where('fecha_inicio', '<=', $fin)
            ->where('fecha_fin', '>=', $inicio)
            ->when($exceptId, fn ($q) => $q->whereKeyNot($exceptId));
    }
}
