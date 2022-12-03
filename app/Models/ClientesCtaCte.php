<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientesCtaCte extends Model
{
    use HasFactory;
	
	protected $fillable = ['id_cliente','tipo','descripcion','id_venta','importe','metodo_pago'];
}
