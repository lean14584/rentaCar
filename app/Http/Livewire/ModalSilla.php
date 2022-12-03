<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Silla;

class ModalSilla extends Component
{
	public $searchSilla, $cadenas =[]; 
	protected $paginationTheme = 'bootstrap';
	
	public function liveSearch(){
		if(strlen($this->searchSilla) > 0){
			$this->sillas = Silla::where('nombre', 'like',"%{$this->searchSilla}%")
			->get()->take(6);
		}else{
			return $this->sillas = [];
		}
	}

    public function render()
    {
		$this->liveSearch();
        return view('livewire.modalSilla.component');
    }

}
