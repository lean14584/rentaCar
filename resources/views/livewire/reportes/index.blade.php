<div>
    <x-page-header title="Reportes" subtitle="Indicadores clave de la flota, la facturación y el mantenimiento" icon="chart-bar" />

    @php
        $tabs = [
            'devolver' => 'Autos a devolver',
            'seguros' => 'Seguros por vencer',
            'polizas' => 'Pólizas por vencer',
            'rto' => 'RTO por vencer',
            'ganancias' => 'Ganancias por auto',
            'alquilados' => 'Autos más alquilados',
            'mantenimientos' => 'Mantenimientos por finalizar',
            'clientes_saldo' => 'Clientes con saldo',
            'proveedores_saldo' => 'Proveedores con saldo',
            'service' => 'Autos próximos a service',
        ];
        $inputClass = 'px-4 py-3 text-left';
    @endphp

    <div class="mb-4 flex flex-wrap items-end gap-3">
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha desde</label>
            <input type="date" wire:model.live="desde" class="rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-100">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha hasta</label>
            <input type="date" wire:model.live="hasta" class="rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-100">
        </div>
    </div>

    <div class="mb-4 flex flex-wrap gap-1 border-b border-gray-200 dark:border-gray-800">
        @foreach ($tabs as $key => $label)
            <button
                type="button" wire:click="$set('tab', '{{ $key }}')"
                class="border-b-2 px-4 py-2 text-sm font-medium transition-colors {{ $tab === $key ? 'border-amber-500 text-amber-600 dark:text-amber-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}"
            >
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Desktop: tabla --}}
    <div class="hidden overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm sm:block dark:border-gray-800 dark:bg-gray-900">
        <div class="overflow-x-auto">

            @if ($tab === 'devolver')
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-800/50 dark:text-gray-400">
                        <tr>
                            <th class="{{ $inputClass }}">Auto</th>
                            <th class="{{ $inputClass }}">Cliente</th>
                            <th class="{{ $inputClass }}">Fecha de devolución</th>
                            <th class="{{ $inputClass }}">Estado</th>
                            <th class="{{ $inputClass }}">Saldo pendiente</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($this->autosADevolver as $alquiler)
                            @php $dias = now()->startOfDay()->diffInDays($alquiler->fecha_fin->copy()->startOfDay(), false); @endphp
                            <tr wire:key="devolver-{{ $alquiler->id }}">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $alquiler->auto->patente }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $alquiler->cliente->nombre }}</td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $alquiler->fecha_fin->format('d/m/Y') }}</td>
                                <td class="px-4 py-3">
                                    @if ($dias < 0)
                                        <span class="inline-flex rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-semibold text-rose-700 dark:bg-rose-500/10 dark:text-rose-400">Vencido hace {{ abs($dias) }}d</span>
                                    @elseif ($dias === 0)
                                        <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">Hoy</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-400">En {{ $dias }}d</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 {{ (float) $alquiler->saldo > 0 ? 'font-semibold text-amber-600 dark:text-amber-400' : 'text-gray-400' }}">$ {{ number_format((float) $alquiler->saldo, 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-10 text-center text-gray-400">No hay alquileres activos con devolución en el período seleccionado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @endif

            @if ($tab === 'seguros')
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-800/50 dark:text-gray-400">
                        <tr>
                            <th class="{{ $inputClass }}">Auto</th>
                            <th class="{{ $inputClass }}">Aseguradora</th>
                            <th class="{{ $inputClass }}">Vigencia hasta</th>
                            <th class="{{ $inputClass }}">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($this->segurosPorVencer as $seguro)
                            <tr wire:key="seguro-{{ $seguro->id }}">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $seguro->auto->patente }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $seguro->nombre }}</td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $seguro->vigencia_hasta->format('d/m/Y') }}</td>
                                <td class="px-4 py-3">
                                    @if ($seguro->vigencia_hasta->isPast())
                                        <span class="inline-flex rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-semibold text-rose-700 dark:bg-rose-500/10 dark:text-rose-400">Vencido</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">Por vencer</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-10 text-center text-gray-400">No hay seguros con vencimiento en el período seleccionado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @endif

            @if ($tab === 'polizas')
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-800/50 dark:text-gray-400">
                        <tr>
                            <th class="{{ $inputClass }}">Auto</th>
                            <th class="{{ $inputClass }}">Empresa</th>
                            <th class="{{ $inputClass }}">Vigencia hasta</th>
                            <th class="{{ $inputClass }}">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($this->polizasPorVencer as $poliza)
                            <tr wire:key="poliza-{{ $poliza->id }}">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $poliza->auto->patente }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $poliza->empresa }}</td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $poliza->vigencia_hasta->format('d/m/Y') }}</td>
                                <td class="px-4 py-3">
                                    @if ($poliza->vigencia_hasta->isPast())
                                        <span class="inline-flex rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-semibold text-rose-700 dark:bg-rose-500/10 dark:text-rose-400">Vencida</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">Por vencer</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-10 text-center text-gray-400">No hay pólizas con vencimiento en el período seleccionado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @endif

            @if ($tab === 'rto')
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-800/50 dark:text-gray-400">
                        <tr>
                            <th class="{{ $inputClass }}">Auto</th>
                            <th class="{{ $inputClass }}">Empresa</th>
                            <th class="{{ $inputClass }}">Vigencia hasta</th>
                            <th class="{{ $inputClass }}">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($this->rtoPorVencer as $rto)
                            <tr wire:key="rto-{{ $rto->id }}">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $rto->auto->patente }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $rto->empresa }}</td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $rto->fecha_fin->format('d/m/Y') }}</td>
                                <td class="px-4 py-3">
                                    @if ($rto->fecha_fin->isPast())
                                        <span class="inline-flex rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-semibold text-rose-700 dark:bg-rose-500/10 dark:text-rose-400">Vencida</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">Por vencer</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-10 text-center text-gray-400">No hay RTO con vencimiento en el período seleccionado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @endif

            @if ($tab === 'ganancias')
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-800/50 dark:text-gray-400">
                        <tr>
                            <th class="{{ $inputClass }}">Auto</th>
                            <th class="{{ $inputClass }}">Ingresos</th>
                            <th class="{{ $inputClass }}">Egresos</th>
                            <th class="{{ $inputClass }}">Ganancia</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($this->gananciasPorAuto as $auto)
                            <tr wire:key="ganancia-{{ $auto->id }}">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $auto->patente }}</td>
                                <td class="px-4 py-3 text-emerald-600 dark:text-emerald-400">$ {{ number_format($auto->ingresos, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-rose-600 dark:text-rose-400">$ {{ number_format($auto->egresos, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 font-semibold {{ $auto->ganancia >= 0 ? 'text-gray-900 dark:text-gray-100' : 'text-rose-600 dark:text-rose-400' }}">$ {{ number_format($auto->ganancia, 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-10 text-center text-gray-400">No hay autos cargados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @endif

            @if ($tab === 'alquilados')
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-800/50 dark:text-gray-400">
                        <tr>
                            <th class="{{ $inputClass }}">Auto</th>
                            <th class="{{ $inputClass }}">Marca</th>
                            <th class="{{ $inputClass }}">Cantidad de alquileres</th>
                            <th class="{{ $inputClass }}">Total facturado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($this->autosMasAlquilados as $auto)
                            <tr wire:key="alquilado-{{ $auto->id }}">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $auto->patente }}</td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $auto->marca->nombre }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $auto->alquileres_count }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">$ {{ number_format((float) $auto->total_facturado, 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-10 text-center text-gray-400">Todavía no hay alquileres cargados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @endif

            @if ($tab === 'mantenimientos')
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-800/50 dark:text-gray-400">
                        <tr>
                            <th class="{{ $inputClass }}">Auto</th>
                            <th class="{{ $inputClass }}">Tipo</th>
                            <th class="{{ $inputClass }}">Proveedor</th>
                            <th class="{{ $inputClass }}">Desde</th>
                            <th class="{{ $inputClass }}">Hasta (esperada)</th>
                            <th class="{{ $inputClass }}">Valor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($this->mantenimientosPorFinalizar as $mantenimiento)
                            <tr wire:key="mant-{{ $mantenimiento->id }}">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $mantenimiento->auto->patente }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $mantenimiento->nombre }}</td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $mantenimiento->proveedor->nombre }}</td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $mantenimiento->fecha_inicio->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $mantenimiento->fecha_fin?->format('d/m/Y') ?? '—' }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">$ {{ number_format((float) $mantenimiento->valor, 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-10 text-center text-gray-400">No hay mantenimientos en curso iniciados en el período seleccionado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @endif

            @if ($tab === 'clientes_saldo')
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-800/50 dark:text-gray-400">
                        <tr>
                            <th class="{{ $inputClass }}">Cliente</th>
                            <th class="{{ $inputClass }}">Cargos</th>
                            <th class="{{ $inputClass }}">Pagos</th>
                            <th class="{{ $inputClass }}">Saldo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($this->clientesConSaldo as $cliente)
                            <tr wire:key="cliente-saldo-{{ $cliente->id }}">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $cliente->nombre }}</td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">$ {{ number_format((float) $cliente->cargos, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">$ {{ number_format((float) $cliente->pagos, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 font-semibold text-rose-600 dark:text-rose-400">$ {{ number_format($cliente->saldo, 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-10 text-center text-gray-400">Ningún cliente tiene saldo pendiente.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @endif

            @if ($tab === 'proveedores_saldo')
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-800/50 dark:text-gray-400">
                        <tr>
                            <th class="{{ $inputClass }}">Proveedor</th>
                            <th class="{{ $inputClass }}">Cargos</th>
                            <th class="{{ $inputClass }}">Pagos</th>
                            <th class="{{ $inputClass }}">Saldo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($this->proveedoresConSaldo as $proveedor)
                            <tr wire:key="proveedor-saldo-{{ $proveedor->id }}">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $proveedor->nombre }}</td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">$ {{ number_format((float) $proveedor->cargos, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">$ {{ number_format((float) $proveedor->pagos, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 font-semibold text-rose-600 dark:text-rose-400">$ {{ number_format($proveedor->saldo, 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-10 text-center text-gray-400">Ningún proveedor tiene saldo pendiente.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @endif

            @if ($tab === 'service')
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-800/50 dark:text-gray-400">
                        <tr>
                            <th class="{{ $inputClass }}">Auto</th>
                            <th class="{{ $inputClass }}">Km actuales</th>
                            <th class="{{ $inputClass }}">Km próx. cambio de aceite</th>
                            <th class="{{ $inputClass }}">Km restantes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($this->autosProximosService as $auto)
                            <tr wire:key="service-{{ $auto->id }}">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $auto->patente }}</td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ number_format($auto->kilometros) }}</td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ number_format($auto->cambio_aceite) }}</td>
                                <td class="px-4 py-3 font-semibold {{ $auto->km_restantes <= 0 ? 'text-rose-600 dark:text-rose-400' : 'text-amber-600 dark:text-amber-400' }}">
                                    {{ $auto->km_restantes <= 0 ? 'Vencido hace '.number_format(abs($auto->km_restantes)).'km' : number_format($auto->km_restantes).'km' }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-10 text-center text-gray-400">Ningún auto está cerca de su próximo service.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @endif

        </div>
    </div>

    {{-- Mobile: cards --}}
    <div class="space-y-3 sm:hidden">
        @if ($tab === 'devolver')
            @forelse ($this->autosADevolver as $alquiler)
                @php $dias = now()->startOfDay()->diffInDays($alquiler->fecha_fin->copy()->startOfDay(), false); @endphp
                <div wire:key="devolver-card-{{ $alquiler->id }}" class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="truncate font-semibold text-gray-900 dark:text-gray-100">{{ $alquiler->auto->patente }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $alquiler->cliente->nombre }}</p>
                        </div>
                        @if ($dias < 0)
                            <span class="inline-flex shrink-0 rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-semibold text-rose-700 dark:bg-rose-500/10 dark:text-rose-400">Vencido hace {{ abs($dias) }}d</span>
                        @elseif ($dias === 0)
                            <span class="inline-flex shrink-0 rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">Hoy</span>
                        @else
                            <span class="inline-flex shrink-0 rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-400">En {{ $dias }}d</span>
                        @endif
                    </div>
                    <div class="mt-2 flex items-center justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Devuelve {{ $alquiler->fecha_fin->format('d/m/Y') }}</span>
                        <span class="{{ (float) $alquiler->saldo > 0 ? 'font-semibold text-amber-600 dark:text-amber-400' : 'text-gray-400' }}">Saldo: $ {{ number_format((float) $alquiler->saldo, 2, ',', '.') }}</span>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-gray-200 bg-white p-6 text-center text-gray-400 dark:border-gray-800 dark:bg-gray-900">No hay alquileres activos con devolución en el período seleccionado.</div>
            @endforelse
        @endif

        @if ($tab === 'seguros')
            @forelse ($this->segurosPorVencer as $seguro)
                <div wire:key="seguro-card-{{ $seguro->id }}" class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="truncate font-semibold text-gray-900 dark:text-gray-100">{{ $seguro->auto->patente }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $seguro->nombre }}</p>
                        </div>
                        @if ($seguro->vigencia_hasta->isPast())
                            <span class="inline-flex shrink-0 rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-semibold text-rose-700 dark:bg-rose-500/10 dark:text-rose-400">Vencido</span>
                        @else
                            <span class="inline-flex shrink-0 rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">Por vencer</span>
                        @endif
                    </div>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Vigencia hasta {{ $seguro->vigencia_hasta->format('d/m/Y') }}</p>
                </div>
            @empty
                <div class="rounded-2xl border border-gray-200 bg-white p-6 text-center text-gray-400 dark:border-gray-800 dark:bg-gray-900">No hay seguros con vencimiento en el período seleccionado.</div>
            @endforelse
        @endif

        @if ($tab === 'polizas')
            @forelse ($this->polizasPorVencer as $poliza)
                <div wire:key="poliza-card-{{ $poliza->id }}" class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="truncate font-semibold text-gray-900 dark:text-gray-100">{{ $poliza->auto->patente }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $poliza->empresa }}</p>
                        </div>
                        @if ($poliza->vigencia_hasta->isPast())
                            <span class="inline-flex shrink-0 rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-semibold text-rose-700 dark:bg-rose-500/10 dark:text-rose-400">Vencida</span>
                        @else
                            <span class="inline-flex shrink-0 rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">Por vencer</span>
                        @endif
                    </div>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Vigencia hasta {{ $poliza->vigencia_hasta->format('d/m/Y') }}</p>
                </div>
            @empty
                <div class="rounded-2xl border border-gray-200 bg-white p-6 text-center text-gray-400 dark:border-gray-800 dark:bg-gray-900">No hay pólizas con vencimiento en el período seleccionado.</div>
            @endforelse
        @endif

        @if ($tab === 'rto')
            @forelse ($this->rtoPorVencer as $rto)
                <div wire:key="rto-card-{{ $rto->id }}" class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="truncate font-semibold text-gray-900 dark:text-gray-100">{{ $rto->auto->patente }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $rto->empresa }}</p>
                        </div>
                        @if ($rto->fecha_fin->isPast())
                            <span class="inline-flex shrink-0 rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-semibold text-rose-700 dark:bg-rose-500/10 dark:text-rose-400">Vencida</span>
                        @else
                            <span class="inline-flex shrink-0 rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">Por vencer</span>
                        @endif
                    </div>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Vigencia hasta {{ $rto->fecha_fin->format('d/m/Y') }}</p>
                </div>
            @empty
                <div class="rounded-2xl border border-gray-200 bg-white p-6 text-center text-gray-400 dark:border-gray-800 dark:bg-gray-900">No hay RTO con vencimiento en el período seleccionado.</div>
            @endforelse
        @endif

        @if ($tab === 'ganancias')
            @forelse ($this->gananciasPorAuto as $auto)
                <div wire:key="ganancia-card-{{ $auto->id }}" class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $auto->patente }}</p>
                    <div class="mt-2 flex items-center justify-between text-sm">
                        <span class="text-emerald-600 dark:text-emerald-400">Ingresos: $ {{ number_format($auto->ingresos, 2, ',', '.') }}</span>
                        <span class="text-rose-600 dark:text-rose-400">Egresos: $ {{ number_format($auto->egresos, 2, ',', '.') }}</span>
                    </div>
                    <p class="mt-1 text-right font-semibold {{ $auto->ganancia >= 0 ? 'text-gray-900 dark:text-gray-100' : 'text-rose-600 dark:text-rose-400' }}">
                        Ganancia: $ {{ number_format($auto->ganancia, 2, ',', '.') }}
                    </p>
                </div>
            @empty
                <div class="rounded-2xl border border-gray-200 bg-white p-6 text-center text-gray-400 dark:border-gray-800 dark:bg-gray-900">No hay autos cargados.</div>
            @endforelse
        @endif

        @if ($tab === 'alquilados')
            @forelse ($this->autosMasAlquilados as $auto)
                <div wire:key="alquilado-card-{{ $auto->id }}" class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $auto->patente }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $auto->marca->nombre }}</p>
                    <div class="mt-2 flex items-center justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">{{ $auto->alquileres_count }} {{ Str::plural('alquiler', $auto->alquileres_count) }}</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">$ {{ number_format((float) $auto->total_facturado, 2, ',', '.') }}</span>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-gray-200 bg-white p-6 text-center text-gray-400 dark:border-gray-800 dark:bg-gray-900">Todavía no hay alquileres cargados.</div>
            @endforelse
        @endif

        @if ($tab === 'mantenimientos')
            @forelse ($this->mantenimientosPorFinalizar as $mantenimiento)
                <div wire:key="mant-card-{{ $mantenimiento->id }}" class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="truncate font-semibold text-gray-900 dark:text-gray-100">{{ $mantenimiento->auto->patente }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $mantenimiento->nombre }} · {{ $mantenimiento->proveedor->nombre }}</p>
                        </div>
                        <p class="shrink-0 font-medium text-gray-900 dark:text-gray-100">$ {{ number_format((float) $mantenimiento->valor, 2, ',', '.') }}</p>
                    </div>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $mantenimiento->fecha_inicio->format('d/m/Y') }} — {{ $mantenimiento->fecha_fin?->format('d/m/Y') ?? '—' }}</p>
                </div>
            @empty
                <div class="rounded-2xl border border-gray-200 bg-white p-6 text-center text-gray-400 dark:border-gray-800 dark:bg-gray-900">No hay mantenimientos en curso iniciados en el período seleccionado.</div>
            @endforelse
        @endif

        @if ($tab === 'clientes_saldo')
            @forelse ($this->clientesConSaldo as $cliente)
                <div wire:key="cliente-saldo-card-{{ $cliente->id }}" class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $cliente->nombre }}</p>
                    <div class="mt-2 flex items-center justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Cargos: $ {{ number_format((float) $cliente->cargos, 2, ',', '.') }} · Pagos: $ {{ number_format((float) $cliente->pagos, 2, ',', '.') }}</span>
                    </div>
                    <p class="mt-1 text-right font-semibold text-rose-600 dark:text-rose-400">Saldo: $ {{ number_format($cliente->saldo, 2, ',', '.') }}</p>
                </div>
            @empty
                <div class="rounded-2xl border border-gray-200 bg-white p-6 text-center text-gray-400 dark:border-gray-800 dark:bg-gray-900">Ningún cliente tiene saldo pendiente.</div>
            @endforelse
        @endif

        @if ($tab === 'proveedores_saldo')
            @forelse ($this->proveedoresConSaldo as $proveedor)
                <div wire:key="proveedor-saldo-card-{{ $proveedor->id }}" class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $proveedor->nombre }}</p>
                    <div class="mt-2 flex items-center justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Cargos: $ {{ number_format((float) $proveedor->cargos, 2, ',', '.') }} · Pagos: $ {{ number_format((float) $proveedor->pagos, 2, ',', '.') }}</span>
                    </div>
                    <p class="mt-1 text-right font-semibold text-rose-600 dark:text-rose-400">Saldo: $ {{ number_format($proveedor->saldo, 2, ',', '.') }}</p>
                </div>
            @empty
                <div class="rounded-2xl border border-gray-200 bg-white p-6 text-center text-gray-400 dark:border-gray-800 dark:bg-gray-900">Ningún proveedor tiene saldo pendiente.</div>
            @endforelse
        @endif

        @if ($tab === 'service')
            @forelse ($this->autosProximosService as $auto)
                <div wire:key="service-card-{{ $auto->id }}" class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $auto->patente }}</p>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ number_format($auto->kilometros) }}km actuales · próximo a los {{ number_format($auto->cambio_aceite) }}km</p>
                    <p class="mt-1 text-right font-semibold {{ $auto->km_restantes <= 0 ? 'text-rose-600 dark:text-rose-400' : 'text-amber-600 dark:text-amber-400' }}">
                        {{ $auto->km_restantes <= 0 ? 'Vencido hace '.number_format(abs($auto->km_restantes)).'km' : 'Faltan '.number_format($auto->km_restantes).'km' }}
                    </p>
                </div>
            @empty
                <div class="rounded-2xl border border-gray-200 bg-white p-6 text-center text-gray-400 dark:border-gray-800 dark:bg-gray-900">Ningún auto está cerca de su próximo service.</div>
            @endforelse
        @endif
    </div>
</div>
