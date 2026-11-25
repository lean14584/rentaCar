<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Caja extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id', 'auto_id', 'caja_sesion_id', 'alquiler_id', 'mantenimiento_id', 'rto_id',
        'cliente_cta_cte_id', 'proveedor_cta_cte_id', 'tipo', 'monto', 'metodo_pago', 'descripcion',
    ];

    protected function casts(): array
    {
        return [
            'monto' => 'decimal:2',
        ];
    }

    /** Movimientos cargados a mano: sin vínculo con ningún módulo de origen. */
    public function esManual(): bool
    {
        return $this->alquiler_id === null
            && $this->mantenimiento_id === null
            && $this->rto_id === null
            && $this->cliente_cta_cte_id === null
            && $this->proveedor_cta_cte_id === null;
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function auto(): BelongsTo
    {
        return $this->belongsTo(Auto::class);
    }

    public function cajaSesion(): BelongsTo
    {
        return $this->belongsTo(CajaSesion::class);
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

    public function clienteCtaCte(): BelongsTo
    {
        return $this->belongsTo(ClienteCtaCte::class);
    }

    public function proveedorCtaCte(): BelongsTo
    {
        return $this->belongsTo(ProveedorCtaCte::class);
    }
}
