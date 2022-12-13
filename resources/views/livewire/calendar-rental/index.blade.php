<div class="container-flud py-2">
    <div class="row mb-2">
        <div class="card w-100 shadow-sm">
            {{-- {{$selectedMonth}} --}}
            <div x-data="{nombre: 'Brian'}">
                <p x-text="nombre"></p>
            </div>
            <div class="d-flex w-100 justify-content-center pt-2">
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
    {{-- Tabla --}}
    <div class="row">


        {{-- {{
        $dt?->format('F Y'),
        }} --}}
        {{-- @if ($daysInMonth)
        @foreach ($daysInMonth as $day)
        <div class="d-flex d-inline-flex w-50">{{$day->isoFormat('D')}}</div>
        <div class="d-flex d-inline-flex w-50">{{$day->isoFormat('dd')}}</div>
        @endforeach
        @endif --}}
        @if ($selectedMonth)
        {{-- {{dump($rentals)}} --}}

        {{-- @foreach ($rentals as $rental)
        {{dd($rental)}}
        @foreach ($rental as $rent)
        {{$rent->patente}}
        @endforeach
        @endforeach --}}
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

                            {{-- <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="rotated font-weight-bold text-center align-middle p-0 bg-danger text-white"
                                style="height: 10%">$2000</td>
                            <td class="bg-danger"></td>
                            <td class="bg-danger"></td>
                            <td class="bg-danger"></td>
                            <td class="bg-danger"></td>
                            <td class="bg-danger"></td>
                            <td class="bg-danger"></td>
                            <td class="bg-danger"></td>
                            <td class="bg-danger"></td>
                            <td class="bg-danger"></td>
                            <td class="bg-danger"></td>
                            <td class="bg-danger"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td> --}}
                            <th class="align-bottom">${{collect($rental)->sum('total')}}</th>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
    <script>
        // let data = null;
        document.addEventListener('livewire:load', function () {
            // let rental = []
            // @this.on('updatedSelectedMonth', () => {
                    // console.log(document.getElementById('DLE-155-2'))

                // data = @this.rentals;
  
                // data.then((dat) => console.log(dat))
                // for (let i = 0; i < data.length; i++) {
                //     rental = data[i];                    
                // }
                // console.log(data['OLD-365'][0]['periodDays']);
                // data.forEach(element => console.log(element))
            // })
            @this.on('rentalAdded', (rentals, daysInMonth, patentes, patentesColors, patentesTotal) => {
                console.log(rentals);
                //    console.log(rentals['OLD-365'].length);
                // console.log(daysInMonth.length);


                // element.className += " rotated font-weight-bold text-center align-middle p-0 bg-danger text-white";
                // console.log(patentes);
                let element
                patentes.forEach((patente) => {
                    // console.log(patentesTotal);
                    element = document.getElementById(patente);
                    // console.log(element);
                    element.className += ` rotated font-weight-bold text-center align-middle p-0 ${patentesColors[patente]} text-white`;
                    element.style.height += "15%";
                    element.textContent = patentesTotal[patente]
                });
                
                // patentes.forEach(patente => {
                //     element = document.getElementById(patente);
                //     element.className += " rotated font-weight-bold text-center align-middle p-0 bg-danger text-white";                  
                // });

                // console.log(patentes.keys());
                // const rental = rentals['OLD-365'].map((alq) => {
                //     console.log(alq);
                // })
                // console.log(rent);
                // if (data) {
                //     console.log(Array.from([1, 2, 3], x => x + x));
                // }
                // data.forEach(element => {
                //     console.log(element);
                // });
            })
        })
        // Livewire.emit('postAdded')
            
    </script>
</div>