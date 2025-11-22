<?php

use App\Models\Topic;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;

new #[Layout('components.layouts.public.app', ['title' => 'Topics | Untold Within'])] class extends Component {
    
    use WithPagination;

    public $search = '';

        #[Computed]
        public function topics(){
            return Topic::latest()
                    ->with('tags')
                    ->when($this->search, fn($q) =>
                        $q->where('name', 'like', "%{$this->search}%")
                        ->orWhereHas('tags', fn($tagQuery) =>
                            $tagQuery->where('name', 'like', "%{$this->search}%")
                        )
                    )
                    ->paginate(5);
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
    <section id="all-topics" class="mb-20 mt-40">
        <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- topics Area -->
            <div class="lg:col-span-3 space-y-8">

            <!-- Breadcrumbs -->
            <nav class="text-sm mb-6 border-b border-gray-200 pb-5">
                <ol class="flex items-center space-x-2">
                <li>
                    <a href="/" class="text-brown-600 dark:text-neutral-100 font-medium hover:underline">Home</a>
                </li>
                <li class="text-brown-600 dark:text-neutral-100 ">›</li>
                <li class="px-2 py-1 rounded text-brown-600 dark:text-neutral-100  font-semibold">
                    Topics
                </li>
                </ol>
            </nav>
            
            <!-- Heading -->
            <x-public.section-heading>Browse by Topic</x-public.section-heading>

            <!-- Intro Text -->
            <p class="text-gray-600 dark:text-neutral-200 leading-relaxed">
                Dive into our collection of topics and find posts that match your interests. 
                Whether you're exploring insights, guides, or inspiring stories — these tags 
                will help you discover more of what you love.
            </p>

            {{-- -------------------------------------------------------------- --}}
            {{-- -------------------------------------------------------------- --}}
            <!-- Search Bar -->
            <div class="flex flex-col">
                
                <div>
                    <input 
                        type="text" 
                        name="search" 
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search topic or tag..." 
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-100"
                    >
                </div>
    
                <div class="mt-5">
                    <!-- Results summary -->
                    @if($search)
                        @if($this->topics->count() > 0)
                            <p class="text-sm text-gray-500 mt-2">
                                Showing <strong>{{ $this->topics->total() }}</strong> {{ Str::plural('result', $this->topics->total()) }}
                            </p>
                        @else
                            <div class="flex flex-col items-center justify-center text-center py-12 border border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
                                <div class="flex flex-col items-center space-y-3">
                                    <h3 class="text-lg font-semibold text-gray-700 dark:text-neutral-200">
                                        No topics or tags found for "<span class="font-semibold">{{ $search }}</span>".
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm">
                                        We couldn’t find any topics or tags matching your search. Try using different keywords.
                                    </p>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            
            </div>

            <!-- Topics Section -->
            <div class="space-y-14 mt-10">

                @foreach ($this->topics as $topic)
                    <div class="dark:bg-zinc-900 rounded-2xl shadow-sm p-6 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="text-xl font-semibold text-brown-900 dark:text-neutral-100">
                                {!! $this->highlight($topic->name) !!}
                            </h3>
                            <a href="/topics/{{ $topic->id }}" class="text-sm text-brown-600 dark:text-cyan-600 font-medium hover:underline">
                            View All Posts →
                            </a>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-neutral-300 mb-4 leading-relaxed">
                            {{ $topic->description }}
                        </p>

                        <div class="flex flex-wrap gap-3">
                            @foreach ($topic->tags as $tag)
                                <x-public.post-tag href="/topics/{{ $tag->topic_id }}/{{ $tag->id }}">
                                    {!! $this->highlight($tag->name) !!}
                                </x-public.post-tag>
                            @endforeach
                        </div>
                    </div>
                @endforeach

            </div>

            <div>
                {{ $this->topics->links() }}
            </div>

            <!-- Note or CTA -->
            <div class="mt-10 bg-brown-600/5 dark:bg-cyan-100/5 border border-brown-600/20 dark:border-cyan-600/20 rounded-xl p-6">
                <h4 class="font-semibold text-brown-900 dark:text-neutral-100 mb-2">Can’t find the topic you’re looking for?</h4>
                <p class="text-gray-600 dark:text-neutral-300 text-sm">
                Explore posts by topic to discover stories that speak to you — or visit our 
                <a href="/posts" class="text-brown-600 dark:text-cyan-600 font-medium hover:underline">Posts</a> 
                page to see every shared thought and emotion.
                </p>
            </div>

            </div>

            {{-- Side Bar --}}
            @include('partials.public.sidebar')

        </div>
        </div>
    </section>
</div>
