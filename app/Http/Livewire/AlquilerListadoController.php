<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Carbon\Carbon;
use App\Models\Auto;
use App\Models\Alquiler;
use App\Models\Cliente;
use App\Models\Cadena;
use App\Models\Matafuego;
use App\Models\Silla;
use App\Traits\CartTrait;
use DB;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class AlquilerListadoController extends Component
{
	use WithFileUploads;
	use WithPagination;
	use CartTrait;
	
	public $idAuto, $idCliente, $diasAlquiler, $importePorDia, $idCadena, $idMatafuego, $idSilla, $fechaInicio, $fechaFin, $lugar_retiro, $medioPago,
	$importePorDiaCadena, $cliente, $cadena, $matafuego, $silla, $controlMediosPagos, $importePorDiaMatafuego, $importePorDiaSilla, $lugar_devolucion, $metodo_pago, $totalVer, $total, $entrega, $saldo, $estado, $search, $image, $selected_id, $pageTitle, $componentName;
	private $pagination = 10;
	
	protected $paginationTheme = 'bootstrap';
	
	public function mount()	
	{
		$this->pageTitle = 'Listado';
		$this->medioPago = 'Elegir';
		$this->componentName = 'Alquileres De Auto';
	}
	
    public function render()
    {
		if(strlen($this->search) > 0)
			$data = Alquiler::join('autos as a','a.id','alquilers.idAuto')
			->join('clientes as c','c.id','alquilers.idCliente')
			->select('alquilers.*','a.patente as patente','c.nombre as nombre')
			->where('modelo', 'like', '%' . $this->search . '%')->paginate($this->pagination);
		else
			$data = Alquiler::join('autos as a','a.id','alquilers.idAuto')
			->join('clientes as c','c.id','alquilers.idCliente')
			->select('alquilers.*','a.patente as patente','c.nombre as nombre')
			->paginate($this->pagination);
		
		return view('livewire.alquileres2.component', ['alquileres' => $data,
		'autos' => Auto::orderBy('id', 'asc')->get(),
		'clientes' => Cliente::orderBy('id', 'asc')->get(),
		'cadenas' => Cadena::orderBy('id', 'asc')->get(),
		'matafuegos' => Matafuego::orderBy('id', 'asc')->get(),
		'sillas' => Silla::orderBy('id', 'asc')->get()])
		->extends('layouts.theme.app')
		->section('content');
       
    }
	
	public function cargarValorAlquiler(){
		$auto = Auto::find($this->idAuto);
		$this->importePorDia = $auto->importe;
	}
	
	public function cargarValorCadenas(){
		$cadena = Cadena::find($this->idCadena);
		$this->importePorDiaCadena = $cadena->valor;
	}
	
	public function cargarValorMatafuegos(){
		$matafuego = Matafuego::find($this->idMatafuego);
		$this->importePorDiaMatafuego = $matafuego->valor;
	}
	
	public function cargarValorSillas(){
		$silla = Silla::find($this->idSilla);
		$this->importePorDiaSilla = $silla->valor;
	}

	public function cargarValorAlquilerTotal(){
		$fechaFin = Carbon::parse($this->fechaFin);
		$fechaIni = Carbon::parse($this->fechaInicio);
		$dias = $fechaFin ->diffInDays($fechaIni);
		$auto = ($dias + 1) * $this->importePorDia;
		$cadena = ($dias + 1) * $this->importePorDiaCadena;
		$matafuego = ($dias + 1) * $this->importePorDiaMatafuego;
		$silla = ($dias + 1) * $this->importePorDiaSilla;
		$this->totalVer = $auto + $cadena + $matafuego + $silla;
	}	
	
	public function cargarValorSaldo(){
		$this->saldo = $this->total - $this->entrega;
	}
	
	public function Edit($id)
	{
		$record = Alquiler::find($id, ['id','idAuto','idCliente','idCadena','idMatafuego','idSilla','fechaInicio','fechaFin','lugar_retiro','lugar_devolucion','metodo_pago','total','entrega','saldo','image','importePorDia']);
		$this->idAuto = $record->idAuto;
		$this->idCliente = $record->idCliente;
		$this->idCadena = $record->idCadena;
		$this->idMatafuego = $record->idMatafuego;
		$this->idSilla = $record->idSilla;
		$this->diasAlquiler = $record->diasAlquiler;
		$this->fechaInicio = $record->fechaInicio;
		$this->fechaFin = $record->fechaFin;
		$this->lugar_retiro = $record->lugar_retiro;
		$this->lugar_devolucion = $record->lugar_devolucion;
		$this->metodo_pago = $record->metodo_pago;
		$this->importePorDia = $record->importePorDia;
		$this->total = $record->total;
		$this->entrega = $record->entrega;
		$this->saldo = $record->saldo;
		$this->selected_id = $record->id;
		$this->image = null;

		$this->emit('show-modal', 'show modal!');
	}

	public function Store()
	{
		$rules = [
			'idAuto' => 'required',
			'idCliente' => 'required',
			'fechaInicio' => 'required',
			'fechaFin' => 'required',
			'lugar_retiro' => 'required',
			'importePorDia' => 'required',
			'lugar_devolucion' => 'required',
			'metodo_pago' => 'required',
			'entrega' => 'required',
			'saldo' => 'required'
		];

		$messages = [
			'idAuto.required' => 'El Auto Es Requerido',
			'idCliente.unique' => 'El Cliente Es Requerido',
			'fechaInicio.min' => 'La Fecha De Inicio Es Requerida',
			'fechaFin.required' => 'La Fecha De Fin Es Requerida',
			'importePorDia.required' => 'El Importe Es Requerido',
			'lugar_retiro.required' => 'El Lugar De Retiro Es Requerido',
			'lugar_devolucion.required' => 'El Lugar De Devolucion Es Requerido',
			'metodo_pago.required' => 'El Metodo De Pago Requerido',
			'entrega.required' => 'La Entrega Es Requerida',
			'saldo.required' => 'El Saldo Es Requerido'
		];

		$this->validate($rules, $messages);
		
		$fechaFin = Carbon::parse($this->fechaFin);
		$fechaIni = Carbon::parse($this->fechaInicio);
		$this->diasAlquiler = $fechaFin ->diffInDays($fechaIni);
		
		$alquiler = Alquiler::create([
			'idAuto' => $this->idAuto,
			'idCliente' => $this->idCliente,
			'idCadena' => $this->idCadena,
			'idMatafuego' => $this->idMatafuego,
			'idSilla' => $this->idSilla,
			'fechaInicio' => $this->fechaInicio,
			'fechaFin' => $this->fechaFin,
			'lugar_retiro' => $this->lugar_retiro,
			'importePorDia' => $this->importePorDia,
			'lugar_devolucion' => $this->lugar_devolucion,
			'diasAlquiler' => $this->diasAlquiler + 1,
			'metodo_pago' => $this->metodo_pago,
			'total' => $this->total,
			'entrega' => $this->entrega,
			'saldo' => $this->saldo		
		]);

		$customFileName;
		if($this->image)
		{
			
			$customFileName = uniqid() . '_.' . $this->image->extension();
			$this->image->storeAs('public/alquileres', $customFileName);
			$alquiler->image = $customFileName;
			$alquiler->save();
		}

		$this->resetUI();
		$this->emit('alquiler-added','Alquiler Registrado');

	}

	public function Update()
	{
		$rules = [
			'idAuto' => 'required',
			'idCliente' => 'required',
			'fechaInicio' => 'required',
			'fechaFin' => 'required',
			'lugar_retiro' => 'required',
			'importePorDia' => 'required',
			'lugar_devolucion' => 'required',
			'metodo_pago' => 'required',
			'total' => 'required',
			'entrega' => 'required',
			'saldo' => 'required'
		];

		$messages = [
			'idAuto.required' => 'El Auto Es Requerido',
			'idCliente.unique' => 'El Cliente Es Requerido',
			'fechaInicio.min' => 'La Fecha De Inicio Es Requerida',
			'fechaFin.required' => 'La Fecha De Fin Es Requerida',
			'importePorDia.required' => 'El Importe Es Requerido',
			'lugar_retiro.required' => 'El Lugar De Retiro Es Requerido',
			'lugar_devolucion.required' => 'El Lugar De Devolucion Es Requerido',
			'metodo_pago.required' => 'El Metodo De Pago Requerido',
			'total.required' => 'El Total Es Requerido',
			'entrega.required' => 'La Entrega Es Requerida',
			'saldo.required' => 'El Saldo Es Requerido'
		];

		$this->validate($rules, $messages);

		$fechaFin = Carbon::parse($this->fechaFin);
		$fechaIni = Carbon::parse($this->fechaInicio);
		$this->diasAlquiler = $fechaFin ->diffInDays($fechaIni);
		
		$alquiler = Alquiler::find($this->selected_id);
		$alquiler->update([
			'idAuto' => $this->idAuto,
			'idCliente' => $this->idCliente,
			'idCadena' => $this->idCadena,
			'idMatafuego' => $this->idMatafuego,
			'idSilla' => $this->idSilla,
			'fechaInicio' => $this->fechaInicio,
			'importePorDia' => $this->importePorDia,
			'fechaFin' => $this->fechaFin,
			'lugar_retiro' => $this->lugar_retiro,
			'lugar_devolucion' => $this->lugar_devolucion,
			'diasAlquiler' => $this->diasAlquiler + 1,
			'metodo_pago' => $this->metodo_pago,
			'total' => $this->total,
			'entrega' => $this->entrega,
			'saldo' => $this->saldo		
		]);

		if($this->image)
		{
			$customFileName = uniqid() . '_.' . $this->image->extension();
			$this->image->storeAs('public/alquileres', $customFileName);
			$imageName = $alquiler->image;

			$alquiler->image = $customFileName;
			$alquiler->save();

			if($imageName !=null)
			{
				if(file_exists('storage/alquileres' . $imageName))
				{
					unlink('storage/alquileres' . $imageName);
				}
			}

		}

		$this->resetUI();
		$this->emit('alquiler-updated', 'Alquiler Actualizado');

	}

	public function resetUI() 
	{

		$this->idAuto ='';
		$this->idCliente = 0;
		$this->idCadena = 0;
		$this->idMatafuego = 0;
		$this->idSilla = 0;
		$this->cliente = 'Buscar Cliente F2';
		$this->cadena = 'Cadena F3';
		$this->matafuego = 'Matafuego F4';
		$this->silla = 'S. De Bebe F7';
		$this->fechaInicio='';
		$this->fechaFin='';
		$this->importePorDia='';
		$this->diasAlquiler='';
		$this->lugar_retiro='';
		$this->lugar_devolucion='';
		$this->metodo_pago='';
		$this->total='';
		$this->entrega='';
		$this->saldo='';
		$this->image = null;
		$this->search ='';
		$this->selected_id =0;
		Cart::session(Auth()->user()->id)->clear();
		$this->total = Cart::session(Auth()->user()->id)->getTotal();
		$this->totalVer = 0;
	}	
	
	protected $listeners =['deletedRow' => 'Destroy'];
	
	public function Destroy(Alquiler $alquiler)
	{   	
		$imageName = $alquiler->image; 
		$alquiler->delete();

		if($imageName !=null) {
			unlink('storage/alquileres/' . $imageName);
		}

		$this->resetUI();
		$this->emit('alquiler-deleted', 'Alquiler Eliminado');
	}
	
}
