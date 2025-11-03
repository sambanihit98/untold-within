@include('partials.public.header')

    <!-- Hero About -->
    <section class="relative pt-48 pb-24 bg-gradient-to-b from-[#FDFBF9] to-white">
        <div class="container mx-auto px-6 max-w-4xl text-center">

            <x-public.hero-heading>
                About <span class="text-[#A67C52]">Untold Within</span>
            </x-public.hero-heading>

            <x-public.body-text class="max-w-2xl mx-auto">
                Everyone carries thoughts, feelings, and stories they never say out loud.  
                <span class="font-semibold text-[#A67C52]">Untold Within</span> is a safe corner of the internet where you can finally let them out.  
                Share anonymously, read others’ voices, and find comfort knowing you’re not alone.
            </x-public.body-text>

        </div>
    </section>

    <!-- Our Mission -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6 max-w-6xl grid md:grid-cols-2 gap-16 items-center">
            <div>
                <x-public.section-heading>
                    Our Mission
                </x-public.section-heading>

                <x-public.body-text>
                    Life can feel heavy when we keep our emotions locked inside.  
                    Our mission is to create a sanctuary where you can release your innermost thoughts—without judgment or exposure.  
                    By sharing and reading others’ untold words, you may discover reflection, empathy, and healing.
                </x-public.body-text>

            </div>
            <div class="bg-[#F5F1EB] rounded-2xl p-10 shadow-xl">
                <p class="text-2xl italic font-medium text-[#4B3B2A] leading-relaxed">
                    "Sometimes the most powerful words are the ones we never say out loud."
                </p>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-20 bg-gray-50 ">
        <div class="container mx-auto px-6 max-w-6xl">
            
            <x-public.section-heading>
                How It Works
            </x-public.section-heading>

            <div class="grid md:grid-cols-3 gap-10 text-center">
                <div class="p-8 bg-white rounded-2xl shadow-lg hover:shadow-xl transition">
                    <h3 class="text-xl font-semibold text-[#4B3B2A] mb-3">1. Share</h3>
                    <x-public.body-text>
                        Write your thoughts and feelings anonymously.  
                        No names, no faces—just pure, honest expression.
                    </x-public.body-text>
                </div>
                <div class="p-8 bg-white rounded-2xl shadow-lg hover:shadow-xl transition">
                    <h3 class="text-xl font-semibold text-[#4B3B2A] mb-3">2. Connect</h3>
                    <x-public.body-text>
                       Read posts from others who may be feeling the same.  
                        Discover comfort in knowing you’re not alone.
                    </x-public.body-text>
                </div>
                <div class="p-8 bg-white rounded-2xl shadow-lg hover:shadow-xl transition">
                    <h3 class="text-xl font-semibold text-[#4B3B2A] mb-3">3. Reflect</h3>
                     <x-public.body-text>
                       Let stories inspire healing, empathy, and a deeper understanding of the human experience.
                    </x-public.body-text>
                </div>
            </div>
        </div>
    </section>

@include('partials.public.cta')
@include('partials.public.footer')
