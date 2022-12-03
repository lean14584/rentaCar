<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alquiler extends Model
{
    use HasFactory;

	protected $fillable = ['idAuto','idCliente','idCadena','idMatafuego','idSilla','fechaInicio','fechaFin','lugar_retiro','lugar_devolucion','metodo_pago','total','entrega','saldo','diasAlquiler','importePorDia','image'];

	//Accessor
	public function getImagenAttribute()
	{
		if($this->image != null)
			return (file_exists('storage/alquileres/' . $this->image) ? $this->image : 'noimg.jpg');
		else
			return 'noimg.jpg';		
	}
}
