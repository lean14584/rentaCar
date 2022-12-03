<?php

namespace App\Http\Livewire;

use App\Models\Marca;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class MarcasController extends Component
{

	use WithFileUploads;
	use WithPagination;

	public $nombre, $search, $image, $selected_id, $pageTitle, $componentName;
	private $pagination = 10;
	
	protected $paginationTheme = 'bootstrap';
	
	public function mount()
	{
		$this->pageTitle = 'Listado';
		$this->componentName = 'Marcas';
	}

	public function render()
	{
		if(strlen($this->search) > 0)
			$data = Marca::where('nombre', 'like', '%' . $this->search . '%')->paginate($this->pagination);
		else
			$data = Marca::orderBy('id','desc')->paginate($this->pagination);

		return view('livewire.marcas.component', ['marcas' => $data])
		->extends('layouts.theme.app')
		->section('content');
	}

	public function Edit($id)
	{
		$record = Marca::find($id, ['id','nombre','image']);
		$this->nombre = $record->nombre;
		$this->selected_id = $record->id;
		$this->image = null;

		$this->emit('show-modal', 'show modal!');
	}

	public function Store()
	{
		$rules = [
			'nombre' => 'required|unique:marcas|min:3'
		];

		$messages = [
			'nombre.required' => 'El Nombre De La Marca Es Requerido',
			'nombre.unique' => 'Ya Existe El Nombre De La Marca',
			'nombre.min' => 'El Nombre De La Marca Debe Tener Al Menos 3 Caracteres'
		];

		$this->validate($rules, $messages);

		$marca = Marca::create([
			'nombre' => $this->nombre
		]);

		$customFileName;
		if($this->image)
		{
			$customFileName = uniqid() . '_.' . $this->image->extension();
			$this->image->storeAs('public/marcas', $customFileName);
			$marca->image = $customFileName;
			$marca->save();
		}

		$this->resetUI();
		$this->emit('marca-added','Marcas Registrada');

	}

	public function Update()
	{
		$rules =[
			'nombre' => "required|min:3|unique:marcas,nombre,{$this->selected_id}"
		];

		$messages =[
			'nombre.required' => 'El Nombre De La Marca Es Requerido',
			'nombre.unique' => 'Ya Existe El Nombre De La Marca',
			'nombre.min' => 'El Nombre De La Marca Debe Tener Al Menos 3 Caracteres'
		];

		$this->validate($rules, $messages);

		$marca = Marca::find($this->selected_id);
		$marca->update([
			'nombre' => $this->nombre
		]);

		if($this->image)
		{
			$customFileName = uniqid() . '_.' . $this->image->extension();
			$this->image->storeAs('public/marcas', $customFileName);
			$imageName = $marca->image;

			$marca->image = $customFileName;
			$marca->save();

			if($imageName !=null)
			{
				if(file_exists('storage/marcas' . $imageName))
				{
					unlink('storage/marcas' . $imageName);
				}
			}

		}

		$this->resetUI();
		$this->emit('marca-updated', 'Marca Actualizada');

	}

	public function resetUI() 
	{
		$this->nombre ='';
		$this->image = null;
		$this->search ='';
		$this->selected_id =0;
	}	
	
	protected $listeners =['deletedRow' => 'Destroy'];
	
	public function Destroy(Marca $marca)
	{   	
		$imageName = $marca->image; 
		$marca->delete();

		if($imageName !=null) {
			unlink('storage/marcas/' . $imageName);
		}

		$this->resetUI();
		$this->emit('marca-deleted', 'Marca Eliminada');
	}
}

