<?php

use Livewire\Volt\Component;
use App\Models\User;
use Livewire\Attributes\Computed;

new class extends Component {
    
    #[Computed]
    public function authors(){
        return User::whereHas('post', function ($query){
                    $query->where('is_public', true);
                })
                    ->withCount(['post as public_posts_count' => function ($query) {
                        $query->where('is_public', true);
                    }])
                    ->orderByDesc('public_posts_count')
                    ->take(5)
                    ->get();
    }

}; ?>

<div>
    <div class="bg-white dark:bg-zinc-900 shadow-md rounded-lg p-6">
        <h3 class="text-lg font-semibold text-brown-600 dark:text-neutral-100 mb-4">Top Authors</h3>
        <ul class="space-y-4">
            @foreach ($this->authors as $author)
                <li class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-brown-50 dark:bg-cyan-100 text-brown-900 dark:text-cyan-900 font-bold">
                            {{ strtoupper(substr($author->username, 0, 1)) }}
                        </div>
                        <!-- Author Info -->
                        <div>
                        <a href="/authors/{{ $author->id }}" class="hover:underline"><h4 class="font-semibold text-brown-900 dark:text-neutral-100">{{ $author->username }}</h4></a>
                        <p class="text-sm text-gray-500">{{ $author->public_posts_count }} posts</p>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>

        <!-- See All Authors Link -->
        <div class="mt-4 text-center">
            <a href="/authors" class="text-sm font-medium text-brown-600 dark:text-cyan-600 dark:hover:text-cyan-300 hover:underline">
            Browse More Voices →
            </a>
        </div>

    </div>
</div>
