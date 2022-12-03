<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
	use HasFactory;

	protected $fillable = ['nombre','image'];

	//Accessor
	public function getImagenAttribute()
	{
		if($this->image != null)
			return (file_exists('storage/marcas/' . $this->image) ? $this->image : 'noimg.jpg');
		else
			return 'noimg.jpg';		
	}
}	