<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Caja;
use App\Models\User;
use App\Models\Auto;

class CajaController extends Component
{
	use WithPagination;
    use WithFileUploads;
	
	public $pageTitle, $componentName,$search,$tipo,$idAuto,$selected_id,$metodo_pago,$monto,$suma,$resta,$sumaVista,$restaVista;
    private $pagination = 1000;

	protected $paginationTheme = 'bootstrap';
	
    public function mount()
    {
       $this->pageTitle ='Listado';
       $this->componentName ='Movimientos De Caja';
	}

   public function render()
    {
		
		$this->suma = Caja::orderBy('id','desc')
        ->get();
		
		 $descuento = $this->suma->sum(function($item){
			 return $item->entrada;
		});
		$this->sumaVista = $descuento;
		
		$this->resta = Caja::orderBy('id','desc')
        ->get();
		
		 $resta = $this->resta->sum(function($item){
			 return $item->salida;
		});
	   	$this->restaVista = $resta;
		
		if(strlen($this->search) > 0)
			$data = Caja::join('users as u','u.id','cajas.id_usuario')
			->join('autos as a','a.id','cajas.idAuto')
			->select('cajas.*','u.name as nombre','a.patente as patenteVer')
			->where('medio_pago', 'like', '%' . $this->search . '%')
			->orwhere('descripcion', 'like', '%' . $this->search . '%')
			->paginate($this->pagination);
		else
			
		$data = Caja::join('users as u','u.id','cajas.id_usuario')
			->join('autos as a','a.id','cajas.idAuto')
			->select('cajas.*','u.name as nombre','a.patente as patenteVer')
			->paginate($this->pagination);
		
		return view('livewire.caja.component', ['data' => $data])

	->extends('layouts.theme.app')
       ->section('content');
    }
	
	public function Store()
	{
		if($this->tipo=="Egreso De Dinero Manual Desde Caja"){
			$tipo = 0;
			$entradaDinero = 0;
			$salidaDinero = $this->monto;
		}
		else{
			$tipo = 1;
			$entradaDinero = $this->monto;
			$salidaDinero = 0;
		}
		$caja = Caja::create([
			'id_usuario' => Auth()->user()->id,
			'idAuto' => $this->idAuto,
			'tipo' => $tipo,
			'entrada' => $entradaDinero,
			'salida' => $salidaDinero,
			'metodo_pago' => $this->metodo_pago,
			'descripcion' => $this->tipo,
			'numero_venta' => 0,
			]);

		$this->resetUI();
		$this->emit('ctacte-added','Movimiento En Caja Agregado Correctamente');

	}
	
	public function resetUI() 
	{
		$this->monto ='';
	}
}
