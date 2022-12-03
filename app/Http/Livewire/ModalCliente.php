<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cliente;

class ModalCliente extends Component
{
	public $searchCliente, $clientes =[]; 
	protected $paginationTheme = 'bootstrap';
	
	public function liveSearch(){
		if(strlen($this->searchCliente) > 0){
			$this->clientes = Cliente::where('nombre', 'like',"%{$this->searchCliente}%")
			->get()->take(6);
		}else{
			return $this->clientes = [];
		}
	}

    public function render()
    {
		$this->liveSearch();
        return view('livewire.modalcliente.component');
    }

}
