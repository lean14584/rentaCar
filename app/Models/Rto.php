<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rto extends Model
{
    use HasFactory;
	
	protected $fillable = ['empresa','idAuto','fechaInicio','fechaFin','valor','metodo_pago'];

public function getImagenAttribute()
	{
	
		if($this->image != null)
			return (file_exists('storage/rtos/' . $this->image) ? $this->image : 'noimg.jpg');
		else
			return 'noimg.jpg';		
		
	}
}	