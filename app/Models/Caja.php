<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    use HasFactory;
	
	protected $fillable = ['id_usuario','idAuto','tipo','entrada','salida','metodo_pago','descripcion','numero_venta','observacion'];
}
