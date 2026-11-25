<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-gray-50 text-gray-900 dark:bg-gray-950 dark:text-gray-100" x-data="{ sidebarOpen: false }">
    <div class="flex h-full">
        @include('layouts.partials.sidebar')

        <div class="flex min-w-0 flex-1 flex-col overflow-y-auto">
            {{-- Barra superior: solo mobile/tablet, el sidebar fijo se encarga en desktop --}}
            <div class="flex items-center gap-3 border-b border-gray-200 bg-white px-4 py-3 lg:hidden dark:border-gray-800 dark:bg-gray-900">
                <button type="button" @click="sidebarOpen = true" aria-label="Abrir menú" class="rounded-md p-1.5 text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800">
                    <x-heroicon-o-bars-3 class="h-6 w-6" />
                </button>
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-500 text-white">
                    <x-heroicon-o-truck class="h-4 w-4" />
                </span>
                <span class="text-sm font-bold text-gray-900 dark:text-white">RentaCar</span>
            </div>

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>
