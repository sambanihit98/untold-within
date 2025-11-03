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
                    Authors
                </li>
                </ol>
            </nav>

            <!-- Authors Section -->
            <div class="space-y-8">

                <!-- Heading -->
                <x-public.section-heading>The Hearts Behind the Words</x-public.section-heading>

                <!-- Search Bar -->
                <div>
                    <form action="{{ route('posts') }}" method="GET" class="flex w-full">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Search author..." 
                        class="flex-1 px-4 py-2 rounded-l-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-100"
                    >
                    <button type="submit" class="px-4 py-2 rounded-r-lg bg-[#A67C52] text-white font-medium hover:bg-[#8B5E3C] transition cursor-pointer">
                        Search
                    </button>
                    </form>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Author 1 -->
                    <div class="bg-white shadow-lg rounded-lg p-6 hover:shadow-2xl transition flex items-center gap-4">
                        <!-- Avatar -->
                        <div class="w-20 h-20 flex-none rounded-full bg-[#EFE6DD] text-[#4B3B2A] font-bold text-3xl flex items-center justify-center">
                            A
                        </div>

                        <!-- Author Info -->
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-gray-800">Angel in Disguise</h3>
                            {{-- <p class="text-sm text-gray-500">Emotional Guide</p> --}}
                            <p class="mt-1 text-sm text-gray-600">
                                Passionate about helping readers navigate emotions and build resilience.
                            </p>
                            <div class="mt-2 text-xs text-gray-500 space-y-1">
                                <p>📝 12 Articles Published</p>
                                <p>📅 Joined: March 2023</p>
                                <p>👥 1.2k Followers</p>
                            </div>
                            <a href="/authors/1" class="mt-3 inline-block text-[#A67C52] hover:underline font-medium">
                                Meet the Author →
                            </a>
                        </div>
                    </div>

                    <!-- Author 2 -->
                    <div class="bg-white shadow-lg rounded-lg p-6 hover:shadow-2xl transition flex items-center gap-4">
                        <!-- Avatar -->
                        <div class="w-20 h-20 flex-none rounded-full bg-[#EFE6DD] text-[#4B3B2A] font-bold text-3xl flex items-center justify-center">
                            S
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">Silent Angel</h3>
                            {{-- <p class="text-sm text-gray-500">Heartfelt Storyteller</p> --}}
                            <p class="mt-1 text-sm text-gray-600">
                                Writing about love, friendship, and meaningful human connections.
                            </p>
                            <div class="mt-2 text-xs text-gray-500 space-y-1">
                                <p>📝 9 Articles Published</p>
                                <p>📅 Joined: June 2023</p>
                                <p>👥 1.2k Followers</p>
                            </div>
                            <a href="/authors/1" class="mt-3 inline-block text-[#A67C52] hover:underline font-medium">
                                Meet the Author →
                            </a>
                        </div>
                    </div>

                    <!-- Author 3 -->
                    <div class="bg-white shadow-lg rounded-lg p-6 hover:shadow-2xl transition flex items-center gap-4">
                        <!-- Avatar -->
                        <div class="w-20 h-20 flex-none rounded-full bg-[#EFE6DD] text-[#4B3B2A] font-bold text-3xl flex items-center justify-center">
                            H
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">Hopeful Heart</h3>
                            {{-- <p class="text-sm text-gray-500">Motivation Expert</p> --}}
                            <p class="mt-1 text-sm text-gray-600">
                                Inspires readers to grow, achieve goals, and overcome challenges.
                            </p>
                            <div class="mt-2 text-xs text-gray-500 space-y-1">
                                <p>📝 15 Articles Published</p>
                                <p>📅 Joined: January 2022</p>
                                <p>👥 1.2k Followers</p>
                            </div>
                            <a href="/authors/1" class="mt-3 inline-block text-[#A67C52] hover:underline font-medium">
                                Meet the Author →
                            </a>
                        </div>
                    </div>

                    <!-- Author 4 -->
                    <div class="bg-white shadow-lg rounded-lg p-6 hover:shadow-2xl transition flex items-center gap-4">
                        <!-- Avatar -->
                        <div class="w-20 h-20 flex-none rounded-full bg-[#EFE6DD] text-[#4B3B2A] font-bold text-3xl flex items-center justify-center">
                            W
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">Wondeful Heart</h3>
                            {{-- <p class="text-sm text-gray-500">Mindful Thinker</p> --}}
                            <p class="mt-1 text-sm text-gray-600">
                                Shares tips on personal development, productivity, and mindfulness.
                            </p>
                            <div class="mt-2 text-xs text-gray-500 space-y-1">
                                <p>📝 8 Articles Published</p>
                                <p>📅 Joined: May 2023</p>
                                <p>👥 1.2k Followers</p>
                            </div>
                            <a href="/authors/1" class="mt-3 inline-block text-[#A67C52] hover:underline font-medium">
                                Meet the Author →
                            </a>
                        </div>
                    </div>

                    <!-- Author 1 -->
                    <div class="bg-white shadow-lg rounded-lg p-6 hover:shadow-2xl transition flex items-center gap-4">
                        <!-- Avatar -->
                        <div class="w-20 h-20 flex-none rounded-full bg-[#EFE6DD] text-[#4B3B2A] font-bold text-3xl flex items-center justify-center">
                            A
                        </div>

                        <!-- Author Info -->
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-gray-800">Angel in Disguise</h3>
                            {{-- <p class="text-sm text-gray-500">Emotional Guide</p> --}}
                            <p class="mt-1 text-sm text-gray-600">
                                Passionate about helping readers navigate emotions and build resilience.
                            </p>
                            <div class="mt-2 text-xs text-gray-500 space-y-1">
                                <p>📝 12 Articles Published</p>
                                <p>📅 Joined: March 2023</p>
                                <p>👥 1.2k Followers</p>
                            </div>
                            <a href="/authors/1" class="mt-3 inline-block text-[#A67C52] hover:underline font-medium">
                                Meet the Author →
                            </a>
                        </div>
                    </div>

                    <!-- Author 2 -->
                    <div class="bg-white shadow-lg rounded-lg p-6 hover:shadow-2xl transition flex items-center gap-4">
                        <!-- Avatar -->
                        <div class="w-20 h-20 flex-none rounded-full bg-[#EFE6DD] text-[#4B3B2A] font-bold text-3xl flex items-center justify-center">
                            S
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">Silent Angel</h3>
                            {{-- <p class="text-sm text-gray-500">Heartfelt Storyteller</p> --}}
                            <p class="mt-1 text-sm text-gray-600">
                                Writing about love, friendship, and meaningful human connections.
                            </p>
                            <div class="mt-2 text-xs text-gray-500 space-y-1">
                                <p>📝 9 Articles Published</p>
                                <p>📅 Joined: June 2023</p>
                                <p>👥 1.2k Followers</p>
                            </div>
                            <a href="/authors/1" class="mt-3 inline-block text-[#A67C52] hover:underline font-medium">
                                Meet the Author →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Side Bar --}}
        @include('partials.public.sidebar')

      </div>
    </div>
  </section>
   
@include('partials.public.cta')
@include('partials.public.footer')
