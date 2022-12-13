<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CalendarRentalController extends Component
{
    public $msj;
    public $data;
    // public $startsAt;
    // public $endsAt;
    public $initialYear;
    public $initialMonth;
    // public $months;

    // public $weekStartsAt;
    // public $weekEndsAt;

    public $selectedMonth;
    public $selectedYear;

    protected $dt; //dataTime
    public $daysInMonth;
    public $rentals;
    public $day;
    public $colors = [
        "bg-primary",
        "bg-secondary",
        "bg-success",
        "bg-danger",
        "bg-warning",
        "bg-info"
    ];

    protected $listeners;

    public function mount(
        $initialYear = null,
        $initialMonth = null,
        $years = null,
        // $weekStartsAt = null,
    ) {
        // $this->weekStartsAt = $weekStartsAt ?? Carbon::SUNDAY;
        // $this->weekEndsAt = $this->weekStartsAt == Carbon::SUNDAY
        //     ? Carbon::SATURDAY
        //     : collect([0, 1, 2, 3, 4, 5, 6])->get($this->weekStartsAt + 6 - 7);

        $this->initialYear = $initialYear ?? Carbon::today()->year;
        $this->initialMonth = $initialMonth ?? Carbon::today()->month;

        // $meses = Carbon::parse(Carbon::now()->startOfYear())->monthsUntil(Carbon::now()->endOfYear());

        // foreach ($meses as $key => $mes) {
        //     dump($mes);
        // }

        // $this->startsAt = Carbon::createFromDate($initialYear, $initialMonth, 1)->startOfDay();
        // $this->endsAt = $this->startsAt->clone()->endOfMonth()->startOfDay();
    }

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
        // dd($this->dt);
        // $this->daysInMonth = $this->dt->daysInMonth;
        $this->daysInMonth = $this->getCurrentMonthDays($this->dt);
        // dd($monthDays);
        $this->rentals = $this->rentals();

        $patentes = [];
        $patentesColors = [];
        $patentesTotal = [];
        $test = [];

        foreach ($this->rentals as $key => $rental) {
            // dd($rental);
            foreach ($rental as $key2 => $values) {
                $randColor = array_rand($this->colors, 1);
                
                foreach ($values['periodDays'] as $key => $value) {
                    array_push($patentes, $values['patente'] . '-' . $value);
                    $keys = $values['patente'] . '-' . $value;
                    if ($key == 0) {                        
                        $patentesTotal = Arr::add($patentesTotal, $keys, $values['total']);
                    }
                    $patentesColors = Arr::add($patentesColors, $keys, $this->colors[$randColor]);
                    array_push($test, $key. '-' . $value);
                }
            }
        }

        // dump($patentes);
        // dump($patentesTotal);
        // dump($test);

        $daysInMonth = collect($this->daysInMonth)->map(function ($days) {
            return $days->isoFormat('D');
        });
        $this->emit("rentalAdded", $this->rentals, $daysInMonth, $patentes, $patentesColors, $patentesTotal);
    }

    // public function updatedSelectedMonth()
    // {
    //     // $this->rentals;
    //     $this->emit('rentalAdded', $this->rentals);
    //     // $this->emit("updatedSelectedMonth");
    // }

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
            ->select('al.*',  'a.patente as patente', 'c.nombre as nombre', 'al.total')->get();
        // ->where('fechaInicio', 'like', '%' . $this->search . '%')->paginate($this->pagination);
        // dd($this->dt->isoFormat('M/Y'));
        // dd($data);
        // $groupedPatente = $data->groupBy('patente');
        // dd($groupedPatente);
        $filteredData = Arr::where($data->all(), function ($value, $key) {
            $monthRental = Carbon::createFromDate($value->fechaInicio);
            // dump($monthRental->isoFormat('M/Y'));
            // dump($this->dt->isoFormat('M/Y'));
            return $monthRental->isoFormat('M/Y') == $this->dt->isoFormat('M/Y');
        });
        // dump($filteredData);
        // $period = Carbon::createFromDate($filteredData)
        $processedData = [];
        foreach ($filteredData as $key => $data) {
            $startDate = Carbon::parse($data->fechaInicio);
            // dd($startDate);
            // dd($startDate->isoFormat('D'));
            // dd($startDate);
            $endDate = Carbon::parse($data->fechaFin);
            // dd($endDate);

            $diffInDays = $endDate->diffInDays($startDate);
            // dd($diffInDays);
            $periodDays = [];
            $periodDaysNextMonth = [];

            $startDateDay = $startDate->isoFormat('D');
            $endDateDay = $startDate->isoFormat('D') + $diffInDays;
            $periodDays[] = $startDate->copy()->isoFormat('D');
            $startDateInitial = $startDate->isoFormat('M');
            for ($i = $startDateDay; $i < $endDateDay; $i++) {
                // dump($startDate->isoFormat('M') );
                // dump($endDate->isoFormat('D'));
                // dump($this->dt->endOfMonth()->isoFormat('D'));
                if ($startDate->isoFormat('D') != $this->dt->endOfMonth()->isoFormat('D') && $startDate->isoFormat('M') != $startDateInitial + 1) {
                    $periodDays[] = $startDate->addDay()->startOfDay()->copy()->isoFormat('D');
                } else {
                    $periodDaysNextMonth[] = $startDate->addDay()->startOfDay()->copy()->isoFormat('D');
                }            
                
            }
            // dump($periodDays);
            // dump($periodDaysNextMonth);
            // $processedData[] = collect($data)->put('periodDays', $periodDays)->all();
            $processedData[] = collect($data)->merge(['periodDays' => $periodDays, 'periodDaysNextMonth' => $periodDaysNextMonth])->all();
        }
        // dump($processedData);
        // dump(collect($processedData)->all());
        // $groupedPatente = collect($filteredData)->groupBy('patente');
        $groupedPatente = collect($processedData)->groupBy('patente')->all();
        // dump($groupedPatente);
        // dd(collect($filteredData)->sum('total'));

        // dd($data);
        return $groupedPatente;
    }
}
