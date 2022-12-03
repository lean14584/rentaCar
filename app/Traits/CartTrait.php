<?php

namespace App\Traits;
use App\Models\Auto;
use DB;
use App\Models\Sale;
use App\Models\SaleDetails;
use Darryldecode\Cart\Facades\CartFacade as Cart;


trait CartTrait{
	
	public function ScanearCode($id, $cantR = 1)
	{
		$auto = Auto::where('id', $id)->first();
		
		if($auto == null)
		{
			$this->emit('scan-notfound','El Auto No Esta Registrado'.$auto);
		}
		
		else{
			if($this->InCart($auto->id))
			{	
				$this->IncreaseQuantity($auto);
				return;
			}
						
			Cart::session(Auth()->user()->id)->add($auto->id, $auto->name, $auto->price, $cant, $auto->image);
			$this->total = Cart::session(Auth()->user()->id)->getTotal();
			$this->totalNeto = Cart::session(Auth()->user()->id)->getTotal();
			$this->itemsQuantity = Cart::session(Auth()->user()->id)->getTotalQuantity();
			$this->emit('scan-ok', 'Auto Agregado');
			$this->emit('borrarModal', 'Modal Borrado');
		}
	}
	
	public function InCart($autoId)
	{
		$exist = Cart::session(Auth()->user()->id)->get($autoId);
		{
			if($exist)
				return true;
			else
				return false;
		}
	}
		
	public function IncreaseQuantity($auto, $cant = 1)
	{
			$auto = Auto::where('id', $auto->id)->first();
			//dd($auto);
			Cart::session(Auth()->user()->id)->add($auto->id, $auto->patente, $auto->importe, $cant, $auto->image);
			
			$this->total = Cart::session(Auth()->user()->id)->getTotal();
			$this->totalNeto = Cart::session(Auth()->user()->id)->getTotal();
			$this->itemsQuantity = Cart::session(Auth()->user()->id)->getTotalQuantity();
			$this->emit('scan-ok', 'Auto Agregado');
			$this->emit('borrarModal', 'Modal Borrado');
	}
	
	public function	removeItem($autoId)
	{
		$auto = Auto::where('id', $autoId)->first();

		$exist = Cart::session(Auth()->user()->id)->get($auto->id);
		
		Cart::session(Auth()->user()->id)->remove($autoId);
		$this->total = Cart::session(Auth()->user()->id)->getTotal();
		$this->totalNeto = Cart::session(Auth()->user()->id)->getTotal();
		$this->itemsQuantity = Cart::session(Auth()->user()->id)->getTotalQuantity();
		
		$this->emit('scan-ok', 'Auto Eliminado');
	}	
		
}