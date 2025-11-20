<?php

use Livewire\Volt\Component;

new class extends Component {
    
}; ?>

<div class="flex flex-col gap-6 p-6">

    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Activity Logs</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Heading Title --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Activity Logs') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Subheading here...') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>
    
</div>
