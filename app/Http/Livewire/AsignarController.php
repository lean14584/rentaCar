<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;
use DB;


class AsignarController extends Component
{
    use WithPagination;
	//variables publicas que se usan en la vista
    public $role, $componentName, $permisosSelected = [], $old_permissions =[];
	//variable privada para definir el rango de paginacion
    private $pagination = 10;
	//funcion de paginacion que tiene una extension de boostrap para los estilos
	protected $paginationTheme = 'bootstrap';
	//en el mount seteo valores a las variales publicas que yo necesitte que tengan una valor por defecto
    public function mount()
    {
        $this->role = 'Elegir';
        $this->componentName ='Asignar Permisos';       
    }
	//en el render dibujo mi vista, con lo cual hago las consultas a base de datos que necesite y subo los valores
    public function render()
    {
        $permisos = Permission::select('name','id', DB::raw("0 as checked") )
        ->orderBy('name','asc')
        ->paginate($this->pagination);

        if($this->role != 'Elegir')
        {
            $list = Permission::join('role_has_permissions as rp','rp.permission_id','permissions.id')
            ->where('role_id', $this->role)->pluck('permissions.id')->toArray();
            $this->old_permissions = $list;            
        }

        if($this->role != 'Elegir') 
        {
            foreach ($permisos as $permiso) {
                $role = Role::find($this->role);
                $tienePermiso = $role->hasPermissionTo($permiso->name);
                if($tienePermiso) {
                    $permiso->checked = 1;
                }
            }
        }

        return view('livewire.asignar.component',[
            'roles' => Role::orderBy('name','asc')->get(),
            'permisos' => $permisos
        ])->extends('layouts.theme.app')->section('content');
    }
	//en los listeners escucho eventos que se ejecuten en la vista y los ejecuto en el controlador
    public $listeners = ['revokeall' => 'RemoveAll'];
	//funcion para sacar los premisos (todos)
    public function RemoveAll()
    {
       if($this->role =='Elegir')
       {
        $this->emit('sync-error','Selecciona un role válido');
        return;
    }

    $role = Role::find($this->role);
    $role->syncPermissions([0]);
    $this->emit('removeall',"Se revocaron todos los permisos al role $role->name ");

}
//funcion para sincronizar todos los permisos
public function SyncAll()
{
    if($this->role =='Elegir')
    {
        $this->emit('sync-error','Selecciona un role válido');
        return;
    }

    $role = Role::find($this->role);
    $permisos = Permission::pluck('id')->toArray();
    $role->syncPermissions($permisos);

    $this->emit('syncall',"Se sincronizaron todos los permisos al role $role->name ");
}
//funcion para sincronizar solo un permiso
public function syncPermiso($state, $permisoName)
{
    if($this->role !='Elegir')
    {
        $roleName = Role::find($this->role);

        if($state)
        {
            $roleName->givePermissionTo($permisoName);
            $this->emit('permi',"Permiso asignado correctamente ");

        } else {
            $roleName->revokePermissionTo($permisoName);
            $this->emit('permi',"Permiso eliminado correctamente ");
        }

    } else {
        $this->emit('permi',"Elige un role válido");
    }
    
}


}
