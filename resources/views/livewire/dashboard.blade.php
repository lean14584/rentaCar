<div class="mx-auto max-w-6xl">
    <h1 class="mb-6 text-xl font-bold text-gray-900 dark:text-gray-100">Dashboard</h1>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">Autos disponibles</div>
            <div class="mt-1 text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $autosDisponibles }}</div>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">Autos alquilados</div>
            <div class="mt-1 text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $autosAlquilados }}</div>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">Clientes</div>
            <div class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $clientesTotal }}</div>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">Alquileres vigentes</div>
            <div class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $alquileresActivos }}</div>
        </div>
    </div>
</div>
