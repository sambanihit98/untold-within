<!-- Header -->
<header 
    x-data="{ 
        scrolled: false, 
        dark: document.documentElement.classList.contains('dark') 
    }"
    x-init="
        // Detect scroll
        window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 });
        
        // Watch for dark mode toggle from Flux
        const observer = new MutationObserver(() => {
        dark = document.documentElement.classList.contains('dark');
        });
        observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    "
    :class="scrolled 
        ? (dark ? 'bg-zinc-900 shadow-md' : 'bg-white shadow-md') 
        : 'bg-transparent'"
    class="fixed top-0 w-full z-50 transition-colors duration-300"
    >
    
    <div class="container mx-auto px-6 py-6 flex items-center justify-between">
        <a href="{{ url('/') }}" 
        class="text-xl font-bold transition-colors duration-300 text-brown-600 dark:text-neutral-100 dark:hover:text-cyan-200">
            Untold Within
        </a>

        <nav class="hidden md:flex items-center gap-6">
            <x-public.nav-link href="{{ url('/') }}" :active="request()->is('/')">Home</x-public.nav-link>
            <x-public.nav-link href="{{ url('/about') }}" :active="request()->is('about')">About</x-public.nav-link>
            <x-public.nav-link href="{{ url('/posts') }}" :active="request()->is('posts', 'posts/*')">Posts</x-public.nav-link>
            <x-public.nav-link href="{{ url('/authors') }}" :active="request()->is('authors', 'authors/*')">Authors</x-public.nav-link>
            <x-public.nav-link href="{{ url('/topics') }}" :active="request()->is('topics', 'topics/*')">Topics</x-public.nav-link>

            @auth
                <flux:button tag="a" href="{{ url('/dashboard') }}"  variant="primary">
                    Dashboard
                </flux:button>
            @endauth

            @guest
                <x-public.nav-link href="{{ url('/login') }}" :active="request()->is('login')">Login</x-public.nav-link>

                <flux:button tag="a" href="{{ url('/register') }}"  variant="primary">
                    Register
                </flux:button>
            @endguest

            <flux:dropdown x-data align="end">
                <flux:button variant="subtle" square class="group" aria-label="Preferred color scheme">
                    <flux:icon.sun x-show="$flux.appearance === 'light'" variant="mini" class="text-zinc-500 dark:text-white" />
                    <flux:icon.moon x-show="$flux.appearance === 'dark'" variant="mini" class="text-zinc-500 dark:text-white" />
                    <flux:icon.moon x-show="$flux.appearance === 'system' && $flux.dark" variant="mini" />
                    <flux:icon.sun x-show="$flux.appearance === 'system' && ! $flux.dark" variant="mini" />
                </flux:button>

                <flux:menu>
                    <flux:menu.item icon="sun" x-on:click="$flux.appearance = 'light'">Light</flux:menu.item>
                    <flux:menu.item icon="moon" x-on:click="$flux.appearance = 'dark'">Dark</flux:menu.item>
                    <flux:menu.item icon="computer-desktop" x-on:click="$flux.appearance = 'system'">System</flux:menu.item>
                </flux:menu>
            </flux:dropdown>
            
        </nav>

        <!-- Mobile Menu Button -->
        <button id="menu-toggle" 
            class="md:hidden flex items-center text-gray-700 dark:text-gray-300 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <!-- Mobile Dropdown Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white dark:bg-gray-800 shadow-md">
        <nav class="flex flex-col px-6 py-4 space-y-3">
            <x-public.nav-link href="{{ url('/') }}" :active="request()->is('/')">Home</x-public.nav-link>
            <x-public.nav-link href="{{ url('/about') }}" :active="request()->is('about')">About</x-public.nav-link>
            <x-public.nav-link href="{{ url('/posts') }}" :active="request()->is('posts')">Posts</x-public.nav-link>
            <x-public.nav-link href="{{ url('/authors') }}" :active="request()->is('authors')">Authors</x-public.nav-link>
            
            @auth
                <x-public.nav-link href="{{ url('/dashboard') }}" :element="'button'" class="text-center">Dashboard</x-public.nav-link>
            @endauth

            @guest
                <x-public.nav-link href="{{ url('/login') }}" :active="request()->is('login')">Login</x-public.nav-link>
                <x-public.nav-link href="{{ url('/register') }}" :active="request()->is('register')" :element="'button'" class="text-center">Register</x-public.nav-link>
            @endguest
        </nav>
    </div>
</header>