<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div>
    <div class="bg-white dark:bg-zinc-900 shadow-md rounded-lg p-6 text-center">
        <h3 class="text-lg font-semibold text-brown-600 dark:text-neutral-100 mb-4">How are you feeling today?</h3>
        <form action="#" method="POST" class="flex justify-center gap-4">
            <button type="submit" name="mood" value="happy" 
                    class="text-3xl hover:scale-110 transition cursor-pointer">😊</button>
            <button type="submit" name="mood" value="neutral" 
                    class="text-3xl hover:scale-110 transition cursor-pointer">😐</button>
            <button type="submit" name="mood" value="sad" 
                    class="text-3xl hover:scale-110 transition cursor-pointer">😔</button>
        </form>
        <p class="text-xs text-gray-500 mt-3">Your response helps us create a better space 💛</p>
    </div>
</div>
