<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.public.app')] class extends Component {
    //
}; ?>

<div>
    <!-- Hero About -->
    <section class="relative pt-48 pb-24 bg-brown-100 dark:bg-zinc-900">
        <div class="container mx-auto px-6 max-w-4xl text-center">

            <x-public.hero-heading>
                About <span class="text-brown-600 dark:text-cyan-600">Untold Within</span>
            </x-public.hero-heading>

            <x-public.text class="max-w-2xl mx-auto">
                Everyone carries thoughts, feelings, and stories they never say out loud.  
                <span class="font-semibold text-brown-600 dark:text-cyan-600">Untold Within</span> is a safe corner of the internet where you can finally let them out.  
                Share anonymously, read others’ voices, and find comfort knowing you’re not alone.
            </x-public.text>

        </div>
    </section>

    <!-- Our Mission -->
    <section class="py-20 bg-white dark:bg-zinc-800">
        <div class="container mx-auto px-6 max-w-6xl grid md:grid-cols-2 gap-16 items-center">
            <div>
                <x-public.section-heading>
                    Our Mission
                </x-public.section-heading>

                <x-public.text>
                    Life can feel heavy when we keep our emotions locked inside.  
                    Our mission is to create a sanctuary where you can release your innermost thoughts—without judgment or exposure.  
                    By sharing and reading others’ untold words, you may discover reflection, empathy, and healing.
                </x-public.text>

            </div>
            <div class="bg-brown-100 dark:bg-cyan-100 rounded-2xl p-10 shadow-xl">
                <p class="text-2xl italic font-medium text-brown-900 dark:text-cyan-900 leading-relaxed">
                    "Sometimes the most powerful words are the ones we never say out loud."
                </p>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-20 bg-gray-50 dark:bg-zinc-700">
        <div class="container mx-auto px-6 max-w-6xl">
            
            <x-public.section-heading>
                How It Works
            </x-public.section-heading>

            <div class="grid md:grid-cols-3 gap-10 text-center">
                <div class="p-8 bg-white dark:bg-zinc-900 rounded-2xl shadow-lg hover:shadow-xl transition">
                    <h3 class="text-xl font-semibold text-brown-900 dark:text-white mb-3">1. Share</h3>
                    <x-public.text>
                        Write your thoughts and feelings anonymously.  
                        No names, no faces—just pure, honest expression.
                    </x-public.text>
                </div>
                <div class="p-8 bg-white dark:bg-zinc-900 rounded-2xl shadow-lg hover:shadow-xl transition">
                    <h3 class="text-xl font-semibold text-brown-900 dark:text-white mb-3">2. Connect</h3>
                    <x-public.text>
                       Read posts from others who may be feeling the same.  
                        Discover comfort in knowing you’re not alone.
                    </x-public.text>
                </div>
                <div class="p-8 bg-white dark:bg-zinc-900 rounded-2xl shadow-lg hover:shadow-xl transition">
                    <h3 class="text-xl font-semibold text-brown-900 dark:text-white mb-3">3. Reflect</h3>
                     <x-public.text>
                       Let stories inspire healing, empathy, and a deeper understanding of the human experience.
                    </x-public.text>
                </div>
            </div>
        </div>
    </section>

    @include('partials.public.cta')
    
</div>
