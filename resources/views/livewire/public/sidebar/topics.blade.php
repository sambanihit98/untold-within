<?php

use App\Models\Topic;
use Livewire\Volt\Component;
use Livewire\Attributes\Computed;

new class extends Component {

    #[Computed]
    public function topics(){
        return Topic::whereHas('posts', function ($query) {
                $query->where('is_public', true);
            })
            ->withCount(['posts as public_posts_count' => function ($query) {
                $query->where('is_public', true);
            }])
            ->orderByDesc('public_posts_count')
            ->take(5)
            ->get();
    }

    
}; ?>

<div>
    <div class="bg-white dark:bg-zinc-900 shadow-md rounded-lg p-6">
        <h3 class="text-lg font-semibold text-brown-600 dark:text-neutral-100 mb-4">Popular Topics</h3>
        <ul class="space-y-3 text-gray-700 dark:text-neutral-100">

            @foreach ($this->topics as $topic)
                 <li>
                    <a href="/topics/{{ $topic->id }}" class="flex justify-between items-center hover:text-brown-600 dark:hover:text-cyan-200">
                        <span>{{ $topic->name }}</span>
                        <span class="text-sm bg-brown-50 dark:bg-cyan-100 text-brown-600 dark:text-cyan-900 px-2 py-0.5 rounded-full">{{ $topic->public_posts_count }}</span>
                    </a>
                </li>
            @endforeach
        
        </ul>

        <!-- Link to All Topics Page -->
        <div class="mt-4 text-center">
            <a href="/topics" class="text-sm font-medium text-brown-600 dark:text-cyan-600 dark:hover:text-cyan-300 hover:underline">
            View All Topics →
            </a>
        </div>

    </div>
</div>
