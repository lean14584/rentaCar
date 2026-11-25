@props(['wireModel', 'title' => null, 'maxWidth' => 'md'])

@php
$maxWidthClass = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
][$maxWidth] ?? 'max-w-md';
@endphp

<div
    x-show="$wire.{{ $wireModel }}"
    x-cloak
    x-on:keydown.escape.window="$wire.{{ $wireModel }} = false"
    class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto px-4 pt-16 pb-8"
>
    <div x-show="$wire.{{ $wireModel }}" x-transition.opacity class="fixed inset-0 bg-gray-900/60"></div>

    <div
        x-show="$wire.{{ $wireModel }}"
        x-transition
        role="dialog" aria-modal="true" aria-label="{{ $title }}"
        class="relative w-full {{ $maxWidthClass }} rounded-2xl bg-white shadow-xl dark:bg-gray-900"
    >
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-800">
            <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $title }}</h2>
            <button type="button" x-on:click="$wire.{{ $wireModel }} = false" aria-label="Cerrar" class="rounded-md p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800">
                <x-heroicon-o-x-mark class="h-5 w-5" />
            </button>
        </div>

        <div class="px-6 py-5">
            {{ $slot }}
        </div>
    </div>
</div>
