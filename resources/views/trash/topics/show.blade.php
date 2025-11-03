@include('partials.public.header')

<section class="mb-20 mt-40">
  <div class="container mx-auto px-6">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-10">
      
      <!-- Main Content -->
      <div class="lg:col-span-3 space-y-10">

        <!-- Breadcrumbs -->
        <nav class="text-sm border-b border-gray-200 pb-4">
          <ol class="flex items-center space-x-2">
            <li>
              <a href="/" class="text-[#A67C52] font-medium hover:underline">Home</a>
            </li>
            <li class="text-gray-400">›</li>
            <li>
              <a href="/topics" class="text-[#A67C52] font-medium hover:underline">Topics</a>
            </li>
            <li class="text-gray-400">›</li>
            <li class="bg-gradient-to-r from-[#A67C52]/10 to-transparent px-2 py-1 rounded text-[#4B3B2A] font-semibold">
              Emotions
            </li>
          </ol>
        </nav>

        <!-- Topic Header -->
        <div>
          <x-public.section-heading>Emotions</x-public.section-heading>
          <p class="text-gray-600 mt-3 leading-relaxed">
            Dive into heartfelt emotions — stories of love, sadness, excitement, and everything that makes us human. 
            Discover reflections that connect to the very core of what we feel, think, and experience every day.
          </p>
        </div>

        <!-- Related Tags -->
        <div>
          <h3 class="text-lg font-semibold text-[#4B3B2A] mb-4">Related Tags</h3>
          <div class="flex flex-wrap gap-2">
            <x-public.post-tag href="/topics/1/1">Happiness</x-public.post-tag>
            <x-public.post-tag href="/topics/1/1">Sadness</x-public.post-tag>
            <x-public.post-tag href="/topics/1/1">Love</x-public.post-tag>
            <x-public.post-tag href="/topics/1/1">Fear</x-public.post-tag>
            <x-public.post-tag href="/topics/1/1">Excitement</x-public.post-tag>
          </div>
        </div>

        <!-- Posts under this Topic -->
        <div class="space-y-10 mt-8">
          <h3 class="text-lg font-semibold text-[#4B3B2A]">Stories & Reflections</h3>

          <!-- Post 1 -->
          <x-public.post-card>
            <x-public.post-card-title>When Silence Feels Safer</x-public.post-card-title>
            <x-public.post-card-subtitle>Anonymous Dreamer • 2 hours ago</x-public.post-card-subtitle>
            <x-public.post-card-content>
              I wanted to tell someone how exhausted I am pretending everything is fine, 
              but every time I open my mouth, the words get stuck in my throat. 
              Silence feels safer than being misunderstood.
            </x-public.post-card-content>

            <div class="mt-3 flex flex-wrap gap-2">
              <x-public.post-tag>Loneliness</x-public.post-tag>
              <x-public.post-tag>Anxiety</x-public.post-tag>
              <x-public.post-tag>Overthinking</x-public.post-tag>
            </div>

            <div class="mt-4">
              <a href="/posts/1" class="text-[#A67C52] hover:underline font-medium block mb-3">Read More →</a>
              <div class="flex items-center gap-6 text-gray-500 text-sm justify-end">
                <div class="flex items-center gap-1">
                  <x-public.heroicons :icon="'heart'" />
                  <span>34</span>
                </div>
                <div class="flex items-center gap-1">
                  <x-public.heroicons :icon="'save'" />
                  <span>12</span>
                </div>
                <div class="flex items-center gap-1">
                  <x-public.heroicons :icon="'comment'" />
                  <span>5</span>
                </div>
                <div class="flex items-center gap-1">
                  <x-public.heroicons :icon="'views'" />
                  <span>220</span>
                </div>
              </div>
            </div>
          </x-public.post-card>

          <!-- Post 2 -->
          <x-public.post-card>
            <x-public.post-card-title>The Weight of Unspoken Words</x-public.post-card-title>
            <x-public.post-card-subtitle>By Someone Who Feels Too Much • 1 day ago</x-public.post-card-subtitle>
            <x-public.post-card-content>
              Sometimes emotions don’t need to be fixed — they just need to be felt. 
              The unspoken words we carry often say more than we ever could.
            </x-public.post-card-content>

            <div class="mt-3 flex flex-wrap gap-2">
              <x-public.post-tag>Sadness</x-public.post-tag>
              <x-public.post-tag>Reflection</x-public.post-tag>
              <x-public.post-tag>Healing</x-public.post-tag>
            </div>

            <div class="mt-4">
              <a href="/posts/2" class="text-[#A67C52] hover:underline font-medium block mb-3">Read More →</a>
              <div class="flex items-center gap-6 text-gray-500 text-sm justify-end">
                <div class="flex items-center gap-1">
                  <x-public.heroicons :icon="'heart'" />
                  <span>57</span>
                </div>
                <div class="flex items-center gap-1">
                  <x-public.heroicons :icon="'save'" />
                  <span>18</span>
                </div>
                <div class="flex items-center gap-1">
                  <x-public.heroicons :icon="'comment'" />
                  <span>9</span>
                </div>
                <div class="flex items-center gap-1">
                  <x-public.heroicons :icon="'views'" />
                  <span>310</span>
                </div>
              </div>
            </div>
          </x-public.post-card>

          <!-- Post 3 -->
          <x-public.post-card>
            <x-public.post-card-title>Joy in the Small Moments</x-public.post-card-title>
            <x-public.post-card-subtitle>Lighthearted Soul • 3 days ago</x-public.post-card-subtitle>
            <x-public.post-card-content>
              Sometimes joy hides in the quiet moments — morning coffee, shared laughter, 
              or simply realizing you’ve made it this far. Happiness doesn’t have to be loud.
            </x-public.post-card-content>

            <div class="mt-3 flex flex-wrap gap-2">
              <x-public.post-tag>Happiness</x-public.post-tag>
              <x-public.post-tag>Gratitude</x-public.post-tag>
              <x-public.post-tag>Peace</x-public.post-tag>
            </div>

            <div class="mt-4">
              <a href="/posts/3" class="text-[#A67C52] hover:underline font-medium block mb-3">Read More →</a>
              <div class="flex items-center gap-6 text-gray-500 text-sm justify-end">
                <div class="flex items-center gap-1">
                  <x-public.heroicons :icon="'heart'" />
                  <span>89</span>
                </div>
                <div class="flex items-center gap-1">
                  <x-public.heroicons :icon="'save'" />
                  <span>24</span>
                </div>
                <div class="flex items-center gap-1">
                  <x-public.heroicons :icon="'comment'" />
                  <span>12</span>
                </div>
                <div class="flex items-center gap-1">
                  <x-public.heroicons :icon="'views'" />
                  <span>560</span>
                </div>
              </div>
            </div>
          </x-public.post-card>

        </div>

      </div>

      <!-- Sidebar -->
      @include('partials.public.sidebar')

    </div>
  </div>
</section>

@include('partials.public.cta')
@include('partials.public.footer')
