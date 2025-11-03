<?php

use App\Models\Post;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;

new #[Layout('components.layouts.public.app')] class extends Component {
    
    use WithPagination;

    public $search = '';

    #[Computed]
    public function posts(){
        return Post::latest()
                ->where('is_public', true)
                ->when($this->search, fn($q) =>
                    $q->where('title', 'like', "%{$this->search}%")
                    // ->orWhere('content', 'like', "%{$this->search}%")
                )
                ->with(['user', 'topics', 'tags'])
                ->paginate(5);

    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function highlight($text)
    {
        if (!$this->search) return $text;

        $pattern = '/' . preg_quote($this->search, '/') . '/i';
        return preg_replace($pattern, '<mark class="bg-yellow-200 text-black font-semibold">$0</mark>', e($text));
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
                    Posts
                </li>
                </ol>
            </nav>
            
            <!-- Heading -->
            <x-public.section-heading>All Posts</x-public.section-heading>
            
            {{-- -------------------------------------------------------------- --}}
            {{-- -------------------------------------------------------------- --}}
            <!-- Search Bar -->
            <div class="flex flex-col">
                
                <div>
                    <input 
                        type="text" 
                        name="search" 
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search post..." 
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-100"
                    >
                </div>
                
                <div class="mt-5">
                    <!-- Results summary -->
                    @if($search)
                        @if($this->posts->count() > 0)
                            <p class="text-sm text-gray-500 mt-2">
                                Showing <strong>{{ $this->posts->total() }}</strong> {{ Str::plural('result', $this->posts->total()) }}
                            </p>
                        @else
                            <div class="flex flex-col items-center justify-center text-center py-12 border border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
                                <div class="flex flex-col items-center space-y-3">
                                    <h3 class="text-lg font-semibold text-gray-700 dark:text-neutral-200">
                                        No posts found for "<span class="font-semibold">{{ $search }}</span>".
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm">
                                        We couldn’t find any posts matching your search. Try using different keywords.
                                    </p>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            
            </div>
            {{-- -------------------------------------------------------------- --}}
            {{-- -------------------------------------------------------------- --}}

            <!-- Post Cards Grid -->
            <div class="space-y-10 mt-8">

                @foreach ($this->posts as $post)

                    <x-public.post-card>
                    <a href="posts/{{ $post->id }}" class="hover:underline dark:text-neutral-100 text-brown-800">
                        <x-public.post-heading class="text-xl">
                            {!! $this->highlight($post->title) !!}
                        </x-public.post-heading>
                    </a>
                    <x-public.post-subheading>
                        <a href="/authors/{{ $post->user->id }}" class="font-medium transition-all hover:underline hover:font-bold duration-200 ease-in-out">
                            {{ $post->user->username }}
                        </a> • {{ $post->created_at->diffForHumans() }}</x-public.post-subheading>
                        
                    <x-public.post-content>{{ $post->content }}</x-public.post-content>

                   @if ($post->topics->count())
                        <!-- Topics -->
                        <div class="mt-5 flex flex-wrap items-center gap-2">
                            <span class="text-sm font-semibold text-brown-600 dark:text-cyan-600">Topics:</span>
                            @foreach ($post->topics as $topic)
                                <x-public.post-tag href="/topics/{{ $topic->id }}">
                                    {{ $topic->name }}
                                </x-public.post-tag>
                            @endforeach
                        </div>
                    @endif
                    

                    @if ($post->tags->count())
                        <!-- Tags -->
                        <div class="mt-3 flex flex-wrap items-center gap-2">
                            <span class="text-sm font-semibold text-brown-600 dark:text-cyan-600">Tags:</span>
                            @foreach ($post->tags as $tag)
                                <x-public.post-tag href="/topics/{{ $tag->topic_id }}/{{ $tag->id }}">
                                    {{ $tag->name }}
                                </x-public.post-tag>
                            @endforeach
                        </div>
                    @endif

                    <div class="mt-4">
                        <div class="flex items-center gap-6 text-gray-500 text-sm dark:text-neutral-100 justify-end">
                            <div class="flex items-center gap-1">
                                <x-public.heroicons :icon="'heart'"/>
                                <span>{{ $post->likes_count }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <x-public.heroicons :icon="'save'"/>
                                </svg>
                                <span>{{ $post->saves_count }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <x-public.heroicons :icon="'comment'"/>
                                <span>{{ $post->comments_count }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <x-public.heroicons :icon="'views'"/>
                                <span>{{ $post->views_count }}</span>
                            </div>
                        </div>
                    </div>
                    </x-public.post-card>

                @endforeach

                <div>
                    {{ $this->posts->links() }}
                </div>

            </div>
            </div>

            {{-- Side Bar --}}
            @include('partials.public.sidebar')

        </div>
        </div>
    </section>
</div>
