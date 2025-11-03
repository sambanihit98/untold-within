<?php

use App\Models\Post;
use App\Models\User;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

use Livewire\Attributes\Computed;
use Livewire\WithPagination;


new #[Layout('components.layouts.app', ['title' => 'My Posts'])] class extends Component {

    use WithPagination;

    public $toastForRedirect = null;

    public function mount() {
        if (session()->has('toast')) {
            $this->toastForRedirect = session('toast');
        }
    }

    public function hydrate() {
        if ($this->toastForRedirect) {
            $this->dispatchBrowserEvent('showToast', $this->toastForRedirect);
            $this->toastForRedirect = null;
        }
    }

    //---------------------------------------------------------
    //---------------------------------------------------------
    #[Computed]
    public function posts(){

        return Post::where('user_id', auth('web')->id())
                        ->with(['topics', 'tags'])
                        ->latest()
                        ->paginate(10);
    }

}; ?>

<div class="flex flex-col gap-6 p-6">
    
    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>My Post</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Heading Title --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('My Posts') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('A collection of my thoughts, stories, and shared moments.') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    @if($this->posts->count())

        {{-- Add New Button --}}
        <div class="flex justify-between items-center">
            <flux:button icon="plus" tag="a" href="{{ route('my-posts.create') }}"  variant="primary">
                Add New Post
            </flux:button>
        </div>

        <!-- POSTS -->
        <div class="grid auto-rows-min gap-6 md:grid-cols-2">
            @foreach ($this->posts as $post)
            
                <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 overflow-hidden">
                    
                    <div class="p-5 space-y-2">
                        <a href="my-posts/{{ $post->id }}" class="hover:underline">
                            <flux:heading size="xl">{{ $post->title }}</flux:heading>
                        </a>
                        <flux:subheading class="text-gray-500 mt-2">
                            Posted {{ $post->created_at->diffForHumans() }}
                            @if ($post->updated_at->gt($post->created_at))
                                • Updated {{ $post->updated_at->diffForHumans() }}
                            @endif
                        </flux:subheading>

                        <!-- Visibility Badge -->
                        @if ($post->is_public)
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
                            {{ $post->content }}
                        </flux:text>
                        
                        <!-- Topics -->
                        <div class="mt-6 flex flex-wrap items-center gap-2">

                            @if ($post->topics->count())
                                <span class="text-sm font-semibold text-brown-600 dark:text-cyan-300">Topics:</span>
                                    @foreach ($post->topics as $topic)
                                        <x-public.post-tag>{{ $topic->name }}</x-public.post-tag>
                                    @endforeach
                            @endif
                            
                        </div>

                        <!-- Tags -->
                        <div class="mt-6 flex flex-wrap items-center gap-2">

                            @if ($post->tags->count())
                                <span class="text-sm font-semibold text-brown-600 dark:text-cyan-300">Tags:</span>
                                    @foreach ($post->tags as $tag)
                                        <x-public.post-tag>{{ $tag->name }}</x-public.post-tag>
                                    @endforeach
                            @endif
                        </div>

                        <div class="mt-10">
                            <div class="flex items-center gap-6 text-gray-500 dark:text-neutral-100 text-sm justify-end">

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
                    </div>
                </div>

            @endforeach
        </div>

    @else

        <div class="flex flex-col items-center justify-center text-center py-12 border border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
            <div class="flex flex-col items-center space-y-3">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-neutral-200">
                    No posts found
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm">
                    You haven’t created any posts yet. Start sharing your thoughts and stories with the world!
                </p>
                <flux:button variant="primary" icon="plus" tag="a" href="{{ route('my-posts.create') }}" class="mt-4">
                    Add New Post
                </flux:button>
            </div>
        </div>

    @endif

    {{-- dispatch toast on page load if session has it (works reliably after redirect) --}}
    @if (session()->has('toast'))
        <script>
            window.addEventListener('DOMContentLoaded', function () {
                const toast = @json(session('toast'));

                // Safety check
                if (toast && toast.message) {
                    window.dispatchEvent(new CustomEvent('showToast', {
                        detail: {
                            message: toast.message,
                            type: toast.type ?? 'info'
                        }
                    }));
                }
            });
        </script>
    @endif

</div>
