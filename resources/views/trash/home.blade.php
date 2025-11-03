@include('partials.public.header')

 <!-- Hero -->
<section class="relative min-h-[100vh] flex flex-col items-center text-center px-6 bg-cover bg-no-repeat"
         style="background-image: url('/storage/img/hero-bg-4.jpg'); background-position: 50% 50%;">

    <!-- Overlay with opacity -->
    {{-- <div class="absolute inset-0 bg-black/10"></div> <!-- 20% black overlay, adjust as needed --> --}}

    <!-- Content -->
    <div class="relative z-10 mt-35 lg:mt-40">
        <x-public.hero-heading>
          Untold Within
        </x-public.hero-heading>

        <x-public.body-text class="max-w-2xl mb-6">
            A safe corner of the internet where you can finally share the thoughts you never say out loud. 
            Post anonymously, read others’ untold words, and realize you’re not alone. 
        </x-public.body-text>

        <div class="flex gap-4 justify-center">
          <x-public.button href="{{ route('register') }}">
            Start Posting
          </x-public.button>
          <x-public.button href="{{ route('login') }}" :background="'transparent'">
             Sign In
          </x-public.button>
        </div>
    </div>
</section>

  <!-- Posts Section -->
<section id="posts" class="py-16 bg-gray-50">
  <div class="container mx-auto px-6">
    <x-public.section-heading class="text-center">Popular Posts</x-public.section-heading>

    <div class="grid gap-8 md:grid-cols-2">
      
      <!-- Post 1 -->
      <x-public.post-card>
        <x-public.post-card-title>When Silence Feels Safer</x-public.post-card-title>
        <x-public.post-card-subtitle>Anonymous Dreamer • 2 hours ago</x-public.post-card-subtitle>
        <x-public.post-card-content>
          I wanted to tell someone how exhausted I am pretending everything is fine, 
            but every time I open my mouth, the words get stuck in my throat. 
            Silence feels safer than being misunderstood.
        </x-public.post-card-content>

        <!-- Tags -->
        <div class="mt-3 flex flex-wrap gap-2">
          <x-public.post-tag>#Loneliness</x-public.post-tag>
          <x-public.post-tag>#Anxiety</x-public.post-tag>
          <x-public.post-tag>#Overthinking</x-public.post-tag>
        </div>

        <div class="mt-4">
            <a href="/posts/1" class="text-[#A67C52] hover:underline font-medium block mb-3">Read More →</a>
            <div class="flex items-center gap-6 text-gray-500 text-sm justify-end">

            <div class="flex items-center gap-1">
                <x-public.heroicons :icon="'heart'"/>
                <span>34</span>
            </div>
            <div class="flex items-center gap-1">
                <x-public.heroicons :icon="'save'"/>
                </svg>
                <span>12</span>
            </div>
            <div class="flex items-center gap-1">
                <x-public.heroicons :icon="'comment'"/>
                <span>5</span>
            </div>
            <div class="flex items-center gap-1">
                <x-public.heroicons :icon="'views'"/>
                <span>220</span>
            </div>
            </div>
        </div>
      </x-public.post-card>

      <!-- Post 2 -->
      <x-public.post-card>
        <x-public.post-card-title>When Silence Feels Safer</x-public.post-card-title>
        <x-public.post-card-subtitle>Anonymous Dreamer • 2 hours ago</x-public.post-card-subtitle>
        <x-public.post-card-content>
          I wanted to tell someone how exhausted I am pretending everything is fine, 
            but every time I open my mouth, the words get stuck in my throat. 
            Silence feels safer than being misunderstood.
        </x-public.post-card-content>

        <!-- Tags -->
        <div class="mt-3 flex flex-wrap gap-2">
          <x-public.post-tag>#Loneliness</x-public.post-tag>
          <x-public.post-tag>#Anxiety</x-public.post-tag>
          <x-public.post-tag>#Overthinking</x-public.post-tag>
        </div>

        <div class="mt-4">
            <a href="/posts/1" class="text-[#A67C52] hover:underline font-medium block mb-3">Read More →</a>
            <div class="flex items-center gap-6 text-gray-500 text-sm justify-end">

            <div class="flex items-center gap-1">
                <x-public.heroicons :icon="'heart'"/>
                <span>34</span>
            </div>
            <div class="flex items-center gap-1">
                <x-public.heroicons :icon="'save'"/>
                </svg>
                <span>12</span>
            </div>
            <div class="flex items-center gap-1">
                <x-public.heroicons :icon="'comment'"/>
                <span>5</span>
            </div>
            <div class="flex items-center gap-1">
                <x-public.heroicons :icon="'views'"/>
                <span>220</span>
            </div>
            </div>
        </div>
      </x-public.post-card>

      <!-- Post 3 -->
      <x-public.post-card>
        <x-public.post-card-title>When Silence Feels Safer</x-public.post-card-title>
        <x-public.post-card-subtitle>Anonymous Dreamer • 2 hours ago</x-public.post-card-subtitle>
        <x-public.post-card-content>
          I wanted to tell someone how exhausted I am pretending everything is fine, 
            but every time I open my mouth, the words get stuck in my throat. 
            Silence feels safer than being misunderstood.
        </x-public.post-card-content>

        <!-- Tags -->
        <div class="mt-3 flex flex-wrap gap-2">
          <x-public.post-tag>#Loneliness</x-public.post-tag>
          <x-public.post-tag>#Anxiety</x-public.post-tag>
          <x-public.post-tag>#Overthinking</x-public.post-tag>
        </div>

        <div class="mt-4">
            <a href="/posts/1" class="text-[#A67C52] hover:underline font-medium block mb-3">Read More →</a>
            <div class="flex items-center gap-6 text-gray-500 text-sm justify-end">

            <div class="flex items-center gap-1">
                <x-public.heroicons :icon="'heart'"/>
                <span>34</span>
            </div>
            <div class="flex items-center gap-1">
                <x-public.heroicons :icon="'save'"/>
                </svg>
                <span>12</span>
            </div>
            <div class="flex items-center gap-1">
                <x-public.heroicons :icon="'comment'"/>
                <span>5</span>
            </div>
            <div class="flex items-center gap-1">
                <x-public.heroicons :icon="'views'"/>
                <span>220</span>
            </div>
            </div>
        </div>
      </x-public.post-card>

      <!-- Post 4 -->
      <x-public.post-card>
        <x-public.post-card-title>When Silence Feels Safer</x-public.post-card-title>
        <x-public.post-card-subtitle>Anonymous Dreamer • 2 hours ago</x-public.post-card-subtitle>
        <x-public.post-card-content>
          I wanted to tell someone how exhausted I am pretending everything is fine, 
            but every time I open my mouth, the words get stuck in my throat. 
            Silence feels safer than being misunderstood.
        </x-public.post-card-content>

        <!-- Tags -->
        <div class="mt-3 flex flex-wrap gap-2">
          <x-public.post-tag>#Loneliness</x-public.post-tag>
          <x-public.post-tag>#Anxiety</x-public.post-tag>
          <x-public.post-tag>#Overthinking</x-public.post-tag>
        </div>

        <div class="mt-4">
            <a href="/posts/1" class="text-[#A67C52] hover:underline font-medium block mb-3">Read More →</a>
            <div class="flex items-center gap-6 text-gray-500 text-sm justify-end">

            <div class="flex items-center gap-1">
                <x-public.heroicons :icon="'heart'"/>
                <span>34</span>
            </div>
            <div class="flex items-center gap-1">
                <x-public.heroicons :icon="'save'"/>
                </svg>
                <span>12</span>
            </div>
            <div class="flex items-center gap-1">
                <x-public.heroicons :icon="'comment'"/>
                <span>5</span>
            </div>
            <div class="flex items-center gap-1">
                <x-public.heroicons :icon="'views'"/>
                <span>220</span>
            </div>
            </div>
        </div>
      </x-public.post-card>

      <!-- Post 5 -->
      <x-public.post-card>
        <x-public.post-card-title>When Silence Feels Safer</x-public.post-card-title>
        <x-public.post-card-subtitle>Anonymous Dreamer • 2 hours ago</x-public.post-card-subtitle>
        <x-public.post-card-content>
          I wanted to tell someone how exhausted I am pretending everything is fine, 
            but every time I open my mouth, the words get stuck in my throat. 
            Silence feels safer than being misunderstood.
        </x-public.post-card-content>

        <!-- Tags -->
        <div class="mt-3 flex flex-wrap gap-2">
          <x-public.post-tag>#Loneliness</x-public.post-tag>
          <x-public.post-tag>#Anxiety</x-public.post-tag>
          <x-public.post-tag>#Overthinking</x-public.post-tag>
        </div>

        <div class="mt-4">
            <a href="/posts/1" class="text-[#A67C52] hover:underline font-medium block mb-3">Read More →</a>
            <div class="flex items-center gap-6 text-gray-500 text-sm justify-end">

            <div class="flex items-center gap-1">
                <x-public.heroicons :icon="'heart'"/>
                <span>34</span>
            </div>
            <div class="flex items-center gap-1">
                <x-public.heroicons :icon="'save'"/>
                </svg>
                <span>12</span>
            </div>
            <div class="flex items-center gap-1">
                <x-public.heroicons :icon="'comment'"/>
                <span>5</span>
            </div>
            <div class="flex items-center gap-1">
                <x-public.heroicons :icon="'views'"/>
                <span>220</span>
            </div>
            </div>
        </div>
      </x-public.post-card>

      <!-- Post 6 -->
      <x-public.post-card>
        <x-public.post-card-title>When Silence Feels Safer</x-public.post-card-title>
        <x-public.post-card-subtitle>Anonymous Dreamer • 2 hours ago</x-public.post-card-subtitle>
        <x-public.post-card-content>
          I wanted to tell someone how exhausted I am pretending everything is fine, 
            but every time I open my mouth, the words get stuck in my throat. 
            Silence feels safer than being misunderstood.
        </x-public.post-card-content>

        <!-- Tags -->
        <div class="mt-3 flex flex-wrap gap-2">
          <x-public.post-tag>#Loneliness</x-public.post-tag>
          <x-public.post-tag>#Anxiety</x-public.post-tag>
          <x-public.post-tag>#Overthinking</x-public.post-tag>
        </div>

        <div class="mt-4">
            <a href="/posts/1" class="text-[#A67C52] hover:underline font-medium block mb-3">Read More →</a>
            <div class="flex items-center gap-6 text-gray-500 text-sm justify-end">

            <div class="flex items-center gap-1">
                <x-public.heroicons :icon="'heart'"/>
                <span>34</span>
            </div>
            <div class="flex items-center gap-1">
                <x-public.heroicons :icon="'save'"/>
                </svg>
                <span>12</span>
            </div>
            <div class="flex items-center gap-1">
                <x-public.heroicons :icon="'comment'"/>
                <span>5</span>
            </div>
            <div class="flex items-center gap-1">
                <x-public.heroicons :icon="'views'"/>
                <span>220</span>
            </div>
            </div>
        </div>
      </x-public.post-card>

    </div>
  </div>
</section>


@include('partials.public.community-numbers')
@include('partials.public.cta')
@include('partials.public.footer')
