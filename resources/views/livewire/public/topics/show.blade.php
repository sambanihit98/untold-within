<?php

use App\Models\Topic;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.public.app')] class extends Component {
    
    public $topic;

   public function mount($id){
        $this->topic = Topic::where('id', $id)
            ->with([
                'posts' => function ($query) {
                    $query->where('is_public', true) //only public posts
                        ->with('user')
                        ->latest();
                },
                'tags'
            ])
            ->firstOrFail();
    }


}; ?>

<div>
    <section class="mb-20 mt-40">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-10">
            
            <!-- Main Content -->
            <div class="lg:col-span-3 space-y-10">

                <!-- Breadcrumbs -->
                <nav class="text-sm mb-6 border-b border-gray-200 pb-5">
                    <ol class="flex items-center space-x-2">
                    <li>
                        <a href="/" class="text-brown-600 dark:text-neutral-100 font-medium hover:underline">Home</a>
                    </li>
                    <li class="text-gray-400">›</li>
                    <li>
                        <a href="/topics" class="text-brown-600 dark:text-neutral-100 font-medium hover:underline">Topics</a>
                    </li>
                    <li class="text-gray-400">›</li>
                    <li class="py-1 rounded text-brown-600 dark:text-neutral-100 font-semibold">
                        {{ $this->topic->name }}
                    </li>
                    </ol>
                </nav>

                <!-- Topic Header -->
                <div>
                <x-public.section-heading>{{ $this->topic->name }}</x-public.section-heading>
                <p class="text-gray-600 dark:text-neutral-200 mt-3 leading-relaxed">
                    {{ $this->topic->description }}
                </p>
                </div>

                <!-- Related Tags -->
                @if ($this->topic->tags->count())
                    <div>
                        <h3 class="text-lg font-semibold text-brown-900 dark:text-neutral-100 mb-4">Related Tags</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($this->topic->tags as $tag)
                                <x-public.post-tag href="/topics/{{ $tag->topic_id }}/{{ $tag->id }}">{{ $tag->name }}</x-public.post-tag>
                            @endforeach
                        </div>
                    </div>
                @endif
            

                <!-- Posts under this Topic -->
                @if ($this->topic->posts->count())
                    <div class="space-y-10 mt-8">
                        <h3 class="text-lg font-semibold text-brown-900 dark:text-neutral-100">Stories & Reflections</h3>

                        @foreach ($this->topic->posts as $post)
                            <x-public.post-card>
                                <a href="/posts/{{ $post->id }}" class="hover:underline dark:text-neutral-100 text-brown-800">
                                    <x-public.post-heading class="text-xl">{{ $post->title }}</x-public.post-heading>
                                </a>
                                <x-public.post-subheading>
                                    <a href="/authors/{{ $post->user->id }}" class="font-medium transition-all hover:underline hover:font-bold duration-200 ease-in-out">
                                        {{ $post->user->username }}
                                    </a>
                                    • {{ $post->created_at->diffForHumans() }}</x-public.post-subheading>
                                <x-public.post-content>{{ $post->content }}</x-public.post-card-content>

                                @if ($post->topics->count())
                                    <!-- Topics -->
                                    <div class="mt-3 flex flex-wrap items-center gap-2">
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
                                <div class="flex items-center gap-6 text-gray-500 dark:text-neutral-100 text-sm justify-end">
                                    <div class="flex items-center gap-1">
                                    <x-public.heroicons :icon="'heart'" />
                                    <span>{{ $post->likes_count }}</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                    <x-public.heroicons :icon="'save'" />
                                    <span>{{ $post->saves_count }}</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                    <x-public.heroicons :icon="'comment'" />
                                    <span>{{ $post->comments_count }}</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                    <x-public.heroicons :icon="'views'" />
                                    <span>{{ $post->views_count }}</span>
                                    </div>
                                </div>
                                </div>
                            </x-public.post-card>
                        @endforeach
                    </div>

                @else
                    <!-- Empty State -->
                    <div class="flex flex-col items-center justify-center text-center py-12 border border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
                        <div class="flex flex-col items-center space-y-3">
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-neutral-200">
                                No stories yet
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm">
                                Be the first to share something under this topic.
                            </p>
                        </div>
                    </div>
                @endif

            </div>

            <!-- Sidebar -->
            @include('partials.public.sidebar')

            </div>
        </div>
    </section>
</div>
