<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Quick actions</x-slot>
        <x-slot name="description">Jump straight into the most common admin tasks.</x-slot>

        <div class="grid gap-3 sm:grid-cols-2">
            @foreach ($actions as $action)
                <a
                    href="{{ $action['url'] }}"
                    @if (! empty($action['external'])) target="_blank" rel="noopener noreferrer" @endif
                    class="group rounded-xl border border-gray-200 bg-white p-4 transition hover:border-transparent hover:shadow-md dark:border-white/10 dark:bg-white/5"
                    style="border-left: 4px solid {{ $action['color'] }}"
                >
                    <div class="text-sm font-bold text-gray-950 dark:text-white group-hover:text-[color:var(--action)]" style="--action: {{ $action['color'] }}">
                        {{ $action['label'] }}
                    </div>
                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        {{ $action['description'] }}
                    </div>
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
