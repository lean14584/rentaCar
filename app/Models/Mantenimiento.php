<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mantenimiento extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'auto_id', 'km_actual', 'proveedor_id', 'km_proximo',
        'valor', 'estado', 'fecha_inicio', 'fecha_fin', 'dias_arreglo', 'metodo_pago',
    ];

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

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }
}
