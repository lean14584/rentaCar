<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Matafuego;

class ModalMatafuego extends Component
{
	public $searchMatafuego, $cadenas =[]; 
	protected $paginationTheme = 'bootstrap';
	
	public function liveSearch(){
		if(strlen($this->searchMatafuego) > 0){
			$this->matafuegos = Matafuego::where('nombre', 'like',"%{$this->searchMatafuego}%")
			->get()->take(6);
		}else{
			return $this->matafuegos = [];
		}
	}

    public function render()
    {
		$this->liveSearch();
        return view('livewire.modalMatafuego.component');
    }

}
