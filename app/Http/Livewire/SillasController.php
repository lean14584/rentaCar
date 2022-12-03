<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Auto;
use App\Models\Silla;

class SillasController extends Component
{
    use WithFileUploads;
	use WithPagination;

	public $nombre, $idAuto, $estado, $fechaInicio, $valor, $fechaFin, $search, $image, $selected_id, $pageTitle, $componentName;
	private $pagination = 10;
	
	protected $paginationTheme = 'bootstrap';
	
	public function mount()	
	{
		$this->pageTitle = 'Listado';
		$this->componentName = 'Sillas De Bebes';
	}
	
    public function render()
    {
        if(strlen($this->search) > 0)
			$data = Silla::Marca::where('nombre', 'like', '%' . $this->search . '%')->paginate($this->pagination);
		else
			$data = Silla::orderBy('id','desc')->paginate($this->pagination);
		

		return view('livewire.sillas.component', ['sillas' => $data,
		'autos' => Auto::orderBy('patente', 'asc')->get()])
		->extends('layouts.theme.app')
		->section('content');

    }
	
	public function Edit($id)
	{
		$record = Silla::find($id);
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

		$silla = Silla::create([
			'nombre' => $this->nombre,
			'valor' => $this->valor,
			'estado' => $this->estado			
		]);

		$customFileName;
		if($this->image)
		{
			
			$customFileName = uniqid() . '_.' . $this->image->extension();
			$this->image->storeAs('public/sillas', $customFileName);
			$silla->image = $customFileName;
			$silla->save();
		}

		$this->resetUI();
		$this->emit('silla-added','Silla De Bebe Cargada');

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

		$silla = Silla::find($this->selected_id);
		$silla->update([
			'nombre' => $this->nombre,
			'valor' => $this->valor,
			'estado' => $this->estado		
		]);

		if($this->image)
		{
			$customFileName = uniqid() . '_.' . $this->image->extension();
			$this->image->storeAs('public/sillas', $customFileName);
			$imageName = $cadena->image;

			$silla->image = $customFileName;
			$silla->save();

			if($imageName !=null)
			{
				if(file_exists('storage/sillas' . $imageName))
				{
					unlink('storage/sillas' . $imageName);
				}
			}

		}

		$this->resetUI();
		$this->emit('silla-updated', 'Silla De Bebe Actualizada');

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
	
	public function Destroy(Silla $silla)
	{   	
		$imageName = $silla->image; 
		$silla->delete();

		if($imageName !=null) {
			unlink('storage/cadenas/' . $imageName);
		}

		$this->resetUI();
		$this->emit('silla-deleted', 'Silla De Bebe Eliminada');
	}
}