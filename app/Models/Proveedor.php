<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proveedor extends Model
{
    use HasFactory;

    // Eloquent pluraliza "Proveedor" al inglés ("proveedors", solo agrega
    // una s) — la tabla real está en español ("proveedores"). Mismo tipo de
    // desajuste que tenía Alquiler/alquilers en la app vieja.
    protected $table = 'proveedores';

    protected $fillable = ['nombre', 'inicio', 'cuit', 'brutos', 'localidad', 'telefono', 'direccion', 'correo', 'image'];

    protected function casts(): array
    {
        return [
            'inicio' => 'date',
        ];
    }

    public function mantenimientos(): HasMany
    {
        return $this->hasMany(Mantenimiento::class);
    }
}
