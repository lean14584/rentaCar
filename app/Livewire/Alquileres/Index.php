<?php

namespace App\Livewire\Alquileres;

use App\Models\Alquiler;
use App\Models\Auto;
use App\Models\Cadena;
use App\Models\Caja;
use App\Models\CajaSesion;
use App\Models\Cliente;
use App\Models\Matafuego;
use App\Models\Silla;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public string $query = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $auto_id = '';

    public string $cliente_id = '';

    public string $cadena_id = '';

    public string $matafuego_id = '';

    public string $silla_id = '';

    public string $fecha_inicio = '';

    public string $fecha_fin = '';

    public string $lugar_retiro = '';

    public string $lugar_devolucion = '';

    public string $metodo_pago = '';

    public string $entrega = '0';

    public function updatedQuery(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function autosDisponibles()
    {
        return Auto::query()
            ->when(
                $this->editingId,
                fn ($q) => $q->where(fn ($q2) => $q2->where('estado', 'Disponible')->orWhere('id', $this->auto_id)),
                fn ($q) => $q->where('estado', 'Disponible')
            )
            ->with('marca')
            ->orderBy('patente')
            ->get();
    }

    #[Computed]
    public function clientes()
    {
        return Cliente::orderBy('nombre')->get();
    }

    #[Computed]
    public function cadenasDisponibles()
    {
        if ($this->auto_id === '') {
            return collect();
        }

        return Cadena::where('auto_id', $this->auto_id)
            ->where(fn ($q) => $q->where('estado', 'Disponible')->orWhere('id', $this->cadena_id))
            ->get();
    }

    #[Computed]
    public function matafuegosDisponibles()
    {
        if ($this->auto_id === '') {
            return collect();
        }

        return Matafuego::where('auto_id', $this->auto_id)
            ->where(fn ($q) => $q->where('estado', 'Disponible')->orWhere('id', $this->matafuego_id))
            ->get();
    }

    #[Computed]
    public function sillasDisponibles()
    {
        if ($this->auto_id === '') {
            return collect();
        }

        return Silla::where('auto_id', $this->auto_id)
            ->where(fn ($q) => $q->where('estado', 'Disponible')->orWhere('id', $this->silla_id))
            ->get();
    }

    /** Cambiar de auto invalida los accesorios elegidos: pertenecen a OTRO auto. */
    public function updatedAutoId(): void
    {
        $this->cadena_id = '';
        $this->matafuego_id = '';
        $this->silla_id = '';
        unset($this->cadenasDisponibles, $this->matafuegosDisponibles, $this->sillasDisponibles);
    }

    #[Computed]
    public function dias(): int
    {
        if ($this->fecha_inicio === '' || $this->fecha_fin === '') {
            return 0;
        }

        $inicio = Carbon::parse($this->fecha_inicio);
        $fin = Carbon::parse($this->fecha_fin);

        // Carbon 3 (Laravel 13) devuelve diffInDays con signo según la
        // dirección — a diferencia de Carbon 2 (la app vieja), que siempre
        // daba el valor absoluto. Con $fin más tarde que $inicio al revés
        // esto daba negativo y el max(1, ...) lo tapaba en 1 día siempre,
        // cobrando de menos en cualquier alquiler de más de un día.
        return max(1, $inicio->diffInDays($fin) + 1);
    }

    #[Computed]
    public function importePorDia(): float
    {
        $total = (float) (Auto::find($this->auto_id)?->precio_alquiler ?? 0);
        $total += (float) (Cadena::find($this->cadena_id)?->valor ?? 0);
        $total += (float) (Matafuego::find($this->matafuego_id)?->valor ?? 0);
        $total += (float) (Silla::find($this->silla_id)?->valor ?? 0);

        return $total;
    }

    #[Computed]
    public function total(): float
    {
        return $this->dias * $this->importePorDia;
    }

    #[Computed]
    public function saldo(): float
    {
        return max(0, $this->total - (float) ($this->entrega ?: 0));
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(Alquiler $alquiler): void
    {
        $this->resetForm();
        $this->editingId = $alquiler->id;
        $this->auto_id = (string) $alquiler->auto_id;
        $this->cliente_id = (string) $alquiler->cliente_id;
        $this->cadena_id = (string) ($alquiler->cadena_id ?? '');
        $this->matafuego_id = (string) ($alquiler->matafuego_id ?? '');
        $this->silla_id = (string) ($alquiler->silla_id ?? '');
        $this->fecha_inicio = $alquiler->fecha_inicio->toDateString();
        $this->fecha_fin = $alquiler->fecha_fin->toDateString();
        $this->lugar_retiro = $alquiler->lugar_retiro;
        $this->lugar_devolucion = $alquiler->lugar_devolucion;
        $this->metodo_pago = $alquiler->metodo_pago;
        $this->entrega = (string) $alquiler->entrega;
        $this->showModal = true;
    }

    protected function rules(): array
    {
        return [
            'auto_id' => ['required', 'exists:autos,id'],
            'cliente_id' => ['required', 'exists:clientes,id'],
            'cadena_id' => ['nullable', 'exists:cadenas,id'],
            'matafuego_id' => ['nullable', 'exists:matafuegos,id'],
            'silla_id' => ['nullable', 'exists:sillas,id'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'lugar_retiro' => ['required', 'string'],
            'lugar_devolucion' => ['required', 'string'],
            'metodo_pago' => ['required', 'string'],
            'entrega' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $superpuesto = Alquiler::superpuestoCon((int) $this->auto_id, $this->fecha_inicio, $this->fecha_fin, $this->editingId)->exists();

        if ($superpuesto) {
            $this->addError('fecha_inicio', 'Este auto ya tiene otro alquiler activo que se cruza con esas fechas.');

            return;
        }

        DB::transaction(function () {
            $auto = Auto::findOrFail($this->auto_id);

            $alquiler = $this->editingId ? Alquiler::findOrFail($this->editingId) : new Alquiler(['estado' => 'Activo']);
            $alquiler->fill([
                'auto_id' => $this->auto_id,
                'cliente_id' => $this->cliente_id,
                'cadena_id' => $this->cadena_id ?: null,
                'matafuego_id' => $this->matafuego_id ?: null,
                'silla_id' => $this->silla_id ?: null,
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin' => $this->fecha_fin,
                'lugar_retiro' => $this->lugar_retiro,
                'lugar_devolucion' => $this->lugar_devolucion,
                'metodo_pago' => $this->metodo_pago,
                'dias_alquiler' => $this->dias,
                'importe_por_dia' => $this->importePorDia,
                'total' => $this->total,
                'entrega' => $this->entrega,
                'saldo' => $this->saldo,
            ]);
            $alquiler->save();

            // El auto queda marcado Alquilado apenas se reserva (no recién
            // cuando se retira): la app vieja nunca sincronizaba esto, el
            // estado del auto había que cambiarlo a mano desde Autos.
            if ($alquiler->estado === 'Activo') {
                $auto->update(['estado' => 'Alquilado']);
            }

            // La entrega queda como un ingreso en Caja, vinculado a este
            // alquiler — si se edita el monto o se borra el alquiler, el
            // movimiento se actualiza/borra con él en vez de quedar suelto.
            if ((float) $this->entrega > 0) {
                $cajaExistente = Caja::where('alquiler_id', $alquiler->id)->first();
                $datosCaja = [
                    'usuario_id' => Auth::id(),
                    'auto_id' => $alquiler->auto_id,
                    'tipo' => 'Ingreso',
                    'monto' => $this->entrega,
                    'metodo_pago' => $this->metodo_pago,
                    'descripcion' => 'Entrega por alquiler a '.$alquiler->cliente->nombre,
                ];
                // La sesión de caja se fija solo al crear el movimiento — si
                // ya existía (se está editando el alquiler), no se mueve a
                // la sesión actual aunque haya cambiado desde que se creó.
                if (! $cajaExistente) {
                    $datosCaja['caja_sesion_id'] = CajaSesion::abierta()?->id;
                }
                Caja::updateOrCreate(['alquiler_id' => $alquiler->id], $datosCaja);
            } else {
                Caja::where('alquiler_id', $alquiler->id)->delete();
            }
        });

        $mensaje = $this->editingId ? 'Alquiler actualizado.' : 'Alquiler creado.';

        $this->showModal = false;
        $this->resetForm();
        session()->flash('status', $mensaje);
    }

    public function finalizar(Alquiler $alquiler): void
    {
        abort_unless(Auth::user()->can('alquileres'), 403);

        DB::transaction(function () use ($alquiler) {
            $alquiler->update(['estado' => 'Finalizado']);

            if (! Alquiler::where('auto_id', $alquiler->auto_id)->where('estado', 'Activo')->exists()) {
                $alquiler->auto->update(['estado' => 'Disponible']);
            }
        });

        session()->flash('status', 'Alquiler finalizado, auto liberado.');
    }

    public function delete(Alquiler $alquiler): void
    {
        abort_unless(Auth::user()->can('alquileres'), 403);

        DB::transaction(function () use ($alquiler) {
            $autoId = $alquiler->auto_id;
            $alquiler->delete();

            if (! Alquiler::where('auto_id', $autoId)->where('estado', 'Activo')->exists()) {
                Auto::whereKey($autoId)->update(['estado' => 'Disponible']);
            }
        });

        session()->flash('status', 'Alquiler eliminado.');
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingId', 'auto_id', 'cliente_id', 'cadena_id', 'matafuego_id', 'silla_id',
            'fecha_inicio', 'fecha_fin', 'lugar_retiro', 'lugar_devolucion', 'metodo_pago',
        ]);
        $this->entrega = '0';
        $this->resetErrorBag();
    }

    public function render()
    {
        $alquileres = Alquiler::query()
            ->with(['auto', 'cliente'])
            ->when(
                $this->query !== '',
                fn ($q) => $q->whereHas('auto', fn ($a) => $a->where('patente', 'like', "%{$this->query}%"))
                    ->orWhereHas('cliente', fn ($c) => $c->where('nombre', 'like', "%{$this->query}%"))
            )
            ->orderByDesc('id')
            ->paginate(15);

        return view('livewire.alquileres.index', ['alquileres' => $alquileres]);
    }
}
