<?php

use App\Models\Post;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.public.app', ['title' => 'Home | Untold Within'])] class extends Component {
    
    #[Computed]
    public function posts(){
        return Post::latest()
                ->where('is_public', true)
                ->with(['user', 'topics', 'tags'])
                ->take(10)
                ->get();
    }

}; ?>

<div>
    <!-- Hero -->
             
<section
  x-data="{ dark: document.documentElement.classList.contains('dark') }"
  x-init="
    const observer = new MutationObserver(() => {
      dark = document.documentElement.classList.contains('dark');
    });
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
  "
  x-bind:style="dark
    ? 'background-image: url(/storage/img/hero-bg-dark.png); background-position: 50% 50%; background-size: cover; background-repeat: no-repeat;'
    : 'background-image: url(/storage/img/hero-bg-4.jpg); background-position: 50% 50%; background-size: cover; background-repeat: no-repeat;'"
  class="relative min-h-[100vh] flex flex-col items-center text-center px-6 transition-all duration-500"
>


        <!-- Overlay with opacity -->
        {{-- <div class="absolute inset-0 bg-black/10"></div> <!-- 20% black overlay, adjust as needed --> --}}

        <!-- Content -->
        <div class="relative z-10 mt-35 lg:mt-40">
            <x-public.hero-heading>
            Untold Within
            </x-public.hero-heading>

            <x-public.text class="max-w-2xl mb-6">
                A safe corner of the internet where you can finally share the thoughts you never say out loud. 
                Post anonymously, read others’ untold words, and realize you’re not alone. 
            </x-public.text>

            <div class="flex gap-4 justify-center">

                <flux:button tag="a" href="{{ route('register') }}"  variant="primary">
                    Start Posting
                </flux:button>

                @guest
                    <flux:button tag="a" href="{{ route('login') }}"  variant="primary">
                        Sign In
                    </flux:button>
                @endguest
        
            </div>
        </div>
    </section>

    <!-- Posts Section -->
    <section id="posts" class="py-16 bg-gray-50 dark:bg-zinc-800">
    <div class="container mx-auto px-6">
        {{-- <x-public.section-heading class="text-center">Latest Posts</x-public.section-heading> --}}

        {{-- Heading Title --}}
    <div class="relative mb-6 w-full text-center">
        <flux:heading size="xl" level="1">{{ __('Latest Posts') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('A collection of my thoughts, stories, and shared moments.') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

        <div class="grid gap-8 md:grid-cols-2">
        
            @foreach ($this->posts as $post)
                <x-public.post-card>
                    <a href="posts/{{ $post->id }}" class="hover:underline dark:text-neutral-100 text-brown-800">
                        <x-public.post-heading class="text-xl">{{ $post->title }}</x-public.post-heading>
                    </a>
                    <x-public.post-subheading>
                        <a href="/authors/{{ $post->user->id }}" class="font-medium transition-all hover:underline hover:font-bold duration-200 ease-in-out">{{ $post->user->username }}</a> • {{ $post->created_at->diffForHumans() }}
                    </x-public.post-subheading>

                    <x-public.post-content class="line-clamp-3">
                        {{$post->content}}
                    </x-public.post-content>
                

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
                </x-public.post-card>
            @endforeach
        </div>
    </div>
    </section>

    {{-- @include('partials.public.community-numbers') --}}
    @include('partials.public.cta')
        
</div>
