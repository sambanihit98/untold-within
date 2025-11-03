@include('partials.public.header')

  <section id="all-posts" class="mb-20 mt-40">
    <div class="container mx-auto px-6">
      <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Posts Area -->
        <div class="lg:col-span-3 space-y-8">

          <!-- Breadcrumbs -->
          <nav class="text-sm mb-6 border-b border-gray-200 pb-5">
            <ol class="flex items-center space-x-2">
              <li>
                <a href="/" class="text-[#A67C52] font-medium hover:underline">Home</a>
              </li>
              <li class="text-gray-400">›</li>
              <li class="bg-gradient-to-r from-[#A67C52]/10 to-transparent px-2 py-1 rounded text-[#4B3B2A] font-semibold">
                Posts
              </li>
            </ol>
          </nav>
          
          <!-- Heading -->
          <x-public.section-heading>All Posts</x-public.section-heading>
          
          <!-- Search Bar -->
          <div>
            <form action="{{ route('posts') }}" method="GET" class="flex w-full">
              <input 
                type="text" 
                name="search" 
                placeholder="Search post..." 
                class="flex-1 px-4 py-2 rounded-l-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-100"
              >
              <button type="submit" class="px-4 py-2 rounded-r-lg bg-[#A67C52] text-white font-medium hover:bg-[#8B5E3C] transition cursor-pointer">
                Search
              </button>
            </form>
          </div>

          <!-- Post Cards Grid -->
          <div class="space-y-10 mt-8">

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
                <x-public.post-tag>Loneliness</x-public.post-tag>
                <x-public.post-tag>Anxiety</x-public.post-tag>
                <x-public.post-tag>Overthinking</x-public.post-tag>
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
                <x-public.post-tag>Loneliness</x-public.post-tag>
                <x-public.post-tag>Anxiety</x-public.post-tag>
                <x-public.post-tag>Overthinking</x-public.post-tag>
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
                <x-public.post-tag>Loneliness</x-public.post-tag>
                <x-public.post-tag>Anxiety</x-public.post-tag>
                <x-public.post-tag>Overthinking</x-public.post-tag>
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
                <x-public.post-tag>Loneliness</x-public.post-tag>
                <x-public.post-tag>Anxiety</x-public.post-tag>
                <x-public.post-tag>Overthinking</x-public.post-tag>
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
                <x-public.post-tag>Loneliness</x-public.post-tag>
                <x-public.post-tag>Anxiety</x-public.post-tag>
                <x-public.post-tag>Overthinking</x-public.post-tag>
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

        {{-- Side Bar --}}
        @include('partials.public.sidebar')

      </div>
    </div>
  </section>
   
@include('partials.public.cta')
@include('partials.public.footer')
