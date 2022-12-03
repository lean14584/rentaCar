<?php

namespace App\Http\Livewire;
use App\Models\Sale;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UsuariosController extends Component
{

    use WithPagination;
    use WithFileUploads;

    public $name,$phone,$email,$status,$image,$password,$selected_id,$fileLoaded,$profile;
    public $pageTitle, $componentName, $search;
    private $pagination = 5;

	protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->pageTitle ='Listado';
        $this->componentName ='Usuarios';
        $this->status ='Elegir';
    }

    public function render()
    {       
        if(strlen($this->search) > 0)
            $data = User::where('name', 'like', '%' . $this->search . '%')
        ->select('*')->orderBy('name','asc')->paginate($this->pagination);
        else
           $data = User::select('*')->orderBy('name','asc')->paginate($this->pagination);

       return view('livewire.usuarios.component',[
        'data' => $data,
        'roles' => Role::orderBy('name','asc')->get()
    ])
       ->extends('layouts.theme.app')
       ->section('content');
   }

   public function resetUI()
   {
    $this->name ='';
    $this->email='';
    $this->password='';
    $this->phone='';
    $this->image ='';
    $this->search ='';
    $this->status ='Elegir';
	$this->profile ='Elegir';
    $this->selected_id =0;
    $this->resetValidation();
    //$this->resetPage();
	}

	public function Edit(User $user)
	{
		$this->selected_id = $user->id;
		$this->name = $user->name;
		$this->phone = $user->phone;
		$this->profile = $this->profile;
		$this->status = $user->status;
		$this->email = $user->email;
		$this->password ='';

		$this->emit('show-modal','open!');

	}

	protected $listeners =[
		'deleteRow' => 'destroy',
		'resetUI' => 'resetUI'

	];

	public function Store()
	{
	 $rules =[
		'name' => 'required|min:3',
		'email' => 'required|unique:users|email',
		'status' => 'required|not_in:Elegir',
		'profile' => 'required|not_in:Elegir',
		'password' => 'required|min:3'
	];

	$messages =[
		'name.required' => 'Ingresa El Nombre',
		'name.min' => 'El Nombre Del Usuario Debe Tener Al Menos 3 Caracteres',
		'email.required' => 'Ingresa El Correo ',
		'email.email' => 'Ingresa Un Correo Válido',
		'email.unique' => 'El Email Ya Existe En El Sistema',
		'status.required' => 'Selecciona El Estatus Del Usuario',
		'status.not_in' => 'Selecciona El Estatus',
		'profile.required' => 'Selecciona El Perfil/Rol Del Usuario',
		'profile.not_in' => 'Selecciona Un Perfil/Rol Distinto A Elegir',
		'password.required' => 'Ingresa El Password',
		'password.min' => 'El Password Debe Tener Al Menos 3 Caracteres'
	];

	$this->validate($rules, $messages);

	$user = User::create([
		'name' => $this->name,
		'email' => $this->email,
		'phone' => $this->phone,
		'status' => $this->status,
		'profile' => $this->profile,
		'password' => bcrypt($this->password)
	]);

	$user->syncRoles($this->profile);

	if($this->image) 
	{
		$customFileName = uniqid() . ' _.' . $this->image->extension();
		$this->image->storeAs('public/users', $customFileName);
		$user->image = $customFileName;
		$user->save();
	}

	$this->resetUI();
	$this->emit('user-added','Usuario Registrado');

	}

	public function Update()
	{

		$rules =[
			'email' => "required|email|unique:users,email,{$this->selected_id}",
			'name' => 'required|min:3',    
			'status' => 'required|not_in:Elegir',
			'profile' => 'required|not_in:Elegir'
		];

		$messages =[
			'name.required' => 'Ingresa El Nombre',
			'name.min' => 'El Nombre Del Usuario Debe Tener Al Menos 3 Caracteres',
			'email.required' => 'Ingresa El Correo ',
			'email.email' => 'Ingresa Un Correo Válido',
			'email.unique' => 'El Email Ya Existe En El Sistema',
			'status.required' => 'Selecciona El Estatus Del Usuario',
			'status.not_in' => 'Selecciona El Estatus',
			'profile.required' => 'Selecciona El Perfil/Rol Del Usuario',
			'profile.not_in' => 'Selecciona un Perfil/Rol Distinto A Elegir'
		];

		$this->validate($rules, $messages);

		$user = User::find($this->selected_id);
		$user->update([
			'name' => $this->name,
			'email' => $this->email,
			'phone' => $this->phone,
			'status' => $this->status,
			'profile' => $this->profile,
			'password' => strlen($this->password) > 0 ? bcrypt($this->password) : $user->password
		]);
		
		$user->syncRoles($this->profile);

		if($this->image) 
		{
			$customFileName = uniqid() . ' _.' . $this->image->extension();
			$this->image->storeAs('public/users', $customFileName);
			$imageTemp = $user->image;

			$user->image = $customFileName;
			$user->save();

			if($imageTemp !=null) 
			{
				if(file_exists('storage/users/' . $imageTemp)) {
					unlink('storage/users/' . $imageTemp);
				}
			}


		}

		$this->resetUI();
		$this->emit('user-updated','Usuario Actualizado');

	}

	public function destroy(User $user)
		{
		 if($user) {
			$sales = Sale::where('user_id', $user->id)->count();
			if($sales > 0)  {
				$this->emit('user-withsales','No es posible eliminar el usuario porque tiene ventas registradas');
			} else {
				$user->delete();
				$this->resetUI();
				$this->emit('user-deleted','Usuario Eliminado');
			}
		}
	}

}
