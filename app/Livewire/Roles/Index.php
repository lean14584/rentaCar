<?php

namespace App\Livewire\Roles;

use App\Support\Pantallas;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
class Index extends Component
{
    public bool $showModal = false;

    public ?int $editingId = null;

    public string $nombre = '';

    /** @var array<string, bool> */
    public array $permisos = [];

    public function mount(): void
    {
        $this->permisos = array_fill_keys(array_keys(Pantallas::LABELS), false);
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(Role $role): void
    {
        $this->resetForm();
        $this->editingId = $role->id;
        $this->nombre = $role->name;

        $asignados = $role->permissions->pluck('name')->all();
        foreach (array_keys(Pantallas::LABELS) as $pantalla) {
            $this->permisos[$pantalla] = in_array($pantalla, $asignados, true);
        }

        $this->showModal = true;
    }

    protected function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'min:2', Rule::unique('roles', 'name')->ignore($this->editingId)],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $role = $this->editingId ? Role::findOrFail($this->editingId) : new Role(['guard_name' => 'web']);
        $role->name = $this->nombre;
        $role->save();

        $seleccionados = array_keys(array_filter($this->permisos));
        $role->syncPermissions($seleccionados);

        $this->showModal = false;
        $this->resetForm();
        session()->flash('status', $this->editingId ? 'Rol actualizado.' : 'Rol creado.');
    }

    public function delete(Role $role): void
    {
        if ($role->users()->exists()) {
            session()->flash('error', 'No se puede borrar: hay usuarios con este rol asignado.');

            return;
        }

        $role->delete();
        session()->flash('status', 'Rol eliminado.');
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'nombre']);
        $this->permisos = array_fill_keys(array_keys(Pantallas::LABELS), false);
        $this->resetErrorBag();
    }

    public function render()
    {
        $roles = Role::withCount('permissions', 'users')->orderBy('name')->get();

        return view('livewire.roles.index', ['roles' => $roles, 'labels' => Pantallas::LABELS]);
    }
}
