@php
    $gastosTabs = [
        ['route' => 'gastos.seguros', 'label' => 'Seguros'],
        ['route' => 'gastos.polizas', 'label' => 'Pólizas'],
        ['route' => 'gastos.mantenimientos', 'label' => 'Mantenimientos'],
        ['route' => 'gastos.rto', 'label' => 'RTO'],
    ];
@endphp

<div class="mb-4 flex gap-1 border-b border-gray-200 dark:border-gray-800">
    @foreach ($gastosTabs as $tab)
        <a
            href="{{ route($tab['route']) }}" wire:navigate
            class="border-b-2 px-4 py-2 text-sm font-medium transition-colors {{ request()->routeIs($tab['route']) ? 'border-amber-500 text-amber-600 dark:text-amber-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}"
        >
            {{ $tab['label'] }}
        </a>
    @endforeach
</div>
