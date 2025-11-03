<?php

use App\Models\User;
use App\Models\Post;
use App\Models\Notification;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\Attributes\Computed;

new #[Layout('components.layouts.public.app')] class extends Component {
    
    public $author;
    public bool $isFollowing = false;

    public function mount($id){
        $this->author = User::where('id', $id)
                            ->withCount([
                                // Count only posts where is_public = true
                                'post as public_post_count' => function ($query) {
                                    $query->where('is_public', true);
                                },
                                'followings',
                                'followers'
                            ])
                            ->firstOrFail();

        $user = auth('web')->user(); // current user logged in
        if ($user) {
            $this->isFollowing = $user->followings()
                ->where('following_user_id', $this->author->id)
                ->exists();
        }
    }

    #[Computed]
    public function posts(){
        return Post::where('user_id', $this->author->id)
                        ->where('is_public', true)
                        ->with(['user', 'topics', 'tags'])
                        ->latest()
                        ->paginate(5);
    }

    public function toggleFollow(){
        
        $user = auth('web')->user(); // current user logged in

        if (!$user || $user->id === $this->author->id) return;

        if ($this->isFollowing) {
            $user->followings()->detach($this->author->id);
            $this->isFollowing = false;

            // Delete the related notification
            Notification::where('user_id', $this->author->id)
                ->where('created_by', $user->id)
                ->where('category', 'follow')
                ->delete();
        } else {
            $user->followings()->attach($this->author->id);
            $this->isFollowing = true;

            if($this->author->id !== $user->id){
                //Notification
                Notification::create([
                    'user_id' => $this->author->id,
                    'title' => 'New follower',
                    'summary' => 'Someone followed you',
                    'details'   => json_encode([]),
                    'category' => 'follow',
                    'link' => '/authors/'. $user->id,
                    'is_read' => false,
                    'created_by' => $user->id,
                ]);
            }
        }

        // Refresh author counts
        $this->author->loadCount(['followers', 'followings', 'post as public_post_count' => function ($query) {
            $query->where('is_public', true);
        }]);

        $this->dispatch('refresh');

    }

}; ?>

<div>
    <section id="author-area" class="mb-20 mt-40">
        <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Author Area -->
            <div class="lg:col-span-3 space-y-8">

                <!-- Breadcrumbs -->
                <nav class="text-sm mb-6 border-b border-gray-200 pb-5">
                    <ol class="flex items-center space-x-2">
                    <li>
                        <a href="/" class="text-brown-600 dark:text-neutral-100 font-medium hover:underline">Home</a>
                    </li>
                    <li class="text-gray-400">›</li>
                    <li>
                        <a href="/authors" class="text-brown-600 dark:text-neutral-100 font-medium hover:underline">Authors</a>
                    </li>
                    <li class="text-gray-400">›</li>
                    <li class="py-1 rounded text-brown-600 dark:text-neutral-100 font-semibold">
                        {{ $author->username }}
                    </li>
                    </ol>
                </nav>

                <!-- Author Profile Info -->
                <!-- Profile Header -->
                <div class="bg-gradient-to-br from-[#FFFFFF] to-[#FAF6F2] 
                    dark:from-zinc-900 dark:to-zinc-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-zinc-700">
                <div class="flex flex-col md:flex-row items-center md:items-center gap-6">
                    
                    <!-- Avatar -->
                    <div class="w-28 h-28 rounded-full bg-gradient-to-tr from-brown-200 to-brown-400 dark:from-cyan-200 dark:to-cyan-400 text-brown-900 dark:text-cyan-900 flex items-center justify-center text-4xl font-bold shadow-inner">
                        {{ strtoupper(substr($author->username, 0, 1)) }}
                    </div>

                    <!-- Info -->
                    <div class="flex-1 text-center md:text-left">
                    <h1 class="text-2xl font-bold text-brown-900 dark:text-neutral-100">{{ $author->username }}</h1>
                    <!-- Tagline -->
                    <p class="text-brown-600 dark:text-cyan-600 text-sm mt-1">{{ $author->tagline }}</p>

                    <!-- Stats -->
                    <div class="flex flex-wrap justify-center md:justify-start gap-4 mt-4 text-sm text-gray-700 dark:text-gray-300">
                        <span>📝 <strong>{{ $author->public_post_count }}</strong> Posts</span>
                        <span>👥 <strong>{{ $author->followers_count }}</strong> Followers</span>
                        <span>🫱 <strong>{{ $author->followings_count }}</strong> Followings</span>
                        <span>📅 Joined <strong>{{ $author->created_at->format('M. j, Y') }}</strong></span>
                    </div>

                    <!-- Action -->

                    @auth('web')
                        @if (auth('web')->id() !== $author->id)
                            <div class="mt-4">
                                @if (!$isFollowing)
                                    <!-- Default button -->
                                    <flux:button variant="primary" wire:click="toggleFollow">
                                        Follow
                                    </flux:button>
                                @else
                                    <!-- Following button styled as primary or filled -->
                                    <flux:button wire:click="toggleFollow">
                                        Following
                                    </flux:button>
                                @endif
                            </div>
                        @else
                            <div class="mt-4">
                                <flux:button tag="a" href="{{ url('/dashboard') }}"  variant="primary"  icon:trailing="arrow-up-right">
                                    View Dashboard
                                </flux:button>
                            </div>
                        @endif
                    @endauth
                    
                    </div>
                </div>
                </div>

                <!-- About Section -->
                <div class="mt-10 border-t border-gray-200 pt-8">
                    <h2 class="text-2xl font-semibold text-brown-900 dark:text-neutral-100 mb-4">About the Author</h2>
                    <p class="text-gray-700 dark:text-neutral-200 leading-relaxed">{{ $author->bio }}</p>
                </div>

                @if($this->posts->count())
                    <!-- Recent Posts Section -->
                    <div class="space-y-10 mt-10 border-t border-gray-200 pt-8">
                        <h2 class="text-2xl font-semibold text-brown-900 dark:text-neutral-100 mb-4">Posts by <span class="text-brown-600 dark:text-cyan-300">{{ $author->username }}</span></h2>

                        @foreach ($this->posts as $post)
                            <x-public.post-card>
                                <a href="/posts/{{ $post->id }}" class="hover:underline dark:text-neutral-100 text-brown-800">
                                    <x-public.post-heading class="text-xl">{{ $post->title }}</x-public.post-heading>
                                </a>

                                <x-public.post-subheading>
                                    <a href="/authors/{{ $post->user->id }}" 
                                        class="font-medium transition-all hover:underline hover:font-bold duration-200 ease-in-out">
                                            {{ $post->user->username }}
                                    </a> • {{ $post->created_at->diffForHumans() }}
                                </x-public.post-subheading>

                                <x-public.post-content>{{ $post->content }}</x-public.post-content>

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
                    </div>
                @else

                    <div class="flex flex-col items-center justify-center text-center py-12 border border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
                        <div class="flex flex-col items-center space-y-3">
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-neutral-200">
                                Nothing here for now
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm">
                                There are no posts to show at the moment.
                            </p>
                        </div>
                    </div>

                @endif

            </div>

            {{-- Side Bar --}}
            @include('partials.public.sidebar')

        </div>
        </div>
    </section>
</div>
