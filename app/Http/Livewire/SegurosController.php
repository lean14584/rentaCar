<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Auto;
use App\Models\Seguro;

class SegurosController extends Component
{
	use WithFileUploads;
	use WithPagination;

	public $empresa, $idAuto, $valor, $vigencia, $search, $image, $selected_id, $pageTitle, $componentName;
	private $pagination = 10;
	
	protected $paginationTheme = 'bootstrap';
	
	public function mount()	
	{
		$this->pageTitle = 'Listado';
		$this->componentName = 'Seguros';
	}
	
    public function render()
    {
        if(strlen($this->search) > 0)
			$data = Seguro::join('autos as a','a.id','seguros.idAuto')
			->select('seguros.*','a.patente as patente')
			->where('empresa', 'like', '%' . $this->search . '%')->paginate($this->pagination);
		else
			$data = Seguro::join('autos as a','a.id','seguros.idAuto')
			->select('seguros.*','a.patente as patente')
			->paginate($this->pagination);
		

		return view('livewire.seguros.component', ['seguros' => $data,
		'autos' => Auto::orderBy('patente', 'asc')->get()])
		->extends('layouts.theme.app')
		->section('content');

    }
	
	public function Edit($id)
	{
		$record = Seguro::find($id);
		$this->empresa = $record->empresa;
		$this->idAuto = $record->idAuto;
		$this->valor = $record->valor;
		$this->vigencia = $record->vigencia;
		$this->selected_id = $record->id;
		$this->image = null;

		$this->emit('show-modal', 'show modal!');
	}

	public function Store()
	{
		$rules = [
			'empresa' => 'required',
			'idAuto' => 'required',
			'valor' => 'required',
			'vigencia' => 'required'
		];

		$messages = [
			'empresa.required' => 'La Empresa Es Requerida',
			'idAuto.required' => 'El Auto Es Requerido',
			'valor.required' => 'El Valor Es Requerido',
			'vigencia.required' => 'La Vigencia Es Requerida'
		];

		$this->validate($rules, $messages);

		$seguro = Seguro::create([
			'empresa' => $this->empresa,
			'idAuto' => $this->idAuto,
			'valor' => $this->valor,
			'vigencia' => $this->vigencia	
		]);

		$customFileName;
		if($this->image)
		{
			
			$customFileName = uniqid() . '_.' . $this->image->extension();
			$this->image->storeAs('public/seguros', $customFileName);
			$seguro->image = $customFileName;
			$seguro->save();
		}

		$this->resetUI();
		$this->emit('seguro-added','Seguro Cargado');

	}

	public function Update()
	{
		$rules = [
			'empresa' => 'required',
			'idAuto' => 'required',
			'valor' => 'required',
			'vigencia' => 'required'
		];

		$messages = [
			'empresa.required' => 'La Empresa Es Requerida',
			'idAuto.required' => 'El Auto Es Requerido',
			'valor.required' => 'El Valor Es Requerido',
			'vigencia.required' => 'La Vigencia Es Requerida'
		];
		
		$this->validate($rules, $messages);

		$seguro = Seguro::find($this->selected_id);
		$seguro->update([
			'empresa' => $this->empresa,
			'idAuto' => $this->idAuto,
			'valor' => $this->valor,
			'vigencia' => $this->vigencia
		]);

		if($this->image)
		{
			$customFileName = uniqid() . '_.' . $this->image->extension();
			$this->image->storeAs('public/seguros', $customFileName);
			$imageName = $seguro->image;

			$seguro->image = $customFileName;
			$seguro->save();

			if($imageName !=null)
			{
				if(file_exists('storage/seguros' . $imageName))
				{
					unlink('storage/seguros' . $imageName);
				}
			}

		}

		$this->resetUI();
		$this->emit('seguro-updated', 'Seguro Actualizado');

	}

	public function resetUI() 
	{
		$this->empresa ='';
		$this->idAuto ='';
		$this->valor ='';
		$this->vigencia='';
		$this->image = null;
		$this->search ='';
		$this->selected_id =0;
	}	
	
	protected $listeners =['deletedRow' => 'Destroy'];
	
	public function Destroy(Seguro $seguro)
	{   	
		$imageName = $seguro->image; 
		$seguro->delete();

		if($imageName !=null) {
			unlink('storage/seguros/' . $imageName);
		}

		$this->resetUI();
		$this->emit('seguro-deleted', 'Seguro Eliminado');
	}
}