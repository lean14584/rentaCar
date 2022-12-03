<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auto extends Model
{
    use HasFactory;

	protected $fillable = ['patente','titular','kilometros','cambioAceite','combustible','modelo','marca_id','tipo','color','estado','importe'];

	//Accessor
	public function getImagenAttribute()
	{
		if($this->image != null)
			return (file_exists('storage/autos/' . $this->image) ? $this->image : 'noimg.jpg');
		else
			return 'noimg.jpg';		
	}
}
