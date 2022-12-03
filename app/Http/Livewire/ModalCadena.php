<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cadena;

class ModalCadena extends Component
{
	public $searchCadena, $cadenas =[]; 
	protected $paginationTheme = 'bootstrap';
	
	public function liveSearch(){
		if(strlen($this->searchCadena) > 0){
			$this->cadenas = Cadena::where('nombre', 'like',"%{$this->searchCadena}%")
			->get()->take(6);
		}else{
			return $this->cadenas = [];
		}
	}

    public function render()
    {
		$this->liveSearch();
        return view('livewire.modalcadena.component');
    }

}
