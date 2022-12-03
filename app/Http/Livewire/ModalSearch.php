<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Auto;

class ModalSearch extends Component
{
	public $search, $products =[]; 
	protected $paginationTheme = 'bootstrap';
	
	public function liveSearch(){
		if(strlen($this->search) > 0){
			$this->products = Auto::join('marcas as m','m.id','autos.marca_id')
			->select('autos.*', 'm.nombre as nombreMarca')
			->where('m.nombre', 'like',"%{$this->search}%")
			->orWhere('autos.patente', 'like',"%{$this->search}%")
			->orWhere('autos.id', 'asc')
			->get()->take(6);
		}else{
			return $this->products = [];
		}
	}

    public function render()
    {
		$this->liveSearch();
        return view('livewire.modalsearch.component');
    }
	public function addAll(){
		if(count($this->products) > 0){
			foreach($this->products as $product){
				$this->emit('scan-code-byid',$product->id);
			}
		}
	}
}
