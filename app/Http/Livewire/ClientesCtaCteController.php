<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\ClientesCtaCte;
use App\Models\Cliente;
use App\Models\PagoDetails;
use App\Models\Sale;
use App\Models\Caja;
use App\Models\SaleDetail;
use App\Models\Descuento;

class ClientesCtaCteController extends Component
{
	use WithPagination;
    use WithFileUploads;
	
	public $id_cliente,$tipo,$descripcion,$id_venta,$importe,$metodo_pago,$selected_id,$suma,$resta,$sumaVista,$restaVista,$descuentos, $sumDescuento;
    public $pageTitle, $componentName,$search,$param,$name,$monto,$details, $sumDetails, $countDetails,$saleId, $mediosDePago,$countDescuento,$clienteVer;
    private $pagination = 1000;

	protected $paginationTheme = 'bootstrap';
	
    public function mount($param,$name)
    {
       $this->param = $param;
	   $this->name = $name;
       $this->pageTitle ='Listado';
       $this->componentName ='Cta. Cte. Cliente';
	   $this->sumDetails =0;
       $this->countDetails =0;
	   $this->sumDescuento = 0;
	   $this->countDescuento = 0;
       $this->saleId =0;
	   $this->mediosDePago = [];
	   $this->descuentos = [];
	   $this->details =[];
	   $this->clienteVer =[];
	}

   public function render()

    {
		$this->suma = ClientesCtaCte::where('tipo', 1 )
		->where('id_cliente', $this->param)
        ->get();
		
		 $descuento = $this->suma->sum(function($item){
			 return $item->importe;
		});
		$this->sumaVista = $descuento;
		
		$this->resta = ClientesCtaCte::where('tipo', 2 )
		->where('id_cliente', $this->param )
        ->get();
		
		 $resta = $this->resta->sum(function($item){
			 return $item->importe;
		});
	   	$this->restaVista = $resta;
		
		if(strlen($this->search) > 0)
			$data = ClientesCtaCte::join('clientes as c','c.id','clientes_cta_ctes.id_cliente')
			->select('clientes_cta_ctes.*','c.nombre as nombre')
			->where('id_cliente', 'like', '%' . $this->param . '%')
			->where('descripcion', 'like', '%' . $this->search . '%')
			->paginate($this->pagination);
		else
			$data = ClientesCtaCte::join('clientes as c','c.id','clientes_cta_ctes.id_cliente')
			->select('clientes_cta_ctes.*','c.nombre as nombre')
			->where('id_cliente', 'like', '%' . $this->param . '%')->paginate($this->pagination);

		return view('livewire.clientesCtaCte.component', ['data' => $data, 'cliente' => $this->name ])

	->extends('layouts.theme.app')
       ->section('content');
    }
	
	public function Store()
	{

		$ctaCteCliente = ClientesCtaCte::create([
			'id_cliente' => $this->param,
			'tipo' => 2,
			'descripcion' => $this->descripcion,
			'id_venta' => 0,
			'importe' => $this->monto,
			'metodo_pago' => $this->metodo_pago
			]);
		
		$data = Cliente::where('id', $this->param)->get();
		
		$caja = Caja::create([
			'id_usuario' => Auth()->user()->id,
			'tipo' => 1,
			'entrada' => $this->monto,
			'salida' => 0,
			'medio_pago' => $this->metodo_pago,
			'descripcion' => "Ingreso De Dinero Por Cobro A Cliente: " .$data[0]->nombre,
			'numero_venta' =>  0
			]);
			
		$this->resetUI();
		$this->emit('ctacte-added','Movimiento En Cta. Cte. Agregado Correctamente');

	}
	
	public function getDetailsCtaCte($saleId)
    {
        $this->details = SaleDetail::join('products as p','p.id','sale_details.product_id')
        ->select('sale_details.id','sale_details.price','sale_details.quantity','sale_details.status','p.name as product')
        ->where('sale_details.sale_id', $saleId)
		->where('sale_details.status','<>',3)
        ->get();
		
		$this->clienteVer = Sale::join('clientes as c','c.id','sales.id_cliente')
       ->select('c.nombre as cliente')
       ->where('sales.id', $saleId)
       ->get();
	   
        $suma = $this->details->sum(function($item){
			if($item->status==1 OR $item->status==2){
            return $item->price * $item->quantity;
			}
        });
		
		$cantidad = $this->details->sum(function($item){
			if($item->status==1 OR $item->status==2){
            return $item->quantity;
			}
        });
		
		$this->descuentos = Descuento::where('sale_id', $saleId )
        ->get();
	   
        $descuento = $this->descuentos->sum(function($item){
			 return $item->monto;
		});
		
		$descuentoCantidad = $this->descuentos->sum(function($item){
			 return $item->cantidad;
		});
		
		$suma = $this->details->sum(function($item){
			if($item->status==1 OR $item->status==2){
            return $item->price * $item->quantity;
			}
        });
		
		$cantidad = $this->details->sum(function($item){
			if($item->status==1 OR $item->status==2){
            return $item->quantity;
			}
        });

        $this->sumDetails = $suma;
		$this->sumDescuento = $descuento;
		$this->countDescuento = $descuentoCantidad;

        $this->sumDetails = $suma;
        $this->countDetails = $cantidad;
        $this->saleId = $saleId;
		
		$this->mediosDePago = PagoDetails::where('sale_id', $saleId )
        ->get();
		
        $this->emit('detalle-compra','details loaded');

    }
	
	public function resetUI() 
	{
		$this->monto ='';
	}
}
