<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'tipo_documento', 'documento', 'telefono', 'direccion', 'correo',
        'provincia', 'licencia', 'vencimiento_licencia', 'ciudad', 'image',
    ];

    protected function casts(): array
    {
        return [
            'vencimiento_licencia' => 'date',
        ];
    }

    public function alquileres(): HasMany
    {
        return $this->hasMany(Alquiler::class);
    }

    public function ctaCte(): HasMany
    {
        return $this->hasMany(ClienteCtaCte::class, 'cliente_id');
    }
}
