<?php

namespace App\Livewire\Calendario;

use App\Models\Alquiler;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    public const MESES = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
    ];

    /** Paleta fija para que cada alquiler tenga un color determinístico (ver color() más abajo). */
    private const COLORES = [
        'bg-blue-500', 'bg-emerald-500', 'bg-violet-500', 'bg-rose-500',
        'bg-amber-600', 'bg-cyan-600', 'bg-fuchsia-500', 'bg-orange-500',
    ];

    public int $anio;

    public int $mes;

    public function mount(): void
    {
        $this->anio = now()->year;
        $this->mes = now()->month;
    }

    #[Computed]
    public function anios(): array
    {
        $actual = now()->year;

        return range($actual + 1, $actual - 3);
    }

    #[Computed]
    public function diasDelMes(): array
    {
        $inicio = Carbon::createFromDate($this->anio, $this->mes, 1)->startOfMonth();

        return collect(CarbonPeriod::create($inicio, $inicio->copy()->endOfMonth()))->all();
    }

    /**
     * Tabla tipo Gantt: una fila por auto, una columna por día del mes,
     * cada alquiler pintado con un color propio en los días que ocupa.
     * Reemplaza la tabla equivalente de la app vieja (que pintaba las
     * celdas a mano con JS); acá se arma el color y el total directamente
     * en PHP y Blade solo pinta.
     */
    #[Computed]
    public function filas(): Collection
    {
        $inicioMes = Carbon::createFromDate($this->anio, $this->mes, 1)->startOfMonth();
        $finMes = $inicioMes->copy()->endOfMonth();

        $alquileres = Alquiler::query()
            ->with(['auto.marca', 'cliente'])
            ->where('fecha_inicio', '<=', $finMes)
            ->where('fecha_fin', '>=', $inicioMes)
            ->get();

        return $alquileres
            ->groupBy('auto_id')
            ->map(function (Collection $alquileresDelAuto) use ($inicioMes, $finMes) {
                $auto = $alquileresDelAuto->first()->auto;
                $celdas = [];
                $total = 0.0;

                foreach ($alquileresDelAuto as $alquiler) {
                    $desde = $alquiler->fecha_inicio->copy()->max($inicioMes);
                    $hasta = $alquiler->fecha_fin->copy()->min($finMes);
                    $color = self::COLORES[crc32((string) $alquiler->id) % count(self::COLORES)];

                    // El total de la columna "Total" y el importe mostrado en
                    // la celda solo cuentan si el alquiler empieza DENTRO de
                    // este mes — si arrancó el mes anterior y sigue acá, ya se
                    // contó al verlo en su mes de inicio.
                    $empiezaEnEsteMes = $alquiler->fecha_inicio->isSameMonth($inicioMes) && $alquiler->fecha_inicio->isSameYear($inicioMes);

                    if ($empiezaEnEsteMes) {
                        $total += (float) $alquiler->total;
                    }

                    $primerDiaDelTramo = true;
                    foreach (CarbonPeriod::create($desde, $hasta) as $dia) {
                        $celdas[$dia->day] = [
                            'color' => $color,
                            'cliente' => $alquiler->cliente->nombre,
                            'importe' => ($primerDiaDelTramo && $empiezaEnEsteMes) ? (float) $alquiler->total : null,
                        ];
                        $primerDiaDelTramo = false;
                    }
                }

                return [
                    'patente' => $auto->patente,
                    'marca' => $auto->marca->nombre,
                    'celdas' => $celdas,
                    'total' => $total,
                ];
            })
            ->sortBy('patente')
            ->values();
    }

    public function render()
    {
        return view('livewire.calendario.index');
    }
}
