<?php

namespace App\Http\Livewire;

use App\Models\Auto;
use App\Models\Mantenimiento;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Proveedor;
use Carbon\Carbon;
use App\Models\Caja;
use App\Models\ProveedoresCtaCte;

class MantenimientosController extends Component
{

	use WithFileUploads;
	use WithPagination;

	public $nombre, $idAuto = 1, $metodo_pago, $valor, $kmActual , $estado, $fechaInicio, $fechaFin, $diasArreglo, $kmProximo, $idProveedor,$search, $image, $selected_id, $pageTitle, $componentName;
	private $pagination = 10;
	
	protected $paginationTheme = 'bootstrap';
	
	public function mount()
	{
		$this->pageTitle = 'Listado';
		$this->componentName = 'Gastos';
		$this->idAuto = 'Elegir';
	}

	public function render()
	{
		if(strlen($this->search) > 0)
			$data = Mantenimiento::join('autos as a','a.id','mantenimientos.idAuto')
			->join('proveedors as p','p.id','mantenimientos.idProveedor')
			->select('mantenimientos.*','a.patente as patente','p.nombre as idProveedor')
			->where('empresa', 'like', '%' . $this->search . '%')->paginate($this->pagination);
		else
			$data = Mantenimiento::join('autos as a','a.id','mantenimientos.idAuto')
			->join('proveedors as p','p.id','mantenimientos.idProveedor')
			->select('mantenimientos.*','a.patente as patente','p.nombre as idProveedor')
			->paginate($this->pagination);
		
		return view('livewire.mantenimientos.component', ['mantenimietos' => $data,
		'autos' => Auto::orderBy('patente', 'asc')->get(),
		'proveedores' => Proveedor::orderBy('id', 'asc')->get()])
		->extends('layouts.theme.app')
		->section('content');
	}

	public function Edit($id)
	{
		$record = Mantenimiento::find($id, ['id','nombre','idProveedor','kmActual','kmProximo','fechaInicio','fechaFin','idAuto','valor','image']);
		$this->nombre = $record->nombre;
		$this->idAuto = $record->idAuto;
		$this->valor = $record->valor;
		$this->idProveedor = $record->idProveedor;
		$this->kmActual = $record->kmActual;
		$this->kmProximo = $record->kmProximo;
		$this->fechaInicio = $record->fechaInicio;
		$this->fechaFin = $record->fechaFin;
		//$this->diasArreglo = $record->diasArreglo;
		$this->selected_id = $record->id;
		$this->image = null;

		$this->emit('show-modal', 'show modal!');
	}

	public function Store()
	{
		$rules = [
			'nombre' => 'required',
			'idAuto' => 'required',
			'kmActual' => 'required',
			'kmProximo' => 'required',
			'idProveedor' => 'required',
			'fechaInicio' => 'required',
			'fechaFin' => 'required',
			'valor' => 'required'
		];

		$messages = [
			'nombre.required' => 'El Nombre Es Requerido',
			'idAuto.required' => 'La Patente Es Requerida',
			'kmActual.required' => 'El Kilometraje Actual Es Requerido',
			'kmProximo.required' => 'El Kilometraje Del Proximo Mantenimiento Es Requerido',
			'idProveedor.required' => 'El Proveedor Es Requerido',
			'fechaInicio.required' => 'La Fecha De Inicio Es Requerida',
			'fechaFin.required' => 'La Fecha De Finalizacion Es Requerida',
			'valor.required' => 'El Monto Es Requerido'
		];

		$this->validate($rules, $messages);
		$fechaFin = Carbon::parse($this->fechaFin);
		$fechaIni = Carbon::parse($this->fechaInicio);
		$this->diasArreglo = $fechaFin ->diffInDays($fechaIni);
		
		$mantenimiento = Mantenimiento::create([
			'nombre' => $this->nombre,
			'idAuto' => $this->idAuto,
			'kmActual' => $this->kmActual,
			'idProveedor' => $this->idProveedor,
			'kmProximo' => $this->kmProximo,
			'fechaInicio' => $this->fechaInicio,
			'fechaFin' => $this->fechaFin,
			'diasArreglo' => $this->diasArreglo + 1,
			'estado' => 'En Curso',
			'metodo_pago' => $this->metodo_pago,
			'valor' => $this->valor
		]);
		if($this->nombre=="Cambio De Aceite"){
		$auto = Auto::find($this->idAuto);
		$auto->kilometros = $this->kmActual;
		$auto->cambioAceite = $this->kmProximo;
		$auto->save();
		}
		
		$auto = Auto::find($this->idAuto);
		$auto->estado = 'En Mantenimiento';
		$auto->save();
		
		$customFileName;
		if($this->image)
		{
			$customFileName = uniqid() . '_.' . $this->image->extension();
			$this->image->storeAs('public/mantenimientos', $customFileName);
			$mantenimiento->image = $customFileName;
			$mantenimiento->save();
		}
		$tipo = 0;
		if($this->metodo_pago=="Cta Cte")
		{
		$ctaCte = ProveedoresCtaCte::create([
			'id_proveedor' => $this->idProveedor,
			'tipo' => 1,
			'descripcion' => "Egreso De Dinero Por ". $this->nombre,
			'id_mantenimiento' => $mantenimiento->id,
			'importe' => $this->valor,
			'metodo_pago' => $this->metodo_pago
			]);
		}
		if($this->metodo_pago!="Cta Cte")
		{
		$caja = Caja::create([
			'id_usuario' => Auth()->user()->id,
			'idAuto' => $this->idAuto,
			'tipo' => $tipo,
			'entrada' => 0,
			'salida' => $this->valor,
			'metodo_pago' => $this->metodo_pago,
			'descripcion' => "Egreso De Dinero Por ". $this->nombre,
			'numero_venta' => 0,
			]);
		}
		$this->resetUI();
		$this->emit('mantenimieto-added','Mantenimiento Agregado!!');

	}

