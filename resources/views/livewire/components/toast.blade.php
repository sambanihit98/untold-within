<div
    x-data="{
        // entangle all three so Livewire and Alpine stay in sync
        show: @entangle('show'),
        type: @entangle('type'),
        message: @entangle('message')
    }"
    x-cloak
    x-on:showToast.window="
        // browser event after redirect: detail contains message/type
        message = $event.detail.message ?? message;
        type = $event.detail.type ?? type;
        show = true;
        // allow a short moment if needed for animation
        setTimeout(() => show = false, 5000);
    "
    x-init="
        // Livewire internal event (emitted by $this->dispatch('toast-shown') in the component)
        $wire.on('toast-shown', () => {
            // when Livewire signals, message/type should already be set via entangle
            show = true;
            setTimeout(() => show = false, 5000);
        });
    "
    class="fixed top-5 right-5 z-50"
>
    <div
        x-show="show"
        x-transition.opacity.duration.300ms
        x-bind:class="{
            'bg-green-500 text-white': type === 'success',
            'bg-red-500 text-white': type === 'error',
            'bg-yellow-500 text-gray-900': type === 'warning',
            'bg-sky-500 text-white': type === 'info',
            'bg-blue-500 text-white': !['success','error','warning','info'].includes(type)
        }"
        class="rounded-xl shadow-lg flex items-center gap-3 px-5 py-3"
    >
        <template x-if="type === 'success'">
            <flux:icon name="check-circle" class="w-6 h-6" />
        </template>

        <template x-if="type === 'error'">
            <flux:icon name="x-circle" class="w-6 h-6" />
        </template>

        <template x-if="type === 'warning'">
            <flux:icon name="exclamation-circle" class="w-6 h-6" />
        </template>

        <template x-if="type === 'info'">
            <flux:icon name="information-circle" class="w-6 h-6" />
        </template>

        <span class="font-medium text-sm" x-text="message"></span>
    </div>
</div>
