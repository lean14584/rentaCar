<?php

namespace App\Http\Livewire;

use App\Models\Auto;
use Carbon\Carbon;
use Illuminate\Support\Arr;
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
    private $periodDaysPrevMonth = [];

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

        if ($this->rentals) {
            $patentes = [];
            $patentesSubTotal = [];
            $patentesColors = [];
            $patentesTotal = [];
            $patentesArray = [];
            $test = [];

            foreach ($this->rentals as $key => $rental) {
                foreach ($rental as $key2 => $values) {
                    $randColor = array_rand($this->colors, 1);

                    $startDate = Carbon::createFromDate($values['fechaInicio']);
                    if ($startDate->isoFormat('M') == $this->dt->isoFormat('M')) {
                        $patentesArray = array('patentes' => $values['patente'], 'total' => $values['total']);

                        if (array_key_exists($patentesArray['patentes'], $patentesTotal)) {
                            $patentesTotal[$patentesArray['patentes']] += $patentesArray['total'];
                        } else {
                            $patentesTotal[$patentesArray['patentes']] = $patentesArray['total'];
                        }
                    }

                    foreach ($values['periodDays'] as $key => $value) {
                        array_push($patentes, $values['patente'] . '-' . $value);
                        $keys = $values['patente'] . '-' . $value;
                        if ($key == 0  && $startDate->isoFormat('M') == $this->dt->isoFormat('M')) {
                            $patentesSubTotal = Arr::add($patentesSubTotal, $keys, $values['total']);
                        }
                        $patentesColors = Arr::add($patentesColors, $keys, $this->colors[$randColor]);
                        array_push($test, $key . '-' . $value);
                    }
                }
            }
            // call the "rentalAdded" function in the view script
            $this->emit("rentalAdded", $patentes, $patentesColors, $patentesTotal, $patentesSubTotal);
        }
    }


    public function updatedSelectedYear()
    {
        $this->selectedMonth = null;
    }

    private function getCurrentMonthDays($dt)
    {
        $monthDays = null;
        if ($dt) {
            $date = $dt;
            $startOfMonth = $date->copy()->startOfMonth()->subDay();
            $endOfMonth = $date->copy()->endOfMonth()->format('d');
            $monthDays = [];

            for ($i = 0; $i < $endOfMonth; $i++) {
                $monthDays[] = $startOfMonth->addDay()->startOfDay()->copy();
            }
        }
        return $monthDays;
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
            ->select('al.idAuto', 'a.patente as patente', 'c.nombre as nombre', 'al.fechaInicio', 'al.fechaFin', 'al.total')->get();

        $autos = Auto::all('id', 'patente');

        $periodDaysPrevMonth = $data->map(function ($value) {
            $monthRentalStart = Carbon::createFromDate($value->fechaInicio);
            if ($monthRentalStart->isoFormat('Y') == $this->dt->isoFormat('Y')) {
                $daysPrevMonth = array();
                if ($monthRentalStart->isoFormat('M') == $this->dt->isoFormat('M') - 1) {
                    $startDate = Carbon::parse($value->fechaInicio);
                    $endDate = Carbon::parse($value->fechaFin);
                    if ($startDate->endOfMonth() < $endDate) {
                        $daysNextMonth = $startDate->endOfMonth()->diffInDays($endDate) + 1;
                        for ($i = 0; $i < $daysNextMonth; $i++) {
                            $daysPrevMonth[$i] = $i + 1;
                        }
                    }
                }
                $value->periodDaysPrevMonth = $daysPrevMonth;
            }
            return $value;
        });

        $filteredData = Arr::where($periodDaysPrevMonth->all(), function ($value, $key) {
            $monthRentalStart = Carbon::createFromDate($value->fechaInicio);
            $monthRentalEnd = Carbon::createFromDate($value->fechaFin);

            return $monthRentalStart->isoFormat('M/Y') == $this->dt->isoFormat('M/Y') || $monthRentalEnd->isoFormat('M/Y') == $this->dt->isoFormat('M/Y');
        });

        $processedData = [];
        foreach ($filteredData as $data) {
            $startDate = Carbon::parse($data->fechaInicio);
            $endDate = Carbon::parse($data->fechaFin);
            $periodDays = [];
            $periodDaysNextMonth = [];

            $diffInDays = $endDate->diffInDays($startDate);
            if ($startDate->isoFormat('M') == $this->dt->isoFormat('M')) {
                $periodDays[] = $startDate->copy()->isoFormat('D');
                $startDateDay = $startDate->isoFormat('D');
                $endDateDay = $startDate->isoFormat('D') + $diffInDays;
            } else {
                $periodDays[] = $endDate->copy()->startOfMonth()->isoFormat('D');
                $startDatePrevMonth = $endDate->copy()->startOfMonth();
                $startDateDay = $endDate->copy()->startOfMonth()->isoFormat('D');
                $endDateDay = $endDate->isoFormat('D');
            }
            $startDateInitial = $startDate->isoFormat('M');

            for ($i = $startDateDay; $i < $endDateDay; $i++) {
                if ($startDate->isoFormat('D') != $this->dt->endOfMonth()->isoFormat('D') && $startDate->isoFormat('M') != $startDateInitial + 1) {
                    if ($startDate->isoFormat('M') == $this->dt->isoFormat('M')) {
                        $periodDays[] = $startDate->addDay()->copy()->isoFormat('D');
                    } else {
                        $periodDays[] = $startDatePrevMonth->addDay()->copy()->isoFormat('D');
                    }
                } else {
                    $periodDaysNextMonth[] = $startDate->addDay()->copy()->startOfDay()->isoFormat('D');
                }
            }
            $processedData[] = collect($data)->merge(['periodDays' => $periodDays, 'periodDaysNextMonth' => $periodDaysNextMonth, 'periodDaysPrevMonth' => $this->periodDaysPrevMonth])->all();
        }
        $groupedPatente = collect($processedData)->groupBy('patente')->all();
        foreach ($autos as $auto) {
            $groupedPatente = Arr::add($groupedPatente, $auto->patente, []);
        }

        return $groupedPatente;
    }
}
