<?php

namespace App\Http\Livewire;

use App\Models\Auto;
use App\Models\Marca;
use App\Models\Poliza;
use App\Models\Rto;
use App\Models\Mantenimiento;
use App\Models\Seguro;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Carbon\Carbon;

class AutosController extends Component
{
	use WithFileUploads;
	use WithPagination;

	public $patente, $patenteDetalle,$datosMantenimientos= [], $datosAutos= [], $combustible, $datosSeguros= [], $titular, $kilometros
	, $cambioAceite, $diasSeguro, $diasPoliza ,$modelo, $marca_id, $tipo, $color, $estado, $importe, $search, $image, $selected_id, $pageTitle, $componentName;
	private $pagination = 10;
	
	protected $paginationTheme = 'bootstrap';
	
	public function mount()
	{
		$this->pageTitle = 'Listado';
		$this->componentName = 'Autos';
	}

	public function render()
	{
		if(strlen($this->search) > 0)
			$data = Auto::join('marcas as m','m.id','autos.marca_id')
			->join('polizas as p','p.idAuto','autos.id')
			->join('seguros as s','s.idAuto','autos.id')
			->select('autos.*','m.nombre as nombreMarca','p.vigencia as vigenciaPoliza','s.vigencia as vigenciaSeguro')
			->where('modelo', 'like', '%' . $this->search . '%')->paginate($this->pagination);
		else
			$data = Auto::join('marcas as m','m.id','autos.marca_id')
			->join('polizas as p','p.idAuto','autos.id')
			->join('seguros as s','s.idAuto','autos.id')
			->select('autos.*','m.nombre as nombreMarca','p.vigencia as vigenciaPoliza','s.vigencia as vigenciaSeguro')
			->paginate($this->pagination);
		

		return view('livewire.autos.component', ['autos' => $data,
		'marcas' => Marca::orderBy('nombre', 'asc')->get()])
		->extends('layouts.theme.app')
		->section('content');
	}

	public function Edit($id)
	{
		$record = Auto::find($id, ['id','patente','titular','cambioAceite','kilometros','combustible','modelo','color','estado','marca_id','importe','image']);
		$this->patente = $record->patente;
		$this->titular = $record->titular;
		$this->kilometros = $record->kilometros;
		$this->cambioAceite = $record->cambioAceite;
		$this->modelo = $record->modelo;
		$this->color = $record->color;
		$this->estado = $record->estado;
		$this->marca_id = $record->marca_id;
		$this->importe = $record->importe;
		$this->combustible = $record->combustible;
		$this->selected_id = $record->id;
		$this->image = null;

		$this->emit('show-modal', 'show modal!');
	}

	public function Store()
	{
		$rules = [
			'patente' => 'required|unique:autos|min:3',
			'titular' => 'required',
			'kilometros' => 'required',
			'cambioAceite' => 'required',
			'modelo' => 'required',
			'color' => 'required',
			'estado' => 'required',
			'marca_id' => 'required',
			'combustible' => 'required',
			'importe' => 'required'

		];

		$messages = [
			'patente.required' => 'La Patente Es Requerida',
			'patente.unique' => 'Ya Existe Esa Patente',
			'patente.min' => 'La Patente Debe Tener Minimo 3 Caracteres',
			'titular.required' => 'El estado Es Requerido',
			'kilometros.required' => 'Los Kilometros Son Requeridos',
			'cambioAceite.required' => 'El kilometraje Del Cambio De Aceite Es Requerido',
			'modelo.required' => 'El Modelo Es Requerido',
			'color.required' => 'El Color Es Requerido',
			'estado.required' => 'El Estado Es Requerido',
			'combustible.required' => 'El Tipo De Combustible Es Requerido',
			'marca_id.required' => 'La Marca Es Requerida',
			'importe.required' => 'El Importe Es Requerido'
		];

		$this->validate($rules, $messages);

		$auto = Auto::create([
			'patente' => $this->patente,
			'titular' => $this->titular,
			'kilometros' => $this->kilometros,
			'cambioAceite' => $this->cambioAceite,
			'color' => $this->color,
			'estado' => $this->estado,
			'combustible' => $this->combustible,
			'importe' => $this->importe,
			'marca_id' => $this->marca_id,
			'modelo' => $this->modelo		
		]);

		$customFileName;
		if($this->image)
		{
			
			$customFileName = uniqid() . '_.' . $this->image->extension();
			$this->image->storeAs('public/autos', $customFileName);
			$auto->image = $customFileName;
			$auto->save();
		}

		$this->resetUI();
		$this->emit('auto-added','Auto Registrado');

	}

