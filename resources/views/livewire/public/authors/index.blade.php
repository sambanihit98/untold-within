<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

new #[Layout('components.layouts.public.app')] class extends Component {
    
    use WithPagination;

    public $search = '';

    #[Computed]
    public function authors(){
        return User::whereHas('post')
                ->when($this->search, fn($q) =>
                    $q->where('username', 'like', "%{$this->search}%")
                )
                ->withCount([
                    // Count only posts where is_public = true
                    'post as public_post_count' => function ($query) {
                        $query->where('is_public', true);
                    },
                    'followings',
                    'followers'
                ])
                ->orderByDesc('post_count')
                ->paginate(10);
    }

    public function highlight($text)
    {
        if (!$this->search) return $text;

        $pattern = '/' . preg_quote($this->search, '/') . '/i';
        return preg_replace($pattern, '<mark class="bg-yellow-200 text-black font-semibold">$0</mark>', e($text));
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

}; ?>

<div>
    <section id="all-posts" class="mb-20 mt-40">
        <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Posts Area -->
            <div class="lg:col-span-3 space-y-8">

                <!-- Breadcrumbs -->
                <nav class="text-sm mb-6 border-b border-gray-200 pb-5">
                    <ol class="flex items-center space-x-2">
                    <li>
                        <a href="/" class="text-brown-600 dark:text-neutral-100 font-medium hover:underline">Home</a>
                    </li>
                    <li class="text-brown-600 dark:text-neutral-100 ">›</li>
                    <li class="px-2 py-1 rounded text-brown-600 dark:text-neutral-100  font-semibold">
                        Authors
                    </li>
                    </ol>
                </nav>

                <!-- Authors Section -->
                <div class="space-y-8">

                    <!-- Heading -->
                    <x-public.section-heading>The Hearts Behind the Words</x-public.section-heading>

                    {{-- -------------------------------------------------------------- --}}
                    {{-- -------------------------------------------------------------- --}}
                    <!-- Search Bar -->
                    <div class="flex flex-col">
                        
                        <div>
                            <input 
                                type="text" 
                                name="search" 
                                wire:model.live.debounce.300ms="search"
                                placeholder="Search author..." 
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-100"
                            >
                        </div>
            
                        <div class="mt-5">
                            <!-- Results summary -->
                            @if($search)
                                @if($this->authors->count() > 0)
                                    <p class="text-sm text-gray-500 mt-2">
                                        Showing <strong>{{ $this->authors->total() }}</strong> {{ Str::plural('result', $this->authors->total()) }}
                                    </p>
                                @else
                                    <div class="flex flex-col items-center justify-center text-center py-12 border border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
                                        <div class="flex flex-col items-center space-y-3">
                                            <h3 class="text-lg font-semibold text-gray-700 dark:text-neutral-200">
                                                No authors found for "<span class="font-semibold">{{ $search }}</span>".
                                            </h3>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm">
                                                We couldn’t find any authors matching your search. Try using different keywords.
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    
                    </div>
                    {{-- -------------------------------------------------------------- --}}
                    {{-- -------------------------------------------------------------- --}}

                    <div class="grid md:grid-cols-2 gap-8">
                        
                        @foreach ($this->authors as $author)
                            <div class="bg-white dark:bg-zinc-900 shadow-lg rounded-lg p-6 hover:shadow-2xl transition flex items-center gap-4">
                                <!-- Avatar -->
                                <div class="w-20 h-20 flex-none rounded-full bg-brown-50 dark:bg-cyan-50 text-brown-900 dark:text-cyan-900 font-bold text-3xl flex items-center justify-center">
                                    {{ strtoupper(substr($author->username, 0, 1)) }}
                                </div>

                                <!-- Author Info -->
                                <div class="flex-1">
                                    <h3 class="text-xl font-semibold text-gray-800 dark:text-neutral-100"> {!! $this->highlight($author->username) !!}</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-neutral-300">
                                        {{ $author->tagline }}
                                    </p>
                                    <div class="mt-2 text-xs text-gray-500 dark:text-neutral-300 space-y-1">
                                        <p>📝 {{ $author->public_post_count }} Stories Published</p>
                                        <p>📅 Joined: {{ $author->created_at->format('M. j, Y') }}</p>
                                        <p>👥 {{ $author->followers_count }} Followers</p>
                                        <p>🫱 {{ $author->followings_count }} Following</p>
                                    </div>
                                    <a href="/authors/{{ $author->id }}" class="mt-3 inline-block text-brown-600 dark:text-cyan-600 hover:underline font-medium">
                                        Meet the Author →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                        
                    </div>

                    <div>
                        {{ $this->authors->links() }}
                    </div>
                </div>
            </div>

            {{-- Side Bar --}}
            @include('partials.public.sidebar')

        </div>
        </div>
    </section>
</div>
