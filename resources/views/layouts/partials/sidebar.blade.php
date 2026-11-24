@php
    // route() explota si la ruta no está registrada (pantallas que todavía
    // no se migraron desde la app vieja): se resuelve sin reventar, y el
    // item directamente no se muestra si la ruta no existe.
    $safeRoute = fn (string $name) => \Illuminate\Support\Facades\Route::has($name) ? route($name) : null;

    $navItems = [
        ['permiso' => 'dashboard', 'group' => null, 'pattern' => 'dashboard', 'href' => $safeRoute('dashboard'), 'label' => 'Dashboard', 'icon' => 'home'],

        ['permiso' => 'marcas', 'group' => 'Flota', 'pattern' => 'marcas.*', 'href' => $safeRoute('marcas.index'), 'label' => 'Marcas', 'icon' => 'tag'],
        ['permiso' => 'autos', 'group' => 'Flota', 'pattern' => 'autos.*', 'href' => $safeRoute('autos.index'), 'label' => 'Autos', 'icon' => 'truck'],

        ['permiso' => 'alquiler', 'group' => 'Alquileres', 'pattern' => 'alquiler.*', 'href' => $safeRoute('alquiler.index'), 'label' => 'Nuevo alquiler', 'icon' => 'key'],
        ['permiso' => 'alquiler-listado', 'group' => 'Alquileres', 'pattern' => 'alquiler-listado.*', 'href' => $safeRoute('alquiler-listado.index'), 'label' => 'Alquileres', 'icon' => 'clipboard-document-list'],
        ['permiso' => 'calendar-rental', 'group' => 'Alquileres', 'pattern' => 'calendar-rental', 'href' => $safeRoute('calendar-rental'), 'label' => 'Calendario', 'icon' => 'calendar-days'],

        ['permiso' => 'clientes', 'group' => 'Contactos', 'pattern' => 'clientes.*', 'href' => $safeRoute('clientes.index'), 'label' => 'Clientes', 'icon' => 'users'],
        ['permiso' => 'proveedores', 'group' => 'Contactos', 'pattern' => 'proveedores.*', 'href' => $safeRoute('proveedores.index'), 'label' => 'Proveedores', 'icon' => 'building-storefront'],

        ['permiso' => 'gastos', 'group' => 'Gastos', 'pattern' => 'gastos.*', 'href' => $safeRoute('gastos.index'), 'label' => 'Seguros, pólizas y service', 'icon' => 'wrench'],

        ['permiso' => 'caja', 'group' => 'Finanzas', 'pattern' => 'caja.*', 'href' => $safeRoute('caja.index'), 'label' => 'Caja', 'icon' => 'banknotes'],
        ['permiso' => 'reportes', 'group' => 'Finanzas', 'pattern' => 'reportes.*', 'href' => $safeRoute('reportes.index'), 'label' => 'Reportes', 'icon' => 'chart-bar'],

        ['permiso' => 'usuarios', 'group' => 'Equipo', 'pattern' => 'usuarios.*', 'href' => $safeRoute('usuarios.index'), 'label' => 'Usuarios', 'icon' => 'user-group'],
        ['permiso' => 'roles-permisos', 'group' => 'Equipo', 'pattern' => 'roles-permisos.*', 'href' => $safeRoute('roles-permisos.index'), 'label' => 'Roles y permisos', 'icon' => 'shield-check'],
    ];

    $user = auth()->user();
    $visibleItems = $user
        ? array_filter($navItems, fn ($item) => $item['href'] !== null && $user->can($item['permiso']))
        : [];

    $standaloneItems = array_filter($visibleItems, fn ($item) => empty($item['group']));
    $groupedItems = [];
    foreach ($visibleItems as $item) {
        if (! empty($item['group'])) {
            $groupedItems[$item['group']][] = $item;
        }
    }

    $currentPattern = fn (string $pattern) => request()->routeIs($pattern);
@endphp

<aside class="flex w-64 shrink-0 flex-col bg-gray-950 text-gray-300">
    <div class="flex items-center gap-2 px-5 py-5">
        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-500 text-white">
            <x-heroicon-o-truck class="h-4.5 w-4.5" />
        </span>
        <span class="text-base font-bold text-white">RentaCar</span>
    </div>

    <nav class="flex-1 space-y-5 overflow-y-auto px-3 pb-4">
        @foreach ($standaloneItems as $item)
            <a
                href="{{ $item['href'] }}" wire:navigate
                class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ $currentPattern($item['pattern']) ? 'bg-amber-500/15 text-amber-400' : 'text-gray-300 hover:bg-gray-800/60 hover:text-white' }}"
            >
                <x-dynamic-component :component="'heroicon-o-'.$item['icon']" class="h-4.5 w-4.5 shrink-0" />
                {{ $item['label'] }}
            </a>
        @endforeach

        @foreach ($groupedItems as $group => $items)
            <div>
                <div class="px-3 pb-1 text-xs font-semibold uppercase tracking-wider text-gray-500">{{ $group }}</div>
                <div class="space-y-0.5">
                    @foreach ($items as $item)
                        <a
                            href="{{ $item['href'] }}" wire:navigate
                            class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ $currentPattern($item['pattern']) ? 'bg-amber-500/15 text-amber-400' : 'text-gray-300 hover:bg-gray-800/60 hover:text-white' }}"
                        >
                            <x-dynamic-component :component="'heroicon-o-'.$item['icon']" class="h-4.5 w-4.5 shrink-0" />
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>

    <div class="border-t border-gray-800 px-3 py-3">
        <div class="flex items-center gap-2.5 px-2 py-1.5">
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-800 text-xs font-semibold text-gray-300">
                {{ Str::upper(Str::substr($user?->name, 0, 1)) }}
            </span>
            <div class="min-w-0 flex-1">
                <div class="truncate text-sm font-medium text-white">{{ $user?->name }}</div>
                <div class="truncate text-xs text-gray-500">{{ $user?->getRoleNames()->first() ?? '—' }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="mt-1 flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm font-medium text-gray-400 hover:bg-gray-800/60 hover:text-white">
                <x-heroicon-o-arrow-right-start-on-rectangle class="h-4.5 w-4.5" />
                Cerrar sesión
            </button>
        </form>
    </div>
</aside>
