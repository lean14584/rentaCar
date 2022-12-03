<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Silla extends Model
{
    use HasFactory;
	
	protected $fillable = ['nombre','idAuto','fechaInicio','fechaFin','valor','estado'];

	public function getImagenAttribute()
	{
	
		if($this->image != null)
			return (file_exists('storage/sillas/' . $this->image) ? $this->image : 'noimg.jpg');
		else
			return 'noimg.jpg';		
		
	}
}	