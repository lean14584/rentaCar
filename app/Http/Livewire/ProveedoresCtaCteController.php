<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\ProveedoresCtaCte;
use App\Models\Caja;
use App\Models\Proveedor;
use App\Models\Auto;

class ProveedoresCtaCteController extends Component
{
	use WithPagination;
    use WithFileUploads;
	
	public $id_proveedor,$tipo,$idAuto,$descripcion,$id_mantenimiento,$importe,$metodo_pago,$selected_id;
    public $pageTitle, $componentName,$search,$param,$name,$monto,$suma,$resta,$sumaVista,$restaVista;
    private $pagination = 1000;

	protected $paginationTheme = 'bootstrap';
	
    public function mount($param,$name)
    {
       $this->param = $param;
	   $this->name = $name;
       $this->pageTitle ='Listado';
       $this->componentName ='Cta. Cte. Proveedor';
	}

   public function render()

    {
		$this->suma = ProveedoresCtaCte::where('tipo', 1 )
		->where('id_proveedor', $this->param)
        ->get();
		
		 $descuento = $this->suma->sum(function($item){
			 return $item->importe;
		});
		$this->sumaVista = $descuento;
		
		$this->resta = ProveedoresCtaCte::where('tipo', 2 )
		->where('id_proveedor', $this->param )
        ->get();
		
		 $resta = $this->resta->sum(function($item){
			 return $item->importe;
		});
	   	$this->restaVista = $resta;
		
		if(strlen($this->search) > 0)
			$data = ProveedoresCtaCte::join('proveedors as p','p.id','proveedores_cta_ctes.id_proveedor')
			->select('proveedores_cta_ctes.*','p.nombre as nombre')
			->where('id_proveedor', 'like', '%' . $this->param . '%')
			->where('descripcion', 'like', '%' . $this->search . '%')
			->paginate($this->pagination);
		else
			$data = ProveedoresCtaCte::join('proveedors as p','p.id','proveedores_cta_ctes.id_proveedor')
			->select('proveedores_cta_ctes.*','p.nombre as nombre')
			->where('id_proveedor', 'like', '%' . $this->param . '%')
			->paginate($this->pagination);

		return view('livewire.proveedoresCtaCte.component', ['data' => $data,
		'proveedor' => $this->name,
		'autos' => Auto::orderBy('patente', 'asc')->get()])

	->extends('layouts.theme.app')
       ->section('content');
    }
	
	public function Store()
	{

		$ctaCte = ProveedoresCtaCte::create([
			'id_proveedor' => $this->param,
			'tipo' => 2,
			'descripcion' => $this->descripcion,
			'id_mantenimiento' => 0,
			'importe' => $this->monto,
			'metodo_pago' => $this->metodo_pago
			]);
		
		$data = Proveedor::where('id', $this->param)->get();
	
		$caja = Caja::create([
			'id_usuario' => Auth()->user()->id,
			'idAuto' => $this->idAuto,
			'tipo' => 1,
			'entrada' => 0,
			'salida' => $this->monto,
			'metodo_pago' => $this->metodo_pago,
			'descripcion' => "Egreso De Dinero Por Pago A Proveedor: " .$data[0]->nombre,
			'numero_venta' =>  0
			]);
				
		$this->resetUI();
		$this->emit('ctacte-added','Movimiento En Cta. Cte. Agregado Correctamente');

	}
	
	public function resetUI() 
	{
		$this->monto ='';
	}
}
