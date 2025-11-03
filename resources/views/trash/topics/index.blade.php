@include('partials.public.header')

  <section id="all-topics" class="mb-20 mt-40">
    <div class="container mx-auto px-6">
      <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- topics Area -->
        <div class="lg:col-span-3 space-y-8">

          <!-- Breadcrumbs -->
          <nav class="text-sm mb-6 border-b border-gray-200 pb-5">
            <ol class="flex items-center space-x-2">
              <li>
                <a href="/" class="text-[#A67C52] font-medium hover:underline">Home</a>
              </li>
              <li class="text-gray-400">›</li>
              <li class="bg-gradient-to-r from-[#A67C52]/10 to-transparent px-2 py-1 rounded text-[#4B3B2A] font-semibold">
                Topics
              </li>
            </ol>
          </nav>
          
          <!-- Heading -->
          <x-public.section-heading>Browse by Topic</x-public.section-heading>

          <!-- Intro Text -->
          <p class="text-gray-600 leading-relaxed">
            Dive into our collection of topics and find posts that match your interests. 
            Whether you're exploring insights, guides, or inspiring stories — these tags 
            will help you discover more of what you love.
          </p>

          <!-- Topics Section -->
          <div class="space-y-14 mt-10">

            <!-- Emotions -->
            <div class="border border-gray-100 rounded-2xl shadow-sm p-6 hover:shadow-md transition">
              <div class="flex items-center justify-between mb-5">
                <h3 class="text-xl font-semibold text-[#4B3B2A]">Emotions</h3>
                <a href="/topics/1" class="text-sm text-[#A67C52] font-medium hover:underline">
                  View All Posts →
                </a>
              </div>
              <p class="text-sm text-gray-600 mb-4 leading-relaxed">
                Dive into heartfelt emotions — stories of love, sadness, excitement, and everything that makes us human.
              </p>
              <div class="flex flex-wrap gap-3">
                <a href="/tags/love" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Love</a>
                <a href="/tags/sadness" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Sadness</a>
                <a href="/tags/happiness" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Happiness</a>
                <a href="/tags/fear" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Fear</a>
                <a href="/tags/anger" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Anger</a>
                <a href="/tags/jealousy" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Jealousy</a>
                <a href="/tags/confusion" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Confusion</a>
                <a href="/tags/excitement" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Excitement</a>
              </div>
            </div>

            <!-- Healing & Growth -->
            <div class="border border-gray-100 rounded-2xl shadow-sm p-6 hover:shadow-md transition">
              <div class="flex items-center justify-between mb-5">
                <h3 class="text-xl font-semibold text-[#4B3B2A]">Healing & Growth</h3>
                <a href="/topics/1" class="text-sm text-[#A67C52] font-medium hover:underline">
                  View All Posts →
                </a>
              </div>
              <p class="text-sm text-gray-600 mb-4 leading-relaxed">
                Explore lessons of self-discovery, acceptance, and finding strength through change.
              </p>
              <div class="flex flex-wrap gap-3">
                <a href="/tags/hope" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Hope</a>
                <a href="/tags/healing" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Healing</a>
                <a href="/tags/selflove" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Self-Love</a>
                <a href="/tags/forgiveness" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Forgiveness</a>
                <a href="/tags/movingon" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Moving On</a>
                <a href="/tags/gratitude" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Gratitude</a>
                <a href="/tags/acceptance" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Acceptance</a>
                <a href="/tags/strength" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Strength</a>
              </div>
            </div>

            <!-- Life Reflections -->
            <div class="border border-gray-100 rounded-2xl shadow-sm p-6 hover:shadow-md transition">
              <div class="flex items-center justify-between mb-5">
                <h3 class="text-xl font-semibold text-[#4B3B2A]">Life Reflections</h3>
                <a href="/topics/1" class="text-sm text-[#A67C52] font-medium hover:underline">
                  View All Posts →
                </a>
              </div>
              <p class="text-sm text-gray-600 mb-4 leading-relaxed">
                Look back, ponder, and grow through memories, regrets, and the moments that shaped who we are today.
              </p>
              <div class="flex flex-wrap gap-3">
                <a href="/tags/memories" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Memories</a>
                <a href="/tags/regret" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Regret</a>
                <a href="/tags/nostalgia" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Nostalgia</a>
                <a href="/tags/reflection" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Reflection</a>
                <a href="/tags/dreams" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Dreams</a>
                <a href="/tags/reality" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Reality</a>
              </div>
            </div>

            <!-- Relationships -->
            <div class="border border-gray-100 rounded-2xl shadow-sm p-6 hover:shadow-md transition">
              <div class="flex items-center justify-between mb-5">
                <h3 class="text-xl font-semibold text-[#4B3B2A]">Relationships</h3>
                <a href="/topics/1" class="text-sm text-[#A67C52] font-medium hover:underline">
                  View All Posts →
                </a>
              </div>
              <p class="text-sm text-gray-600 mb-4 leading-relaxed">
                Explore the beauty and pain of connections — from friendship and trust to heartbreak and unspoken goodbyes.
              </p>
              <div class="flex flex-wrap gap-3">
                <a href="/tags/friendship" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Friendship</a>
                <a href="/tags/trust" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Trust</a>
                <a href="/tags/betrayal" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Betrayal</a>
                <a href="/tags/missingyou" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Missing You</a>
                <a href="/tags/unspokenwords" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Unspoken Words</a>
                <a href="/tags/goodbye" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Goodbye</a>
              </div>
            </div>

            <!-- Mental and Emotional States -->
            <div class="border border-gray-100 rounded-2xl shadow-sm p-6 hover:shadow-md transition">
              <div class="flex items-center justify-between mb-5">
                <h3 class="text-xl font-semibold text-[#4B3B2A]">Mental and Emotional States</h3>
                <a href="/topics/1" class="text-sm text-[#A67C52] font-medium hover:underline">
                  View All Posts →
                </a>
              </div>
              <p class="text-sm text-gray-600 mb-4 leading-relaxed">
                A glimpse into the mind’s quiet corners — the weight of anxiety, emptiness, and the pursuit of emotional balance.
              </p>
              <div class="flex flex-wrap gap-3">
                <a href="/tags/anxiety" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Anxiety</a>
                <a href="/tags/depression" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Depression</a>
                <a href="/tags/emptiness" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Emptiness</a>
                <a href="/tags/overthinking" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Overthinking</a>
                <a href="/tags/pressure" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Pressure</a>
                <a href="/tags/burnout" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Burnout</a>
              </div>
            </div>

            <!-- Motivation & Purpose -->
            <div class="border border-gray-100 rounded-2xl shadow-sm p-6 hover:shadow-md transition">
              <div class="flex items-center justify-between mb-5">
                <h3 class="text-xl font-semibold text-[#4B3B2A]">Motivation & Purpose</h3>
                <a href="/topics/1" class="text-sm text-[#A67C52] font-medium hover:underline">
                  View All Posts →
                </a>
              </div>
              <p class="text-sm text-gray-600 mb-4 leading-relaxed">
                Stories that ignite hope — finding faith, determination, and meaning in life’s challenges and aspirations.
              </p>
              <div class="flex flex-wrap gap-3">
                <a href="/tags/faith" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Faith</a>
                <a href="/tags/determination" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Determination</a>
                <a href="/tags/dreams" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Dreams</a>
                <a href="/tags/inspiration" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Inspiration</a>
                <a href="/tags/purpose" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Purpose</a>
                <a href="/tags/resilience" class="bg-[#A67C52]/10 text-[#A67C52] px-4 py-2 rounded-full text-sm font-medium hover:bg-[#A67C52]/20 transition">Resilience</a>
              </div>
            </div>

          </div>

         <!-- Note or CTA -->
          <div class="mt-10 bg-[#A67C52]/5 border border-[#A67C52]/20 rounded-xl p-6">
            <h4 class="font-semibold text-[#4B3B2A] mb-2">Can’t find the topic you’re looking for?</h4>
            <p class="text-gray-600 text-sm">
              Explore posts by topic to discover stories that speak to you — or visit our 
              <a href="/posts" class="text-[#A67C52] font-medium hover:underline">Posts</a> 
              page to see every shared thought and emotion.
            </p>
          </div>

        </div>

        {{-- Side Bar --}}
        @include('partials.public.sidebar')

      </div>
    </div>
  </section>
   
@include('partials.public.cta')
@include('partials.public.footer')
