<?php

use Livewire\Volt\Component;
use App\Models\Feedback;

new class extends Component {

    public $feedback;

    public function toggleFeedback(){

        // get current user
        $user = auth('web')->user();

        // validate
        $validated = $this->validate(['feedback' => 'required|string']);

        //create
        Feedback::create([
            'user_id' => $user->id,
            'feedback' => $validated['feedback']
        ]);

        //refresh / popup message
        $this->dispatch('showToast', 'Feedback submitted successfully!', 'success');
        $this->reset(['feedback']);
    }

}; ?>

<div>
    <div class="bg-white dark:bg-zinc-900 shadow-md rounded-lg p-6 text-center">
        <h3 class="text-lg font-semibold text-brown-600 dark:text-neutral-100 mb-4">💬 Share Your Feedback</h3>
        
        <form action="#" method="POST" class="space-y-3">
            <textarea 
            wire:model="feedback"
            name="feedback" 
            placeholder="Your thoughts..." 
            class="w-full p-3 border rounded-lg text-sm focus:ring-2 focus:ring-[#A67C52] focus:outline-none resize-none dark:text-neutral-100"
            rows="3"
            ></textarea>
            
            <flux:button variant="primary" class="w-full" wire:click="toggleFeedback">
                Send Feedback
            </flux:button>
        </form>
        
        <p class="text-xs text-gray-500 mt-3">We value your voice in this community 💛</p>
    </div>
</div>