	public function Update()
	{
		$rules = [
			'patente' => 'required',
			'titular' => 'required',
			'kilometros' => 'required',
			'cambioAceite' => 'required',
			'modelo' => 'required',
			'color' => 'required',
			'estado' => 'required',
			'combustible' => 'required',
			'marca_id' => 'required',
			'importe' => 'required'
		];

		$messages = [
			'patente.required' => 'La Patente Es Requerida',
			//'patente.unique' => 'Ya Existe Esa Patente',
			//'patente.min' => 'La Patente Debe Tener Minimo 3 Caracteres',
			'titular.required' => 'El estado Es Requerido',
			'kilometros.required' => 'Los Kilometros Son Requeridos',
			'cambioAceite.required' => 'El kilometraje Del Cambio De Aceite Es Requerido',
			'modelo.required' => 'El Modelo Es Requerido',
			'color.required' => 'El Color Es Requerido',
			'estado.required' => 'El Estado Es Requerido',
			'combustible.required' => 'El Tipo De Combustible Es Requerido',
			'marca_id.required' => 'La Marca Es Requerida',
			'importe.required' => 'El Importe Es Requerido'
		];
		
		$this->validate($rules, $messages);

		$auto = Auto::find($this->selected_id);
		$auto->update([
			'patente' => $this->patente,
			'titular' => $this->titular,
			'kilometros' => $this->kilometros,
			'cambioAceite' => $this->cambioAceite,
			'modelo' => $this->modelo,
			'color' => $this->color,
			'estado' => $this->estado,
			'combustible' => $this->combustible,
			'marca_id' => $this->marca_id,
			'importe' => $this->importe
		]);

		if($this->image)
		{
			$customFileName = uniqid() . '_.' . $this->image->extension();
			$this->image->storeAs('public/autos', $customFileName);
			$imageName = $auto->image;

			$auto->image = $customFileName;
			$auto->save();

			if($imageName !=null)
			{
				if(file_exists('storage/autos' . $imageName))
				{
					unlink('storage/autos' . $imageName);
				}
			}

		}

		$this->resetUI();
		$this->emit('auto-updated', 'Auto Actualizado');

	}

	public function resetUI() 
	{
		$this->name ='';
		$this->patente ='';
		$this->titular ='';
		$this->kilometros='';
		$this->cambioAceite='';
		$this->modelo='';
		$this->color='';
		$this->estado='';
		$this->combustible='';
		$this->importe='';
		$this->marca_id='';
		$this->image = null;
		$this->search ='';
		$this->selected_id =0;
	}	
	
	protected $listeners =['deletedRow' => 'Destroy'];
	
	public function Destroy(Auto $auto)
	{   	
		$imageName = $auto->image; 
		$auto->delete();

		if($imageName !=null) {
			unlink('storage/autos/' . $imageName);
		}

		$this->resetUI();
		$this->emit('auto-deleted', 'Auto Eliminado');
	}
	
	public function delalleMantenimientos($id, $patenteDetalle)
	{
		$this->datosMantenimientos = Mantenimiento::join('autos as a','a.id','mantenimientos.idAuto')
			->select('mantenimientos.*','a.patente as patenteAuto')
			->where('mantenimientos.idAuto', $id)->get();
		$this->patenteDetalle = $patenteDetalle;
		$this->emit('modal-detalle-mantenimiento', 'show modal!');
	}
	
	public function detalleAuto($id, $patenteDetalle)
	{
		$this->datosAutos = Auto::join('marcas as m','m.id','autos.marca_id')
			->select('autos.*','m.nombre as nombreMarca')
			->where('autos.id', $id)->get();
		$this->patenteDetalle = $patenteDetalle;
		$this->emit('modal-detalle-auto', 'show modal!');
	}
	
	public function delalleSeguro($id, $patenteDetalle, $vigenciaSeguro, $vigenciaPoliza)
	{
		$this->datosSeguros = Auto::join('polizas as p','p.idAuto','autos.id')
			->join('seguros as s','s.idAuto','autos.id')
			->join('rtos as r','r.idAuto','autos.id')
			->select('autos.*','p.vigencia as vigenciaPoliza','s.vigencia as vigenciaSeguro','r.empresa as empresa','r.fechaFin as fechaFin')
			->where('autos.id', $id)->get();
		$this->patenteDetalle = $patenteDetalle;
		$fechaReciente = Carbon::now();
		$fechaSeguro = Carbon::parse($vigenciaSeguro); 
		$fechaPoliza = Carbon::parse($vigenciaPoliza); 		
		$this->diasSeguro = $fechaSeguro ->diffInDays($fechaReciente);
		$this->diasPoliza = $fechaPoliza->diffInDays($fechaReciente);
		$this->emit('modal-detalle-seguro', 'show modal!');
	}
}