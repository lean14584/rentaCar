<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Auto;
use App\Models\Matafuego;

class MatafuegosController extends Component
{
    use WithFileUploads;
	use WithPagination;

	public $nombre, $idAuto, $estado, $fechaInicio, $valor, $fechaFin, $search, $image, $selected_id, $pageTitle, $componentName;
	private $pagination = 10;
	
	protected $paginationTheme = 'bootstrap';
	
	public function mount()	
	{
		$this->pageTitle = 'Listado';
		$this->componentName = 'Matafuegos';
	}
	
    public function render()
    {
        if(strlen($this->search) > 0)
			$data = Matafuego::Marca::where('nombre', 'like', '%' . $this->search . '%')->paginate($this->pagination);
		else
			$data = Matafuego::orderBy('id','desc')->paginate($this->pagination);
		

		return view('livewire.matafuegos.component', ['matafuegos' => $data,
		'autos' => Auto::orderBy('patente', 'asc')->get()])
		->extends('layouts.theme.app')
		->section('content');

    }
	
	public function Edit($id)
	{
		$record = Matafuego::find($id);
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
			'estado.required' => 'El Estado Es Requerido'
		];

		$this->validate($rules, $messages);

		$matafuego = Matafuego::create([
			'nombre' => $this->nombre,
			'valor' => $this->valor,
			'estado' => $this->estado			
		]);

		$customFileName;
		if($this->image)
		{
			
			$customFileName = uniqid() . '_.' . $this->image->extension();
			$this->image->storeAs('public/matafuegos', $customFileName);
			$matafuego->image = $customFileName;
			$matafuego->save();
		}

		$this->resetUI();
		$this->emit('matafuego-added','Matafuego Cargado');

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
			'estado.required' => 'El Estado Es Requerido'
		];
		
		$this->validate($rules, $messages);

		$matafuego = Matafuego::find($this->selected_id);
		$matafuego->update([
			'nombre' => $this->nombre,
			'valor' => $this->valor,
			'estado' => $this->estado		
		]);

		if($this->image)
		{
			$customFileName = uniqid() . '_.' . $this->image->extension();
			$this->image->storeAs('public/matafuegos', $customFileName);
			$imageName = $cadena->image;

			$matafuego->image = $customFileName;
			$matafuego->save();

			if($imageName !=null)
			{
				if(file_exists('storage/matafuegos' . $imageName))
				{
					unlink('storage/matafuegos' . $imageName);
				}
			}

		}

		$this->resetUI();
		$this->emit('matafuego-updated', 'Matafuego Actualizado');

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
	
	public function Destroy(Matafuego $matafuego)
	{   	
		$imageName = $matafuego->image; 
		$matafuego->delete();

		if($imageName !=null) {
			unlink('storage/cadenas/' . $imageName);
		}

		$this->resetUI();
		$this->emit('matafuego-deleted', 'Matafuego Eliminado');
	}
}