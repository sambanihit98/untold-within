<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use App\Models\Like;
use Livewire\WithPagination;

new #[Layout('components.layouts.app', ['title' => 'Liked Posts'])] class extends Component {
    

    #[Computed]
    public function likedPosts()
    {
        return Like::latest()
            ->where('user_id', auth('web')->id())
            ->whereHas('post', function ($query) {
                $query->where('is_public', true);
            })
            ->with([
                'post' => function ($query) {
                    $query->with(['user', 'topics', 'tags'])
                          ->withCount(['likes', 'comments', 'saves']);
                }
            ])
            ->paginate(5);
    }
}; ?>

<div class="flex flex-col gap-6 p-6">

     <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Liked Posts</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Heading Title --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Liked Posts') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('A collection of posts you’ve liked — keep track of the content that caught your attention.') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    @if($this->likedPosts->count())
        <!-- POSTS -->
        <div class="grid auto-rows-min gap-6 md:grid-cols-2">
            @foreach ($this->likedPosts as $likedPost)
            
                <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 overflow-hidden">
                    
                    <div class="p-5 space-y-2">
                        <a href="posts/{{ $likedPost->post->id }}" class="hover:underline">
                            <flux:heading size="xl">{{ $likedPost->post->title }}</flux:heading>
                        </a>

                        <flux:subheading class="text-gray-500 mt-2">
                            <a href="/authors/{{ $likedPost->post->user->id }}" class="font-medium transition-all hover:underline hover:font-bold duration-200 ease-in-out">
                                {{ $likedPost->post->user->username }}
                            </a>
                            • Posted {{ $likedPost->post->created_at->diffForHumans() }}
                            @if ($likedPost->post->updated_at->gt($likedPost->post->created_at))
                                • Updated {{ $likedPost->post->updated_at->diffForHumans() }}
                            @endif
                        </flux:subheading>

                        <!-- Visibility Badge -->
                        @if ($likedPost->post->is_public)
                            <flux:badge color="teal" variant="soft" size="sm" class="flex items-center gap-1">
                                <flux:icon name="globe-alt" class="w-4 h-4" />
                                Public
                            </flux:badge>
                        @else
                            <flux:badge color="rose" variant="soft" size="sm" class="flex items-center gap-1">
                                <flux:icon name="lock-closed" class="w-4 h-4" />
                                Private
                            </flux:badge>
                        @endif

                        <flux:text size="lg" class="line-clamp-3 text-gray-600 dark:text-neutral-200 mt-5">
                            {{ $likedPost->post->content }}
                        </flux:text>
                        
                        <!-- Topics -->
                        <div class="mt-6 flex flex-wrap items-center gap-2">

                            @if ($likedPost->post->topics->count())
                                <span class="text-sm font-semibold text-brown-600 dark:text-cyan-300">Topics:</span>
                                    @foreach ($likedPost->post->topics as $topic)
                                        <x-public.post-tag>{{ $topic->name }}</x-public.post-tag>
                                    @endforeach
                            @endif
                            
                        </div>

                        <!-- Tags -->
                        <div class="mt-6 flex flex-wrap items-center gap-2">

                            @if ($likedPost->post->tags->count())
                                <span class="text-sm font-semibold text-brown-600 dark:text-cyan-300">Tags:</span>
                                    @foreach ($likedPost->post->tags as $tag)
                                        <x-public.post-tag>{{ $tag->name }}</x-public.post-tag>
                                    @endforeach
                            @endif
                        </div>

                        <div class="mt-10">
                            <div class="flex items-center gap-6 text-gray-500 dark:text-neutral-100 text-sm justify-end">

                            <div class="flex items-center gap-1">
                                <x-public.heroicons :icon="'heart'"/>
                                <span>{{ $likedPost->post->likes_count }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <x-public.heroicons :icon="'save'"/>
                                </svg>
                                <span>{{ $likedPost->post->saves_count }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <x-public.heroicons :icon="'comment'"/>
                                <span>{{ $likedPost->post->comments_count }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <x-public.heroicons :icon="'views'"/>
                                <span>{{ $likedPost->post->views_count }}</span>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="flex flex-col items-center justify-center text-center py-12 border border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
            <div class="flex flex-col items-center space-y-3">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-neutral-200">
                    No liked posts found
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm">
                    You haven’t liked any posts yet. Start exploring and tap the heart icon on posts that catch your attention!
                </p>
            </div>
        </div>
    @endif
    
</div>
