@props(['title', 'subtitle' => null, 'icon' => null])

<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center gap-3">
        @if ($icon)
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500/15 text-amber-600 dark:text-amber-400">
                <x-dynamic-component :component="'heroicon-o-'.$icon" class="h-5 w-5" />
            </span>
        @endif
        <div>
            <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $title }}</h1>
            @if ($subtitle)
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $subtitle }}</p>
            @endif
        </div>
    </div>

    @isset($actions)
        <div class="flex items-center gap-2">{{ $actions }}</div>
    @endisset
</div>
