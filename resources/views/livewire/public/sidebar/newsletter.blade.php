<?php

use Livewire\Volt\Component;
use App\Models\SubscribedEmail;

new class extends Component {

    public $email = '';
    
    public function toggleSubscribe(){

        //validate
        $validated = $this->validate(['email' => 'required|email|unique:subscribed_emails,email']);

        //create
        SubscribedEmail::create([
            'email' => $validated['email']
        ]);

        //Pop up message / Toast
        $this->dispatch('showToast', 'Subscribed successfully!', 'success');
        $this->reset(['email']);
    }

}; ?>

<div>
    <div class="bg-white dark:bg-zinc-900 shadow-md rounded-lg p-6 text-center space-y-4">
        <h3 class="text-lg font-semibold text-brown-600 dark:text-neutral-100">Stay Updated</h3>
        <p class="text-sm text-gray-600">Subscribe to get the latest untold stories straight to your inbox.</p>
        <flux:field>
            <flux:input id="email" wire:model="email" placeholder="example@domain.com" autocomplete="off"/>
            <flux:error name="email" />
        </flux:field>
        <flux:button variant="primary" class="w-full" wire:click="toggleSubscribe">
            Subscribe
        </flux:button>
    </div>
</div>
