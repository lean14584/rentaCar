<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Auto;
use App\Models\Rto;
use App\Models\Caja;

class RtoController extends Component
{
	use WithFileUploads;
	use WithPagination;

	public $empresa, $idAuto, $fechaInicio, $metodo_pago, $valor, $fechaFin, $search, $image, $selected_id, $pageTitle, $componentName;
	private $pagination = 10;
	
	protected $paginationTheme = 'bootstrap';
	
	public function mount()	
	{
		$this->pageTitle = 'Listado';
		$this->componentName = 'Rto';
	}
	
    public function render()
    {
        if(strlen($this->search) > 0)
			$data = Rto::join('autos as a','a.id','rtos.idAuto')
			->select('rtos.*','a.patente as patente')
			->where('empresa', 'like', '%' . $this->search . '%')->paginate($this->pagination);
		else
			$data = Rto::join('autos as a','a.id','rtos.idAuto')
			->select('rtos.*','a.patente as patente')
			->paginate($this->pagination);
		

		return view('livewire.rtos.component', ['rtos' => $data,
		'autos' => Auto::orderBy('patente', 'asc')->get()])
		->extends('layouts.theme.app')
		->section('content');

    }
	
	public function Edit($id)
	{
		$record = Rto::find($id);
		$this->empresa = $record->empresa;
		$this->idAuto = $record->idAuto;
		$this->fechaInicio = $record->vigencia;
		$this->fechaFin = $record->fechaFin;
		$this->valor = $record->valor;
		$this->selected_id = $record->id;
		$this->metodo_pago = $record->metodo_pago;
		$this->image = null;

		$this->emit('show-modal', 'show modal!');
	}

	public function Store()
	{
		$rules = [
			'empresa' => 'required',
			'idAuto' => 'required',
			'fechaInicio' => 'required',
			'fechaFin' => 'required',
			'valor' => 'required',
			'metodo_pago' => 'required'
		];

		$messages = [
			'empresa.required' => 'La Empresa Es Requerida',
			'idAuto.required' => 'El Auto Es Requerido',
			'fechaInicio.required' => 'La Fecha De Realizacion Es Requerida',
			'fechaFin.required' => 'La Fecha De Vigencia Es Requerida',
			'valor.required' => 'El Valor Es Requerido',
			'metodo_pago.required' => 'El Metodo De Pago Es Requerido'
		];

		$this->validate($rules, $messages);

		$rto = Rto::create([
			'empresa' => $this->empresa,
			'idAuto' => $this->idAuto,
			'fechaInicio' => $this->fechaInicio,
			'fechaFin' => $this->fechaFin,
			'valor' => $this->valor,
			'metodo_pago' => $this->metodo_pago,			
		]);

		$customFileName;
		if($this->image)
		{
			
			$customFileName = uniqid() . '_.' . $this->image->extension();
			$this->image->storeAs('public/rtos', $customFileName);
			$rto->image = $customFileName;
			$rto->save();
		}
		
		$caja = Caja::create([
			'id_usuario' => Auth()->user()->id,
			'idAuto' => $this->idAuto,
			'tipo' => 1,
			'entrada' => 0,
			'salida' => $this->valor,
			'metodo_pago' => $this->metodo_pago,
			'descripcion' => "Egreso De Dinero Por RTO en ". $this->empresa,
			'numero_venta' => 0,
			]);

		$this->resetUI();
		$this->emit('rto-added','Rto Cargada');

	}

	public function Update()
	{
		$rules = [
			'empresa' => 'required',
			'idAuto' => 'required',
			'fechaInicio' => 'required',
			'fechaFin' => 'required',
			'valor' => 'required',
			'metodo_pago' => 'required'
		];

		$messages = [
			'empresa.required' => 'La Empresa Es Requerida',
			'idAuto.required' => 'El Auto Es Requerido',
			'fechaInicio.required' => 'La Fecha De Realizacion Es Requerida',
			'fechaFin.required' => 'La Fecha De Vigencia Es Requerida',
			'valor.required' => 'El Valor Es Requerido',
			'metodo_pago.required' => 'El Metodo De Pago Es Requerido'
		];
		
		$this->validate($rules, $messages);

		$rto = Rto::find($this->selected_id);
		$rto->update([
			'empresa' => $this->empresa,
			'idAuto' => $this->idAuto,
			'fechaInicio' => $this->fechaInicio,
			'fechaFin' => $this->fechaFin,
			'valor' => $this->valor,
			'metodo_pago' => $this->metodo_pago		
		]);

		if($this->image)
		{
			$customFileName = uniqid() . '_.' . $this->image->extension();
			$this->image->storeAs('public/rtos', $customFileName);
			$imageName = $rto->image;

			$rto->image = $customFileName;
			$rto->save();

			if($imageName !=null)
			{
				if(file_exists('storage/rtos' . $imageName))
				{
					unlink('storage/rtos' . $imageName);
				}
			}

		}

		$this->resetUI();
		$this->emit('rto-updated', 'Rto Actualizada');

	}

	public function resetUI() 
	{
		$this->empresa ='';
		$this->idAuto ='';
		$this->fechaInicio='';
		$this->fechaFin='';
		$this->valor='';
		$this->metodo_pago='';
		$this->image = null;
		$this->search ='';
		$this->selected_id =0;
	}	
	
	protected $listeners =['deletedRow' => 'Destroy'];
	
	public function Destroy(Rto $rto)
	{   	
		$imageName = $rto->image; 
		$rto->delete();

		if($imageName !=null) {
			unlink('storage/rtos/' . $imageName);
		}

		$this->resetUI();
		$this->emit('rto-deleted', 'Rto Eliminada');
	}
}