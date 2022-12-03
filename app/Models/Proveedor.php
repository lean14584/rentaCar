<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;
	
	protected $fillable = ['nombre','inicio','cuit','brutos','localidad','telefono','direccion','correo'];
	
	public function getImagenAttribute()
	{
	

		if($this->image != null)
			return (file_exists('storage/proveedores/' . $this->image) ? $this->image : 'noimg.jpg');
		else
			return 'noimg.jpg';		

		//método 2
		/*
		if($this->image == null)
		{		
			if(file_exists('storage/categories/' . $this->image))
				return $this->image;
			else
				return 'noimg.jpg';
		} else {
			return 'noimg.jpg';
		}
		*/
		
	}
}
