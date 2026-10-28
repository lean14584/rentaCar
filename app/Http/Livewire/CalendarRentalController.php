<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CalendarRentalController extends Component
{
    public $selectedMonth;
    public $selectedYear;

    protected $dt; //dataTime
    public $daysInMonth;
    public $rentals;
    public $colors = [
        "bg-primary",
        "bg-secondary",
        "bg-success",
        "bg-danger",
        "bg-warning",
        "bg-info"
    ];
    public $total;

    public function render()
    {
        return view('livewire.calendar-rental.index', [
            "years" => range(Carbon::now()->year + 1, 2020),
            "months" => Carbon::parse(Carbon::now()->startOfYear())->monthsUntil(Carbon::now()->endOfYear()),

        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function updatedSelectedMonth()
    {
        $this->dt = Carbon::createFromDate($this->selectedYear, $this->selectedMonth);
        $this->daysInMonth = $this->getCurrentMonthDays($this->dt);
        $this->rentals = $this->rentals();

        $patentes = [];
        $patentesColors = [];
        $patentesTotal = [];
        $patentesSubTotal = [];

        foreach ($this->rentals as $rentalesDeEsaPatente) {
            foreach ($rentalesDeEsaPatente as $rental) {
                // Color determinístico por alquiler (patente + fechas), no
                // al azar en cada render: antes array_rand() podía pintar
                // el mismo auto de un color distinto cada vez que se
                // recargaba el calendario, sin ningún motivo.
                $colorIndex = crc32($rental->patente . $rental->fechaInicio . $rental->fechaFin) % count($this->colors);
                $color = $this->colors[$colorIndex];

                // El total de la patente (columna "Total") solo suma el
                // alquiler en el mes donde REALMENTE empieza - si ya se
                // sumó al verlo en su mes de inicio, no hay que volver a
                // sumarlo acá solo porque también pisa este mes.
                if ($rental->empiezaEnEsteMes) {
                    $patentesTotal[$rental->patente] = ($patentesTotal[$rental->patente] ?? 0) + (float) $rental->total;
                }

                foreach ($rental->periodDays as $index => $day) {
                    $key = $rental->patente . '-' . $day;
                    $patentes[] = $key;
                    $patentesColors[$key] = $color;

                    // El importe solo se muestra en la primera celda del
                    // alquiler, y solo si ese primer día es el de inicio
                    // real (no el día 1 "de corte" cuando el alquiler
                    // arrancó el mes anterior y sigue acá).
                    if ($index === 0 && $rental->empiezaEnEsteMes) {
                        $patentesSubTotal[$key] = $rental->total;
                    }
                }
            }
        }

        // call the "rentalAdded" function in the view script
        $this->emit("rentalAdded", $patentes, $patentesColors, $patentesTotal, $patentesSubTotal);
    }


    public function updatedSelectedYear()
    {
        $this->selectedMonth = null;
    }

    private function getCurrentMonthDays($dt)
    {
        if (! $dt) {
            return null;
        }

        return collect(CarbonPeriod::create($dt->copy()->startOfMonth(), $dt->copy()->endOfMonth()))->all();
    }

    private function rentals()
    {

        // "id" => 13
        // "idAuto" => 2
        // "idCliente" => 6
        // "idCadena" => 4
        // "idMatafuego" => 0
        // "idSilla" => 0
        // "fechaInicio" => "2022-12-02"
        // "fechaFin" => "2022-12-03"
        // "diasAlquiler" => "2"
        // "lugar_retiro" => "Aeropuerto"
        // "lugar_devolucion" => "Aeropuerto"
        // "metodo_pago" => "Efectivo"
        // "importePorDia" => "1200.00"
        // "total" => "8400.00"
        // "entrega" => "6000.00"
        // "saldo" => "2400.00"
        // "estado" => "Cargada"
        // "image" => "Cargada"
        // "created_at" => "2022-12-02 13:22:39"
        // "updated_at" => "2022-12-02 19:37:42"
        // "patente" => "OLD-365"
        // "nombre" => "Juan Roque"
        $data = DB::table('alquilers as al')
            ->join('autos as a', 'a.id', 'al.idAuto')
            ->join('clientes as c', 'c.id', 'al.idCliente')
            ->select('a.patente as patente', 'c.nombre as nombre', 'al.fechaInicio', 'al.fechaFin', 'al.total')->get();

        $monthStart = $this->dt->copy()->startOfMonth();
        $monthEnd = $this->dt->copy()->endOfMonth();

        // BUGFIX: antes esto comparaba meses como string ("12" - 1 para
        // detectar "el mes anterior") y se rompía en el cambio de año -
        // enero ("1") - 1 da 0, no "12" del año anterior. CarbonPeriod
        // resuelve el rango de días de cada alquiler DENTRO del mes
        // elegido (recortado con min()/max() a los bordes del mes) sin
        // ninguna matemática de calendario a mano.
        $processedData = [];
        foreach ($data as $rental) {
            $startDate = Carbon::parse($rental->fechaInicio);
            $endDate = Carbon::parse($rental->fechaFin);

            // El alquiler no toca el mes elegido en absoluto.
            if ($endDate->lt($monthStart) || $startDate->gt($monthEnd)) {
                continue;
            }

            $periodoEnElMes = CarbonPeriod::create($startDate->max($monthStart), $endDate->min($monthEnd));

            $rental->periodDays = collect($periodoEnElMes)->map(fn ($d) => $d->isoFormat('D'))->all();
            $rental->empiezaEnEsteMes = $startDate->isSameMonth($monthStart) && $startDate->isSameYear($monthStart);

            $processedData[] = $rental;
        }

        return collect($processedData)->groupBy('patente')->all();
    }
}
