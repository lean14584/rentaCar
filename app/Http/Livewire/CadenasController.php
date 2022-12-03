<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Auto;
use App\Models\Cadena;

class CadenasController extends Component
{
	use WithFileUploads;
	use WithPagination;

	public $nombre, $idAuto, $estado, $fechaInicio, $valor, $fechaFin, $search, $image, $selected_id, $pageTitle, $componentName;
	private $pagination = 10;
	
	protected $paginationTheme = 'bootstrap';
	
	public function mount()	
	{
		$this->pageTitle = 'Listado';
		$this->componentName = 'Cadenas';
	}
	
    public function render()
    {
        if(strlen($this->search) > 0)
			$data = Cadena::Marca::where('nombre', 'like', '%' . $this->search . '%')->paginate($this->pagination);
		else
			$data = Cadena::orderBy('id','desc')->paginate($this->pagination);
		

		return view('livewire.cadenas.component', ['cadenas' => $data,
		'autos' => Auto::orderBy('patente', 'asc')->get()])
		->extends('layouts.theme.app')
		->section('content');

    }
	
	public function Edit($id)
	{
		$record = Cadena::find($id);
		$this->nombre = $record->nombre;
		//$this->idAuto = $record->idAuto;
		//$this->fechaInicio = $record->fechaInicio;
		//$this->fechaFin = $record->fechaFin;
		$this->valor = $record->valor;
		$this->selected_id = $record->id;
		$this->estado = $record->estado;
		$this->image = null;

		$this->emit('show-modal', 'show modal!');
	}

	public function Store()
	{
		$rules = [
			'nombre' => 'required',
			'valor' => 'required',
			'estado' => 'required'
		];

		$messages = [
			'nombre.required' => 'El Nombre Es Requerido',
			'valor.required' => 'El Valor Es Requerido',
			'estado.required' => 'El Metodo De Pago Es Requerido'
		];

		$this->validate($rules, $messages);

		$cadena = Cadena::create([
			'nombre' => $this->nombre,
			'valor' => $this->valor,
			'estado' => $this->estado			
		]);

		$customFileName;
		if($this->image)
		{
			
			$customFileName = uniqid() . '_.' . $this->image->extension();
			$this->image->storeAs('public/cadenas', $customFileName);
			$cadena->image = $customFileName;
			$cadena->save();
		}

		$this->resetUI();
		$this->emit('cadena-added','Cadena Cargada');

	}

	public function Update()
	{
		$rules = [
			'nombre' => 'required',
			'valor' => 'required',
			'estado' => 'required'
		];

		$messages = [
			'nombre.required' => 'El Nombre Es Requerido',
			'valor.required' => 'El Valor Es Requerido',
			'estado.required' => 'El Metodo De Pago Es Requerido'
		];
		
		$this->validate($rules, $messages);

		$cadena = Cadena::find($this->selected_id);
		$cadena->update([
			'nombre' => $this->nombre,
			'valor' => $this->valor,
			'estado' => $this->estado		
		]);

		if($this->image)
		{
			$customFileName = uniqid() . '_.' . $this->image->extension();
			$this->image->storeAs('public/cadenas', $customFileName);
			$imageName = $cadena->image;

			$cadena->image = $customFileName;
			$cadena->save();

			if($imageName !=null)
			{
				if(file_exists('storage/cadenas' . $imageName))
				{
					unlink('storage/cadenas' . $imageName);
				}
			}

		}

		$this->resetUI();
		$this->emit('cadena-updated', 'Cadena Actualizada');

	}

	public function resetUI() 
	{
		$this->nombre ='';
		$this->idAuto ='';
		$this->fechaInicio='';
		$this->fechaFin='';
		$this->valor='';
		$this->estado='';
		$this->image = null;
		$this->search ='';
		$this->selected_id =0;
	}	
	
	protected $listeners =['deletedRow' => 'Destroy'];
	
	public function Destroy(Cadena $cadena)
	{   	
		$imageName = $cadena->image; 
		$cadena->delete();

		if($imageName !=null) {
			unlink('storage/cadenas/' . $imageName);
		}

		$this->resetUI();
		$this->emit('cadena-deleted', 'Cadena Eliminada');
	}
}