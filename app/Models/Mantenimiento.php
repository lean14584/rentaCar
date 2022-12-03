<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mantenimiento extends Model
{
    use HasFactory;
	
	protected $fillable = ['nombre','idAuto','kmActual','idProveedor','kmProximo','valor','estado','fechaInicio','fechaFin','diasArreglo','metodo_pago'];
	
	public function getImagenAttribute()
	{
	
		if($this->image != null)
			return (file_exists('storage/mantenimientos/' . $this->image) ? $this->image : 'noimg.jpg');
		else
			return 'noimg.jpg';		
		
	}
}	