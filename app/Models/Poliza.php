<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poliza extends Model
{
    use HasFactory;
	
	protected $fillable = ['empresa','idAuto','vigencia'];
	
	public function getImagenAttribute()
	{
	
		if($this->image != null)
			return (file_exists('storage/polizas/' . $this->image) ? $this->image : 'noimg.jpg');
		else
			return 'noimg.jpg';		
		
	}
}	