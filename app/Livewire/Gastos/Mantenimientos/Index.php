<?php

namespace App\Livewire\Gastos\Mantenimientos;

use App\Models\Auto;
use App\Models\Mantenimiento;
use App\Models\Proveedor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public const TIPOS = [
        'Cambio De Aceite', 'Cambio De Cubiertas', 'Combustible',
        'Multas', 'Mantenimiento', 'Arreglos',
    ];

    public const METODOS_PAGO = ['Efectivo', 'Debito', 'Credito', 'Banco', 'Cta Cte'];

    public string $query = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $nombre = '';

    public string $auto_id = '';

    public string $proveedor_id = '';

    public string $km_actual = '';

    public string $km_proximo = '';

    public string $fecha_inicio = '';

    public string $fecha_fin = '';

    public string $metodo_pago = '';

    public string $valor = '';

    public function updatedQuery(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function autos()
    {
        return Auto::query()
            ->when(
                $this->editingId,
                fn ($q) => $q->where(fn ($q2) => $q2->where('estado', '!=', 'En Mantenimiento')->orWhere('id', $this->auto_id)),
                fn ($q) => $q->where('estado', '!=', 'En Mantenimiento')
            )
            ->orderBy('patente')
            ->get();
    }

    #[Computed]
    public function proveedores()
    {
        return Proveedor::orderBy('nombre')->get();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(Mantenimiento $mantenimiento): void
    {
        $this->resetForm();
        $this->editingId = $mantenimiento->id;
        $this->nombre = $mantenimiento->nombre;
        $this->auto_id = (string) $mantenimiento->auto_id;
        $this->proveedor_id = (string) $mantenimiento->proveedor_id;
        $this->km_actual = (string) $mantenimiento->km_actual;
        $this->km_proximo = (string) $mantenimiento->km_proximo;
        $this->fecha_inicio = $mantenimiento->fecha_inicio->toDateString();
        $this->fecha_fin = $mantenimiento->fecha_fin?->toDateString() ?? '';
        $this->metodo_pago = (string) $mantenimiento->metodo_pago;
        $this->valor = (string) $mantenimiento->valor;
        $this->showModal = true;
    }

    protected function rules(): array
    {
        return [
            'nombre' => ['required', 'string'],
            'auto_id' => ['required', 'exists:autos,id'],
            'proveedor_id' => ['required', 'exists:proveedores,id'],
            'km_actual' => ['required', 'integer', 'min:0'],
            'km_proximo' => ['required', 'integer', 'min:0'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'metodo_pago' => ['required', 'string'],
            'valor' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        // Carbon 3 (Laravel 13) devuelve diffInDays con signo según la
        // dirección de la resta, a diferencia de Carbon 2 (la app vieja) que
        // siempre devolvía el valor absoluto — mismo bug ya corregido en
        // Alquileres: el cálculo tiene que ir inicio->diffInDays(fin).
        $diasArreglo = $this->fecha_fin !== ''
            ? max(1, Carbon::parse($this->fecha_inicio)->diffInDays(Carbon::parse($this->fecha_fin)) + 1)
            : null;

        DB::transaction(function () use ($diasArreglo) {
            $mantenimiento = $this->editingId ? Mantenimiento::findOrFail($this->editingId) : new Mantenimiento(['estado' => 'En Curso']);
            $mantenimiento->fill([
                'nombre' => $this->nombre,
                'auto_id' => $this->auto_id,
                'proveedor_id' => $this->proveedor_id,
                'km_actual' => $this->km_actual,
                'km_proximo' => $this->km_proximo,
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin' => $this->fecha_fin ?: null,
                'dias_arreglo' => $diasArreglo,
                'metodo_pago' => $this->metodo_pago,
                'valor' => $this->valor,
            ]);
            $mantenimiento->save();

            $auto = Auto::findOrFail($this->auto_id);
            if ($this->nombre === 'Cambio De Aceite') {
                $auto->kilometros = $this->km_actual;
                $auto->cambio_aceite = $this->km_proximo;
            }

            // El auto queda marcado "En Mantenimiento" apenas se carga el
            // gasto — igual que en Alquileres, donde reservar bloquea el
            // auto para otra reserva (acá bloquea que se pueda alquilar
            // mientras está en el taller).
            if ($mantenimiento->estado === 'En Curso') {
                $auto->estado = 'En Mantenimiento';
            }
            $auto->save();
        });

        $this->showModal = false;
        $this->resetForm();
        session()->flash('status', $this->editingId ? 'Gasto actualizado.' : 'Gasto cargado.');
    }

    public function finalizar(Mantenimiento $mantenimiento): void
    {
        DB::transaction(function () use ($mantenimiento) {
            $mantenimiento->update(['estado' => 'Finalizada']);

            if (! Mantenimiento::where('auto_id', $mantenimiento->auto_id)->where('estado', 'En Curso')->exists()) {
                $mantenimiento->auto->update(['estado' => 'Disponible']);
            }
        });

        session()->flash('status', 'Gasto finalizado, auto liberado.');
    }

    public function delete(Mantenimiento $mantenimiento): void
    {
        DB::transaction(function () use ($mantenimiento) {
            $autoId = $mantenimiento->auto_id;
            $mantenimiento->delete();

            if (! Mantenimiento::where('auto_id', $autoId)->where('estado', 'En Curso')->exists()) {
                Auto::whereKey($autoId)->update(['estado' => 'Disponible']);
            }
        });

        session()->flash('status', 'Gasto eliminado.');
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingId', 'nombre', 'auto_id', 'proveedor_id', 'km_actual',
            'km_proximo', 'fecha_inicio', 'fecha_fin', 'metodo_pago', 'valor',
        ]);
        $this->resetErrorBag();
    }

    public function render()
    {
        $mantenimientos = Mantenimiento::query()
            ->with(['auto', 'proveedor'])
            ->when(
                $this->query !== '',
                fn ($q) => $q->whereHas('auto', fn ($a) => $a->where('patente', 'like', "%{$this->query}%"))
                    ->orWhere('nombre', 'like', "%{$this->query}%")
            )
            ->orderByDesc('id')
            ->paginate(15);

        return view('livewire.gastos.mantenimientos.index', ['mantenimientos' => $mantenimientos]);
    }
}
