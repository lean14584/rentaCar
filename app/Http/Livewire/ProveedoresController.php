<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUpLoads;
use Livewire\WithPagination;
use App\Models\Proveedor;
use App\Models\Producto;

class ProveedoresController extends Component
{
	use WithFileUploads;
	use WithPagination;
	
	public $nombre,$search,$inicio,$selected_id,$pageTitle,$componentName,$image,$cuit,$brutos,$localidad,$telefono,$direccion,$correo ,$productosProveedor;
	private $pagination = 5;
	
	protected $paginationTheme = 'bootstrap';
	
	public function mount()
	{
		$this->pageTitle = 'Listado';
		$this->componentName = 'Proveedores';
		$this->productosProveedor = [];
	}
	
    public function render()
    {
		if(strlen($this->search) > 0)
		$data = Proveedor::where('nombre', 'like', '%'. $this->search . '%')->paginate($this->pagination);
		else
		$data = Proveedor::orderBy('id','desc')->paginate($this->pagination);
		
        return view('livewire.proveedores.component', ['data' => $data])
		->extends('layouts.theme.app')
		->section('content');
    }
	
	public function Edit(Proveedor $proveedor)
	{
			$this->selected_id = $proveedor->id;
			$this->nombre = $proveedor->nombre;
			$this->inicio = $proveedor->inicio;
			$this->cuit = $proveedor->cuit;
			$this->brutos = $proveedor->brutos;
			$this->localidad = $proveedor->localidad;
			$this->telefono = $proveedor->telefono;
			$this->direccion = $proveedor->direccion;
			$this->correo = $proveedor->correo;
			
			$this->emit('show-modal', 'show modal!');
	}
	
	public function Store()
	{
		$rules = [
			'nombre' => 'required',
			'inicio' => 'required',
			'cuit' => 'required',
			'brutos' => 'required',
			'localidad' => 'required',
			'telefono' => 'required',
			'direccion' => 'required',
			'correo' => 'required'
		];
		
		$messages = [
			'name.require' => 'Nombre Requerido',
			'inicio.require' => 'Inicio De Actividades Requerido',
			'cuit.require' => 'El Cuit Es Requerido',
			'brutos.require' => 'Ingresos Brutos Es Requerido',
			'localidad.require' => 'La Localidad Es Requerida',
			'telefono.require' => 'El Telefono Es Requerido',
			'direccion.require' => 'La Direccion Es Requerida',
			'correo.require' => 'El Correo Es Requerido'
			
		];
		$this->validate($rules, $messages);
		
		$proveedor = Proveedor::create([
			'nombre' => $this->nombre,
			'inicio' => $this->inicio,
			'cuit' => $this->cuit,
			'brutos' => $this->brutos,
			'localidad' => $this->localidad,
			'telefono' => $this->telefono,
			'direccion' => $this->direccion,
			'correo' => $this->correo
		]);
		
		if($this->image) 
		{
			$customFileName = uniqid() . '_.' . $this->image->extension();
			$this->image->storeAs('public/proveedores', $customFileName);
			$proveedor->image = $customFileName;
			$proveedor->save();
		}
		
		$this->resetUI();
		$this->emit('proveedor-added','Proveedor Agregado!!');
	}
	
	public function Update()
	{
			$rules = [
				'nombre' => "required",
				'inicio' => 'required',
				'cuit' => 'required',
				'brutos' => 'required',
				'localidad' => 'required',
				'telefono' => 'required',
				'direccion' => 'required',
				'correo' => 'required'
			];
			$messages = [
				'nombre.required' => 'EL Nombre Del Proveedor Es Requerido',
				'inicio.required' => 'El Inicio De Actividades Del Proveedor Es Requerido',
				'cuit.required' => 'El Cuit Del Proveedor Es Requerido',
				'brutos.required' => 'Ingresos Brutos Del Proveedor Es Requerido',
				'localidad.required' => ' La  Localidad Del Proveedor Es Requerida',
				'telefono.required' => 'El Telefono Del Proveedor Es Requerido',
				'direccion.required' => 'La Direccion Del Proveedor Es Requerida',
				'correo.required' => 'El Correo Del Proveedor Es Requerido'		
				
			];
			
			$this->validate($rules, $messages);
			
			$proveedor = Proveedor::find($this->selected_id);
			$proveedor->update([
				'nombre' => $this->nombre,
				'inicio' => $this->inicio,
				'cuit' => $this->cuit,
				'brutos' => $this->brutos,
				'localidad' => $this->localidad,
				'telefono' => $this->telefono,
				'direccion' => $this->direccion,
				'correo' => $this->correo
			]);
			
			if($this->image) 
			{
				$customFileName = uniqid() . '_.' . $this->image->extension();
				$this->image->storeAs('public/proveedores', $customFileName);
				$imageTemp = $proveedor->image; // imagen temporal
				$proveedor->image = $customFileName;
				$proveedor->save();

				if($imageTemp !=null) 
				{
					if(file_exists('storage/proveedores/' . $imageTemp )) {
						unlink('storage/proveedores/' . $imageTemp);
					}
				}
			}
				
			$this->resetUI();
			$this->emit('proveedor-updated', 'Proveedor Actualizado');
	}
	
	public function resetUI()
	{
		$this->nombre = '';
		$this->inicio = '';
		$this->cuit = '';
		$this->brutos = '';
		$this->localidad = '';
		$this->telefono = '';
		$this->direccion = '';
		$this->correo = '';
		$this->search = '';
		$this->selected_id = 0;
		$this->resetValidation();
	}
	
	protected $listeners =['deleteRow' => 'Destroy'];
	
	public function Destroy(Proveedor $proveedor)
	{
		$proveedor->delete();
			
		$this->resetUI();
		$this->emit('proveedor-deleted', 'Proveedor Eliminado');
		
	}
	
}
