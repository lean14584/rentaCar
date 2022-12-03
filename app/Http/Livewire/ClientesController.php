<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUpLoads;
use Livewire\WithPagination;
use App\Models\Cliente;

class ClientesController extends Component
{
	use WithFileUploads;
	use WithPagination;
	
	public $nombre,$search,$tipo_documento,$selected_id,$pageTitle,$image,$componentName
	,$documento, $ciudad,$telefono,$vencimiento_licencia,$licencia, $provincia,$direccion,$correo;
	private $pagination = 5;
	
	protected $paginationTheme = 'bootstrap';
	
	public function mount()
	{
		$this->pageTitle = 'Listado';
		$this->componentName = 'Clientes';
	}
	
	
    public function render()
    {
		if(strlen($this->search) > 0)
		$data = Cliente::where('nombre', 'like', '%'. $this->search . '%')
		->where('id', '<>', 1)
		->paginate($this->pagination);
		else
		$data = Cliente::orderBy('id','desc')
		->where('id', '<>', 1)
		->paginate($this->pagination);
		
        return view('livewire.clientes.component', ['data' => $data])
		->extends('layouts.theme.app')
		->section('content');
    }
	
	public function Edit(Cliente $cliente)
	{
			$this->selected_id = $cliente->id;
			$this->nombre = $cliente->nombre;
			$this->tipo_documento = $cliente->tipo_documento;
			$this->provincia = $cliente->provincia;
			$this->licencia = $cliente->licencia;
			$this->vencimiento_licencia = $cliente->vencimiento_licencia;
			$this->documento = $cliente->documento;
			$this->ciudad = $cliente->ciudad;
			$this->telefono = $cliente->telefono;
			$this->direccion = $cliente->direccion;
			$this->correo = $cliente->correo;
						
			$this->emit('show-modal', 'show modal!');
	}
	
	public function Store()
	{
		$rules = [
			'nombre' => 'required',
			'tipo_documento' => 'required',
			'documento' => 'required',
			'telefono' => 'required',
			'provincia' => 'required',
			'licencia' => 'required',
			'vencimiento_licencia' => 'required',
			'ciudad' => 'required',
			'direccion' => 'required',
			'correo' => 'required'
		];
		
		$messages = [
			'name.require' => 'Nombre Requerido',
			'tipo_documento.require' => 'Inicio De Actividades Requerido',
			'documento.require' => 'El Cuit Es Requerido',
			'telefono.require' => 'El Telefono Es Requerido',
			'provincia.require' => 'La Provincia Es Requerida',
			'licencia.require' => 'La Licencia Es Requerida',
			'vencimiento_licencia.require' => 'El Vencimiento De La Licencia Es Requerido',
			'ciudad.require' => 'La Ciudad Es Requerida',
			'direccion.require' => 'La Direccion Es Requerida',
			'correo.require' => 'El Correo Es Requerido'
			
		];
		
		$this->validate($rules, $messages);
		
		$cliente = Cliente::create([
			'nombre' => $this->nombre,
			'tipo_documento' => $this->tipo_documento,
			'documento' => $this->documento,
			'telefono' => $this->telefono,
			'provincia' => $this->provincia,
			'licencia' => $this->licencia,
			'vencimiento_licencia' => $this->vencimiento_licencia,
			'ciudad' => $this->ciudad,
			'direccion' => $this->direccion,
			'correo' => $this->correo
		]);
		
		if($this->image) 
		{
			$customFileName = uniqid() . '_.' . $this->image->extension();
			$this->image->storeAs('public/clientes', $customFileName);
			$cliente->image = $customFileName;
			$cliente->save();
		}
		
		$this->resetUI();
		$this->emit('cliente-added','Cliente Agregado!!');
	}
	
	public function Update()
	{
		$rules = [
			'nombre' => 'required',
			'tipo_documento' => 'required',
			'documento' => 'required',
			'telefono' => 'required',
			'provincia' => 'required',
			'licencia' => 'required',
			'vencimiento_licencia' => 'required',
			'ciudad' => 'required',
			'direccion' => 'required',
			'correo' => 'required'
		];
		
		$messages = [
			'name.require' => 'Nombre Requerido',
			'tipo_documento.require' => 'Inicio De Actividades Requerido',
			'documento.require' => 'El Cuit Es Requerido',
			'telefono.require' => 'El Telefono Es Requerido',
			'provincia.require' => 'La Provincia Es Requerida',
			'licencia.require' => 'La Licencia Es Requerida',
			'vencimiento_licencia.require' => 'El Vencimiento De La Licencia Es Requerido',
			'ciudad.require' => 'La Ciudad Es Requerida',
			'direccion.require' => 'La Direccion Es Requerida',
			'correo.require' => 'El Correo Es Requerido'
			
		];
			
			$this->validate($rules, $messages);
			
			$cliente = Cliente::find($this->selected_id);
			$cliente->update([
				'nombre' => $this->nombre,
				'tipo_documento' => $this->tipo_documento,
				'documento' => $this->documento,
				'telefono' => $this->telefono,
				'provincia' => $this->provincia,
				'licencia' => $this->licencia,
				'vencimiento_licencia' => $this->vencimiento_licencia,
				'ciudad' => $this->ciudad,
				'direccion' => $this->direccion,
				'correo' => $this->correo
			]);
			
			if($this->image) 
			{
				$customFileName = uniqid() . '_.' . $this->image->extension();
				$this->image->storeAs('public/clientes', $customFileName);
				$imageTemp = $cliente->image; // imagen temporal
				$cliente->image = $customFileName;
				$cliente->save();

				if($imageTemp !=null) 
				{
					if(file_exists('storage/clientes/' . $imageTemp )) {
						unlink('storage/clientes/' . $imageTemp);
					}
				}
			}
				
			$this->resetUI();
			$this->emit('cliente-updated', 'Cliente Actualizado');
	}
	
	public function resetUI()
	{
		$this->nombre = '';
		$this->tipo_documento = 'Elegir';
		$this->documento = '';
		$this->telefono = '';
		$this->direccion = '';
		$this->provincia = '';
		$this->licencia = '';
		$this->vencimiento_licencia = '';
		$this->ciudad = '';
		$this->correo = '';
		$this->search = '';
		$this->selected_id = 0;
		$this->resetValidation();
	}
	
	protected $listeners =['deleteRow' => 'Destroy'];
	
	public function Destroy(Cliente $cliente)
	{
		$cliente->delete();
			
		$this->resetUI();
		$this->emit('cliente-deleted', 'Cliente Eliminado');
		
	}
}
