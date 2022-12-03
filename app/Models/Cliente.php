<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
	
	protected $fillable = ['nombre','tipo_documento','documento','telefono','direccion','correo','provincia','licencia','vencimiento_licencia','ciudad'];

	public function getImagenAttribute()
	{
	

		if($this->image != null)
			return (file_exists('storage/clientes/' . $this->image) ? $this->image : 'noimg.jpg');
		else
			return 'noimg.jpg';		
		
	}
}
