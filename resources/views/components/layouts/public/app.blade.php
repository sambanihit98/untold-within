@include('partials.header')

<body class="bg-white dark:bg-zinc-800 text-gray-800 antialiased flex flex-col min-h-screen">

    <!-- Header Menu -->
    @include('partials.public.header-menu')

    {{ $slot }}

    <!-- Footer -->
    <footer class="bg-[#4B3B2A] dark:bg-zinc-900 text-[#F5F1EB] py-8 mt-auto">
        <div class="container mx-auto px-6 flex flex-col md:flex-row justify-between items-center">
            <p>&copy; {{ date('Y') }} Untold Within. All rights reserved.</p>
            <div class="flex gap-4 mt-4 md:mt-0">
                <a href="#" class="hover:text-[#D1BFA3]">Privacy</a>
                <a href="#" class="hover:text-[#D1BFA3]">Terms</a>
                <a href="#about" class="hover:text-[#D1BFA3]">About</a>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('menu-toggle').addEventListener('click', () => {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>

    {{-- pop up meassage / toast --}}
    <livewire:components.toast />

    @fluxScripts
    
</body>
</html>