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
              <li>
                <a href="/posts" class="text-[#A67C52] font-medium hover:underline">Posts</a>
              </li>
              <li class="text-gray-400">›</li>
              <li class="bg-gradient-to-r from-[#A67C52]/10 to-transparent px-2 py-1 rounded text-[#4B3B2A] font-semibold">
                Drowning in a Smile
              </li>
            </ol>
          </nav>
          
          <!-- Heading -->
          <x-public.section-heading>Drowning in a Smile</x-public.section-heading>

          <!-- Meta Info -->
          <div class="flex items-center gap-4 text-sm text-gray-500">
            <div class="flex items-center gap-2">
              <div class="w-10 h-10 rounded-full bg-[#EFE6DD] flex items-center justify-center font-semibold text-[#4B3B2A]">
                S
              </div>
              <span class="font-medium text-[#4B3B2A]">Silent Angel</span>
            </div>
            <span>•</span>
            <span>Posted on October 3, 2025</span>
          </div>

          <!-- Content -->
          <div class="text-lg leading-relaxed text-[#4B3B2A] space-y-4">
            <p>
              They say a smile can hide a thousand secrets, and maybe mine hides even more.  
              Each laugh I share with the world is a carefully painted mask, concealing the storm that brews inside me.  
              People see joy in my eyes, but they never ask about the weight my heart carries.
            </p>
            <p>
              I drown silently in my own thoughts, lost in the waves of what-ifs and regrets.  
              Yet, I keep smiling — not because I am okay, but because I don’t want anyone to feel the burden of my darkness.  
              My smile is my armor, and also my prison.
            </p>
            <p>
              Maybe one day someone will look past the curve of my lips and see the truth in my silence.  
              Until then, I’ll keep drowning in a smile — because sometimes, that’s the only way to survive.
            </p>
          </div>

          <!-- Tags -->
          <div class="flex flex-wrap gap-2 mt-6">
            <x-public.post-tag>Loneliness</x-public.post-tag>
            <x-public.post-tag>Anxiety</x-public.post-tag>
            <x-public.post-tag>Overthinking</x-public.post-tag>
          </div>

           <!-- Post Stats -->
          <div class="flex items-center gap-6 text-gray-500 text-sm border-t pt-4">
            <div class="flex items-center gap-1">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a5.5 5.5 0 017.778 0L12 6.586l-.096-.096a5.5 5.5 0 117.778 7.778L12 21.364l-7.682-7.682a5.5 5.5 0 010-7.778z"/></svg>
              <span>102</span>
            </div>
            <div class="flex items-center gap-1">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5v14l7-4 7 4V5H5z"/></svg>
              <span>37</span>
            </div>
            <div class="flex items-center gap-1">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
              <span>19</span>
            </div>
            <div class="flex items-center gap-1">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              <span>440</span>
            </div>
          </div>

          <!-- Comment Section -->
          <div class="mt-10" x-data>
            <h3 class="text-2xl font-semibold text-[#4B3B2A] mb-6">Comments</h3>

            <!-- Comment Form -->
            <form action="#" method="POST" class="mb-8">
              <textarea 
                name="comment" 
                rows="3" 
                placeholder="Share your thoughts..." 
                class="w-full p-4 border rounded-lg focus:ring-2 focus:ring-[#A67C52] focus:outline-none"
              ></textarea>
              <button 
                type="submit" 
                class="mt-3 px-5 py-2 bg-[#A67C52] text-white rounded-lg hover:bg-[#8B653F] transition"
              >
                Post Comment
              </button>
            </form>

            <!-- Comments List -->
            <div class="space-y-8">
              
              <!-- Single Comment -->
              <div class="comment" x-data="{ open: false }">
                <div class="flex items-start gap-3">
                  <div class="w-10 h-10 rounded-full bg-[#EFE6DD] flex items-center justify-center font-semibold text-[#4B3B2A]">
                    H
                  </div>
                  <div>
                    <p class="font-medium text-[#4B3B2A]">Hidden Soul</p>
                    <p class="text-sm text-gray-500">October 3, 2025</p>
                    <p class="mt-2 text-[#4B3B2A]">
                      This really hit me. Smiling outside while breaking inside is something I know too well.  
                      Thank you for putting it into words 💔
                    </p>

                    <!-- Reply Button -->
                    <button 
                      @click="open = !open" 
                      class="mt-2 text-sm text-[#A67C52] hover:underline"
                    >
                      Reply
                    </button>

                    <!-- Reply Form -->
                    <form 
                      x-show="open" 
                      x-transition 
                      action="#" 
                      method="POST" 
                      class="mt-4 pl-8"
                    >
                      <textarea 
                        name="reply" 
                        rows="2" 
                        placeholder="Write your reply..." 
                        class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-[#A67C52] focus:outline-none"
                      ></textarea>
                      <button 
                        type="submit" 
                        class="mt-2 px-4 py-1 bg-[#A67C52] text-white rounded-lg hover:bg-[#8B653F] transition"
                      >
                        Post Reply
                      </button>
                    </form>

                    <!-- Replies -->
                    <div class="mt-4 pl-8 border-l border-gray-200 space-y-4">
                      <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-[#EFE6DD] flex items-center justify-center font-semibold text-[#4B3B2A]">
                          R
                        </div>
                        <div>
                          <p class="font-medium text-[#4B3B2A]">Restless Heart</p>
                          <p class="text-sm text-gray-500">October 3, 2025</p>
                          <p class="mt-1 text-[#4B3B2A]">
                            I feel the same way, you put it into words so perfectly. Sending you strength 💜
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Another Top-Level Comment -->
              <div class="comment" x-data="{ open: false }">
                <div class="flex items-start gap-3">
                  <div class="w-10 h-10 rounded-full bg-[#EFE6DD] flex items-center justify-center font-semibold text-[#4B3B2A]">
                    S
                  </div>
                  <div>
                    <p class="font-medium text-[#4B3B2A]">Silent Echo</p>
                    <p class="text-sm text-gray-500">October 1, 2025</p>
                    <p class="mt-2 text-[#4B3B2A]">
                      This post really reflects the hidden battles we all go through. Thank you for sharing this.
                    </p>

                    <!-- Reply Button -->
                    <button 
                      @click="open = !open" 
                      class="mt-2 text-sm text-[#A67C52] hover:underline"
                    >
                      Reply
                    </button>

                    <!-- Reply Form -->
                    <form 
                      x-show="open" 
                      x-transition 
                      action="#" 
                      method="POST" 
                      class="mt-4 pl-8"
                    >
                      <textarea 
                        name="reply" 
                        rows="2" 
                        placeholder="Write your reply..." 
                        class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-[#A67C52] focus:outline-none"
                      ></textarea>
                      <button 
                        type="submit" 
                        class="mt-2 px-4 py-1 bg-[#A67C52] text-white rounded-lg hover:bg-[#8B653F] transition"
                      >
                        Post Reply
                      </button>
                    </form>
                  </div>
                </div>
              </div>

            </div>
          </div>

          <!-- More from Author Section -->
          <div class="mt-16 border-t pt-4">
            <h3 class="text-2xl font-semibold text-[#4B3B2A] mb-6">More from Silent Angel</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              
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


          <!-- Related Post Section -->
          <div class="mt-16 border-t pt-4">
            <h3 class="text-2xl font-semibold text-[#4B3B2A] mb-6">Related Post</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              
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

        </div>

        {{-- Side Bar --}}
        @include('partials.public.sidebar')

      </div>
    </div>
  </section>
   
@include('partials.public.cta')
@include('partials.public.footer')
