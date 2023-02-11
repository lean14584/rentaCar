<div class="container-flud py-2">
    <div class="row mb-2">
        <div class="card w-100 shadow-sm">
            <div class="d-flex w-100 justify-content-center pt-2">
                {{-- Select Year --}}
                <div class="form-group mr-5">
                    <label class="text-black font-weight-bold" for="exampleFormControlSelect1">Seleccionar Año</label>
                    <select wire:model="selectedYear" class="form-control" id="selectYear">
                        <option></option>
                        @foreach ($years as $year)
                        <option value={{$year}}>{{$year}}</option>
                        @endforeach
                    </select>
                </div>
                @if ($selectedYear)
                {{-- Select Month --}}
                <div class="form-group">
                    <label class="text-black font-weight-bold" for="exampleFormControlSelect1">Seleccionar Mes</label>
                    <select wire:model="selectedMonth" class="form-control" id="selectMonth">
                        <option></option>
                        @foreach ($months as $month)
                        <option value="{{$month->month}}">{{ucfirst($month->monthName)}}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>
        </div>
    </div>
    {{-- Table --}}
    <div class="row">
        @if ($selectedMonth)
        <div class="card w-100 shadow-sm">
            <div class="d-flex w-100 justify-content-center pt-2">
                <table class="table tabla-bordes">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Patente</th>
                            @foreach ($daysInMonth as $day)
                            <th scope="col">{{$day->isoFormat('dd')[0]}}</th>
                            @endforeach
                            <th scope="col">Total</th>
                        </tr>
                        <tr>
                            <th scope="col"></th>
                            @foreach ($daysInMonth as $day)
                            <th scope="col">{{$day->isoFormat('D')}}</th>
                            @endforeach
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rentals as $key => $rental)
                        <tr id={{$key}}>
                            <th class="text-center align-middle p-0">{{$key}}</th>
                          
                            @foreach ($daysInMonth as $day)
                            <td id={{$key.'-'.$day->isoFormat('D')}}></td>
                            @endforeach

                            <th id={{$key.'-total'}} class="align-bottom">$0</th>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
    <script>
        document.addEventListener("livewire:load", function () {
            @this.on(
                "rentalAdded",
                (rentals, patentesColors, patentesTotal, patentesSubTotal) => {
                    let element;
                    let patentesSubTot;

                    Object.entries(rentals).forEach(([key, patentRental]) => {
                        patentRental.forEach(rental => {
                            rental.periodDays.forEach(day => {
                                let patente = `${rental.patente}-${day}`
                                element = document.getElementById(patente);
                                element.setAttribute("tabindex", "0");
                                element.className += ` rotated font-weight-bold text-center align-middle p-0 ${patentesColors[patente]} text-white`;
                                element.style.height += "15%";                        
                                element.textContent = patentesSubTotal[patente];

                                if (patentesSubTotal[patente] != null) {
                                    patentesSubTot = patentesSubTotal;
                                }
                                new bootstrap.Popover(element, {
                                    html: true,
                                    trigger: 'focus',
                                    sanitize: true,
                                    title: rental.patente,
                                    content: 
                                        `
                                        <strong>Nombre:</strong> ${rental.nombre}<br>
                                        <strong>Alquiler:</strong> ${rental.fechaInicio} hasta ${rental.fechaFin}<br>
                                        <strong>Total:</strong> $${rental.total}
                                        `
                                })
                            })
                        })
                    });

                    const patentesIdTotal = Object.keys(patentesTotal);
                    let elementTotal;
                    patentesIdTotal.forEach((patente, key) => {
                        elementTotal = document.getElementById(patente + "-total");
                        elementTotal.textContent = "$" + patentesTotal[patente];
                    });               
                }            
            );
        });
    </script>
</div>