	public function Update()
	{
		$rules =[
			'nombre' => 'required',
			'idAuto' => 'required',
			'kmActual' => 'required',
			'idProveedor' => 'required',
			'kmProximo' => 'required',
			'fechaInicio' => 'required',
			'fechaFin' => 'required',
			//'estado' => 'required',
			'valor' => 'required'
		];

		$messages = [
			'nombre.required' => 'El Nombre Es Requerido',
			'idAuto.required' => 'La Patente Es Requerida',
			'kmActual.required' => 'El Kilometraje Actual Es Requerido',
			'kmProximo.required' => 'El Kilometraje Del Proximo Mantenimiento Es Requerido',
			'idProveedor.required' => 'El Proveedor Es Requerido',
			'fechaInicio.required' => 'La Fecha De Inicio Es Requerida',
			'fechaFin.required' => 'La Fecha De Finalizacion Es Requerida',
			'valor.required' => 'El Monto Es Requerido'
		];

		$this->validate($rules, $messages);
		
		$fechaFin = Carbon::parse($this->fechaFin);
		$fechaIni = Carbon::parse($this->fechaInicio);
		$this->diasArreglo = $fechaFin ->diffInDays($fechaIni);
		
		$mantenimiento = Mantenimiento::find($this->selected_id);
		$mantenimiento->update([
			'nombre' => $this->nombre,
			'idAuto' => $this->idAuto,
			'kmActual' => $this->kmActual,
			'idProveedor' => $this->idProveedor,
			'kmProximo' => $this->kmProximo,
			'fechaInicio' => $this->fechaInicio,
			'fechaFin' => $this->fechaFin,
			'diasArreglo' => $this->diasArreglo + 1,
			'valor' => $this->valor
		]);

		if($this->image)
		{
			$customFileName = uniqid() . '_.' . $this->image->extension();
			$this->image->storeAs('public/mantenimientos', $customFileName);
			$imageName = $mantenimiento->image;

			$mantenimiento->image = $customFileName;
			$mantenimiento->save();

			if($imageName !=null)
			{
				if(file_exists('storage/mantenimientos' . $imageName))
				{
					unlink('storage/mantenimientos' . $imageName);
				}
			}

		}

		$this->resetUI();
		$this->emit('mantenimieto-updated', 'Mantenimiento Actualizado');

	}

	public function resetUI() 
	{
		$this->nombre ='';
		$this->idAuto ='';
		$this->valor ='';
		$this->kmActual ='';
		$this->kmProximo ='';
		$this->fechaInicio ='';
		$this->fechaFin ='';
		$this->estado ='';
		$this->idProveedor ='';
		$this->image = null;
		$this->search ='';
		$this->selected_id =0;
	}	
	
	protected $listeners =['deletedRow' => 'Destroy'];
	
	public function Destroy(Mantenimiento $mantenimiento)
	{   	
		$imageName = $mantenimiento->image; 
		$mantenimiento->delete();

		if($imageName !=null) {
			unlink('storage/mantenimientos/' . $imageName);
		}

		$this->resetUI();
		$this->emit('mantenimieto-deleted', 'Mantenimiento Eliminado');
	}
	
	public function cargarValores(){
		$auto = Auto::find($this->idAuto);
		$this->kmActual = $auto->kilometros;
		
		if($this->nombre=="Elegir"){
			$this->kmActual = '';
		}
		
	}
	
	public function Habilitar($id)
	{
		$mantenimiento = Mantenimiento::find($id, ['id','nombre','idProveedor','kmActual','kmProximo','idAuto','valor','image']);
		
		$auto = Auto::find($mantenimiento->idAuto);
		$auto->estado = 'Disponible';
		$auto->save();
		
		$mantenimiento = Mantenimiento::find($mantenimiento->id);
		$mantenimiento->estado = 'Finalizada';
		$mantenimiento->save();

		$this->emit('mantenimieto-fin', 'Mantenimiento Finalizado!');
	}

}

