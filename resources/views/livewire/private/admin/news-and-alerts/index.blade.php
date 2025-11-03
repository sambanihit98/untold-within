<?php

use Livewire\Volt\Component;

new class extends Component {
    

    public function toggleAdd(){
        
    }
}; ?>

<div class="flex flex-col gap-6 p-6">
    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>News & Alerts</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Heading Title --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('News & Alerts') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Stay informed with the latest announcements and important alerts.') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div class="flex justify-end">
        <flux:button icon="plus" variant="primary" wire:click="toggleAdd">Add new</flux:button>
    </div>

    <div class="flex flex-col items-center justify-center text-center py-12 border border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
        <div class="flex flex-col items-center space-y-3">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-neutral-200">
                No news or alerts available
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm">
                There are no announcements or updates at the moment. Check back later for the latest news and system alerts.
            </p>
        </div>
    </div>

    
</div>
