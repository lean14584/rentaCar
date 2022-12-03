<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProveedoresCtaCte extends Model
{
    use HasFactory;
	
	protected $fillable = ['id_proveedor','tipo','descripcion','id_mantenimiento','importe','metodo_pago'];
}
