<?php

namespace App\Livewire\Usuarios;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithFileUploads;
    use WithPagination;

    public string $query = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $username = '';

    public string $email = '';

    public string $telefono = '';

    public bool $activo = true;

    public string $role = '';

    public string $password = '';

    public ?UploadedFile $image = null;

    public function updatedQuery(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function roles()
    {
        return Role::orderBy('name')->get();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(User $user): void
    {
        $this->resetForm();
        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->telefono = (string) $user->telefono;
        $this->activo = $user->activo;
        $this->role = $user->getRoleNames()->first() ?? '';
        $this->showModal = true;
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'username' => ['required', 'string', Rule::unique('users', 'username')->ignore($this->editingId)],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->editingId)],
            'telefono' => ['nullable', 'string'],
            'activo' => ['boolean'],
            'role' => ['required', 'exists:roles,name'],
            'password' => [$this->editingId ? 'nullable' : 'required', 'string', 'min:6'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $user = $this->editingId ? User::findOrFail($this->editingId) : new User;
        $user->fill([
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'activo' => $this->activo,
        ]);

        if ($this->password !== '') {
            $user->password = $this->password;
        }

        $user->save();
        $user->syncRoles([$this->role]);

        if ($this->image) {
            $anterior = $user->image;
            $user->update(['image' => $this->image->store('usuarios', 'public')]);

            if ($anterior) {
                Storage::disk('public')->delete($anterior);
            }
        }

        $this->showModal = false;
        $this->resetForm();
        session()->flash('status', $this->editingId ? 'Usuario actualizado.' : 'Usuario creado.');
    }

    public function delete(User $user): void
    {
        if ($user->id === Auth::id()) {
            session()->flash('error', 'No podés borrar tu propio usuario.');

            return;
        }

        if ($user->image) {
            Storage::disk('public')->delete($user->image);
        }

        $user->delete();
        session()->flash('status', 'Usuario eliminado.');
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'name', 'username', 'email', 'telefono', 'role', 'password', 'image']);
        $this->activo = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        $usuarios = User::query()
            ->when($this->query !== '', fn ($q) => $q->where('name', 'like', "%{$this->query}%")
                ->orWhere('username', 'like', "%{$this->query}%"))
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.usuarios.index', ['usuarios' => $usuarios]);
    }
}
