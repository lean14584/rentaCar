<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Auto;
use App\Models\Poliza;

class PolizasController extends Component
{
	use WithFileUploads;
	use WithPagination;

	public $empresa, $idAuto, $vigencia, $search, $image, $selected_id, $pageTitle, $componentName;
	private $pagination = 10;
	
	protected $paginationTheme = 'bootstrap';
	
	public function mount()	
	{
		$this->pageTitle = 'Listado';
		$this->componentName = 'Polizas';
	}
	
    public function render()
    {
        if(strlen($this->search) > 0)
			$data = Poliza::join('autos as a','a.id','polizas.idAuto')
			->select('polizas.*','a.patente as patente')
			->where('empresa', 'like', '%' . $this->search . '%')->paginate($this->pagination);
		else
			$data = Poliza::join('autos as a','a.id','polizas.idAuto')
			->select('polizas.*','a.patente as patente')
			->paginate($this->pagination);
		

		return view('livewire.polizas.component', ['polizas' => $data,
		'autos' => Auto::orderBy('patente', 'asc')->get()])
		->extends('layouts.theme.app')
		->section('content');

    }
	
	public function Edit($id)
	{
		$record = Poliza::find($id);
		$this->empresa = $record->empresa;
		$this->idAuto = $record->idAuto;
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
			'vigencia' => 'required'
		];

		$messages = [
			'empresa.required' => 'La Empresa Es Requerida',
			'idAuto.required' => 'El Auto Es Requerido',
			'vigencia.required' => 'La Vigencia Es Requerida'
		];

		$this->validate($rules, $messages);

		$poliza = Poliza::create([
			'empresa' => $this->empresa,
			'idAuto' => $this->idAuto,
			'vigencia' => $this->vigencia	
		]);

		$customFileName;
		if($this->image)
		{
			
			$customFileName = uniqid() . '_.' . $this->image->extension();
			$this->image->storeAs('public/polizas', $customFileName);
			$poliza->image = $customFileName;
			$poliza->save();
		}

		$this->resetUI();
		$this->emit('poliza-added','Poliza Cargada');

	}

	public function Update()
	{
		$rules = [
			'empresa' => 'required',
			'idAuto' => 'required',
			'vigencia' => 'required'
		];

		$messages = [
			'empresa.required' => 'La Empresa Es Requerida',
			'idAuto.required' => 'El Auto Es Requerido',
			'vigencia.required' => 'La Vigencia Es Requerida'
		];
		
		$this->validate($rules, $messages);

		$poliza = Poliza::find($this->selected_id);
		$poliza->update([
			'empresa' => $this->empresa,
			'idAuto' => $this->idAuto,
			'vigencia' => $this->vigencia
		]);

		if($this->image)
		{
			$customFileName = uniqid() . '_.' . $this->image->extension();
			$this->image->storeAs('public/polizas', $customFileName);
			$imageName = $poliza->image;

			$poliza->image = $customFileName;
			$poliza->save();

			if($imageName !=null)
			{
				if(file_exists('storage/polizas' . $imageName))
				{
					unlink('storage/polizas' . $imageName);
				}
			}

		}

		$this->resetUI();
		$this->emit('poliza-updated', 'Poliza Actualizada');

	}

	public function resetUI() 
	{
		$this->empresa ='';
		$this->idAuto ='';
		$this->vigencia='';
		$this->image = null;
		$this->search ='';
		$this->selected_id =0;
	}	
	
	protected $listeners =['deletedRow' => 'Destroy'];
	
	public function Destroy(Poliza $poliza)
	{   	
		$imageName = $poliza->image; 
		$poliza->delete();

		if($imageName !=null) {
			unlink('storage/polizas/' . $imageName);
		}

		$this->resetUI();
		$this->emit('poliza-deleted', 'Poliza Eliminada');
	}
}