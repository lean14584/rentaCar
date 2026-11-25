@props([
    'wireModel',
    'options' => [],
    'value' => null,
    'placeholder' => 'Buscar...',
])

@php
    $options = collect($options)->values();
    $selected = $options->first(fn ($o) => (string) $o['value'] === (string) $value);
@endphp

<div
    x-data="{
        open: false,
        query: @js($selected['label'] ?? ''),
        options: @js($options),
        get filtered() {
            if (this.query === '') { return this.options; }
            const q = this.query.toLowerCase();
            return this.options.filter((o) => o.label.toLowerCase().includes(q));
        },
        select(option) {
            this.query = option.label;
            this.open = false;
            $wire.set('{{ $wireModel }}', option.value);
        },
        clear() {
            this.query = '';
            this.open = false;
            $wire.set('{{ $wireModel }}', '');
        },
    }"
    x-on:click.outside="open = false"
    class="relative"
>
    <div class="relative">
        <input
            type="text"
            x-model="query"
            x-on:focus="open = true; $el.select()"
            x-on:input="open = true"
            x-on:keydown.escape="open = false"
            x-on:keydown.enter.prevent="if (filtered.length === 1) { select(filtered[0]); }"
            placeholder="{{ $placeholder }}"
            autocomplete="off"
            {{ $attributes->merge(['class' => 'w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 pr-9 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-100']) }}
        >
        <button
            type="button" x-show="query !== ''" x-cloak x-on:click="clear"
            class="absolute inset-y-0 right-2 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
            aria-label="Limpiar"
        >
            <x-heroicon-o-x-mark class="h-4 w-4" />
        </button>
    </div>

    <div
        x-show="open" x-cloak x-transition.opacity.duration.100ms
        class="absolute z-20 mt-1 max-h-56 w-full overflow-auto rounded-xl border border-gray-200 bg-white py-1 shadow-lg dark:border-gray-700 dark:bg-gray-800"
    >
        <template x-if="filtered.length === 0">
            <div class="px-3 py-2 text-sm text-gray-400">Sin resultados</div>
        </template>
        <template x-for="option in filtered" :key="option.value">
            <button
                type="button" x-on:click="select(option)" x-text="option.label"
                class="block w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-amber-50 dark:text-gray-200 dark:hover:bg-amber-500/10"
            ></button>
        </template>
    </div>
</div>
