<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProveedorCtaCte extends Model
{
    use HasFactory;

    protected $table = 'proveedores_cta_ctes';

    protected $fillable = ['proveedor_id', 'tipo', 'importe', 'descripcion', 'mantenimiento_id', 'rto_id', 'metodo_pago'];

    protected function casts(): array
    {
        return [
            'importe' => 'decimal:2',
        ];
    }

    /** Pago cargado a mano: no tiene gasto de origen. */
    public function esManual(): bool
    {
        return $this->mantenimiento_id === null && $this->rto_id === null;
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
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
