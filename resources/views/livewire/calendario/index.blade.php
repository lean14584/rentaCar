<div>
    <x-page-header title="Calendario" subtitle="Ocupación de la flota mes a mes, auto por auto" icon="calendar-days" />

    <x-flash-messages />

    <div class="mb-4 flex flex-wrap items-end gap-3">
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Año</label>
            <select wire:model.live="anio" class="rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-100">
                @foreach ($this->anios as $a)
                    <option value="{{ $a }}">{{ $a }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Mes</label>
            <select wire:model.live="mes" class="rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-100">
                @foreach (\App\Livewire\Calendario\Index::MESES as $numero => $nombre)
                    <option value="{{ $numero }}">{{ $nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <div class="overflow-x-auto">
            <table class="w-full text-center text-xs">
                <thead class="bg-gray-50 text-gray-500 dark:bg-gray-800/50 dark:text-gray-400">
                    <tr>
                        <th class="sticky left-0 z-10 bg-gray-50 px-3 py-2 text-left whitespace-nowrap dark:bg-gray-800/50">Auto</th>
                        @foreach ($this->diasDelMes as $dia)
                            <th class="w-9 px-1 py-2 {{ $dia->isWeekend() ? 'bg-gray-100 dark:bg-gray-800/80' : '' }}">{{ $dia->day }}</th>
                        @endforeach
                        <th class="px-3 py-2 whitespace-nowrap">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($this->filas as $fila)
                        <tr>
                            <td class="sticky left-0 z-10 bg-white px-3 py-2 text-left whitespace-nowrap dark:bg-gray-900">
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $fila['patente'] }}</div>
                                <div class="text-[11px] text-gray-400">{{ $fila['marca'] }}</div>
                            </td>

                            @foreach ($this->diasDelMes as $dia)
                                @php $celda = $fila['celdas'][$dia->day] ?? null; @endphp
                                <td
                                    @if ($celda) title="{{ $celda['cliente'] }}" @endif
                                    class="h-10 w-9 px-0.5 align-middle text-[10px] font-semibold leading-tight text-white {{ $celda['color'] ?? '' }}"
                                >
                                    {{ $celda && $celda['importe'] ? number_format($celda['importe'], 0, ',', '.') : '' }}
                                </td>
                            @endforeach

                            <td class="px-3 py-2 font-semibold whitespace-nowrap text-gray-900 dark:text-gray-100">
                                $ {{ number_format($fila['total'], 2, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($this->diasDelMes) + 2 }}" class="px-4 py-10 text-gray-400">
                                No hay alquileres cargados para este mes.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
