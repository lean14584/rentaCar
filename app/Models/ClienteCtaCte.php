<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClienteCtaCte extends Model
{
    use HasFactory;

    protected $table = 'clientes_cta_ctes';

    protected $fillable = ['cliente_id', 'tipo', 'importe', 'descripcion', 'alquiler_id', 'metodo_pago'];

    protected function casts(): array
    {
        return [
            'importe' => 'decimal:2',
        ];
    }

    /** Pago cargado a mano: no tiene alquiler de origen. */
    public function esManual(): bool
    {
        return $this->alquiler_id === null;
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function alquiler(): BelongsTo
    {
        return $this->belongsTo(Alquiler::class);
    }
}
