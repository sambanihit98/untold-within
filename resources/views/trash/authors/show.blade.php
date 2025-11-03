@include('partials.public.header')

  <section id="author-area" class="mb-20 mt-40">
    <div class="container mx-auto px-6">
      <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Author Area -->
        <div class="lg:col-span-3 space-y-8">

            <!-- Breadcrumbs -->
            <nav class="text-sm mb-6 border-b border-gray-200 pb-5">
                <ol class="flex items-center space-x-2">
                <li>
                    <a href="/" class="text-[#A67C52] font-medium hover:underline">Home</a>
                </li>
                <li class="text-gray-400">›</li>
                <li>
                    <a href="/authors" class="text-[#A67C52] font-medium hover:underline">Authors</a>
                </li>
                <li class="text-gray-400">›</li>
                <li class="bg-gradient-to-r from-[#A67C52]/10 to-transparent px-2 py-1 rounded text-[#4B3B2A] font-semibold">
                    Anonymous Shark
                </li>
                </ol>
            </nav>

            <!-- Author Profile Info -->
            <!-- Profile Header -->
            <div class="bg-[#F8F5F1] p-6 rounded-2xl shadow-sm">
            <div class="flex flex-col md:flex-row items-center md:items-center gap-6">
                
                <!-- Avatar -->
                <div class="w-28 h-28 rounded-full bg-[#EFE6DD] text-[#4B3B2A] flex items-center justify-center text-4xl font-bold shadow-inner">
                A
                </div>

                <!-- Info -->
                <div class="flex-1 text-center md:text-left">
                <h1 class="text-2xl font-bold text-[#4B3B2A]">Anonymous Shark</h1>
                <!-- Tagline -->
                <p class="text-[#A67C52] text-sm mt-1 italic">Finding meaning in quiet moments and shared emotions.</p>

                <!-- Stats -->
                <div class="flex flex-wrap justify-center md:justify-start gap-4 mt-4 text-sm text-gray-600">
                    <span>📝 <strong>28</strong> Posts</span>
                    <span>👥 <strong>2.3k</strong> Followers</span>
                    <span>📅 Joined <strong>May 2023</strong></span>
                </div>

                <!-- Action -->
                <div class="mt-4">
                    <button class="bg-[#A67C52] hover:bg-[#8C653F] text-white font-medium px-5 py-2 rounded-full transition duration-300">
                    Follow
                    </button>
                </div>
                </div>
            </div>
            </div>

            <!-- About Section -->
            <div class="mt-10 border-t border-gray-200 pt-8">
                <h2 class="text-2xl font-semibold text-[#4B3B2A] mb-4">About the Author</h2>
                <p class="text-gray-700 leading-relaxed">
                Writing anonymously allows Anonymous Shark to speak openly about mental health, self-worth,
                and emotional growth. Their works are deeply personal yet universally relatable — offering a
                voice to those who struggle to express their innermost thoughts.
                </p>
            </div>

            <!-- Recent Posts Section -->
            <div class="space-y-10 mt-10 border-t border-gray-200 pt-8">
                <h2 class="text-2xl font-semibold text-[#4B3B2A] mb-4">Posts by Anonymous Shark</h2>

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

            </div>
        </div>

        {{-- Side Bar --}}
        @include('partials.public.sidebar')

      </div>
    </div>
  </section>
   
@include('partials.public.cta')
@include('partials.public.footer')
