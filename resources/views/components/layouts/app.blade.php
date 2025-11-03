<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>
        {{ $slot }}

        {{-- pop up meassage / toast --}}
        <livewire:components.toast />

    </flux:main>
</x-layouts.app.sidebar>
