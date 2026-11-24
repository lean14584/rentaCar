<?php

namespace App\Livewire\Clientes;

use App\Models\Cliente;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithFileUploads;
    use WithPagination;

    public string $query = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $nombre = '';

    public string $tipo_documento = '';

    public string $documento = '';

    public string $telefono = '';

    public string $direccion = '';

    public string $correo = '';

    public string $provincia = '';

    public string $ciudad = '';

    public string $licencia = '';

    public string $vencimiento_licencia = '';

    public ?UploadedFile $image = null;

    public function updatedQuery(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(Cliente $cliente): void
    {
        $this->resetForm();
        $this->editingId = $cliente->id;
        $this->nombre = $cliente->nombre;
        $this->tipo_documento = (string) $cliente->tipo_documento;
        $this->documento = (string) $cliente->documento;
        $this->telefono = (string) $cliente->telefono;
        $this->direccion = (string) $cliente->direccion;
        $this->correo = (string) $cliente->correo;
        $this->provincia = (string) $cliente->provincia;
        $this->ciudad = (string) $cliente->ciudad;
        $this->licencia = (string) $cliente->licencia;
        $this->vencimiento_licencia = $cliente->vencimiento_licencia?->toDateString() ?? '';
        $this->showModal = true;
    }

    protected function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'min:3'],
            'tipo_documento' => ['required'],
            'documento' => ['required', 'string'],
            'telefono' => ['required', 'string'],
            'direccion' => ['required', 'string'],
            'correo' => ['required', 'email'],
            'provincia' => ['required', 'string'],
            'ciudad' => ['required', 'string'],
            'licencia' => ['required', 'string'],
            'vencimiento_licencia' => ['required', 'date'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $cliente = $this->editingId ? Cliente::findOrFail($this->editingId) : new Cliente;
        $cliente->fill([
            'nombre' => $this->nombre,
            'tipo_documento' => $this->tipo_documento,
            'documento' => $this->documento,
            'telefono' => $this->telefono,
            'direccion' => $this->direccion,
            'correo' => $this->correo,
            'provincia' => $this->provincia,
            'ciudad' => $this->ciudad,
            'licencia' => $this->licencia,
            'vencimiento_licencia' => $this->vencimiento_licencia,
        ]);
        $cliente->save();

        if ($this->image) {
            $anterior = $cliente->image;
            $cliente->update(['image' => $this->image->store('clientes', 'public')]);

            if ($anterior) {
                Storage::disk('public')->delete($anterior);
            }
        }

        $this->showModal = false;
        $this->resetForm();
        session()->flash('status', $this->editingId ? 'Cliente actualizado.' : 'Cliente creado.');
    }

    public function delete(Cliente $cliente): void
    {
        abort_unless(Auth::user()->can('clientes'), 403);

        if ($cliente->alquileres()->exists()) {
            session()->flash('error', 'No se puede borrar: tiene alquileres cargados.');

            return;
        }

        if ($cliente->image) {
            Storage::disk('public')->delete($cliente->image);
        }

        $cliente->delete();
        session()->flash('status', 'Cliente eliminado.');
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingId', 'nombre', 'tipo_documento', 'documento', 'telefono', 'direccion',
            'correo', 'provincia', 'ciudad', 'licencia', 'vencimiento_licencia', 'image',
        ]);
        $this->resetErrorBag();
    }

    public function render()
    {
        $clientes = Cliente::query()
            ->when($this->query !== '', fn ($q) => $q->where('nombre', 'like', "%{$this->query}%"))
            ->orderBy('nombre')
            ->paginate(15);

        return view('livewire.clientes.index', ['clientes' => $clientes]);
    }
}
