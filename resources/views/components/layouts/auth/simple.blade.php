@include('partials.header')

    <body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">

        <!-- Header Menu -->
        {{-- @include('partials.public.header-menu') --}}

        <div class="bg-background flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">

            @if (request()->is('login', 'admin/login'))
                <div class="flex w-full max-w-sm flex-col gap-2"> {{-- max-w-sm on login --}}
                    <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                        <span class="flex h-9 w-9 mb-1 items-center justify-center rounded-md">
                            {{-- <x-app-logo-icon class="size-9 fill-current text-black dark:text-white" /> --}}
                        </span>
                        <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                    </a>
                    <div class="flex flex-col gap-6">
                        {{ $slot }}
                    </div>
                </div>
            @elseif (request()->is('register'))
                <div class="flex w-full max-w-[50vw] flex-col gap-2 mt-10"> {{-- max-w-sm on login --}}
                    <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                        <span class="flex h-9 w-9 mb-1 items-center justify-center rounded-md">
                            {{-- <x-app-logo-icon class="size-9 fill-current text-black dark:text-white" /> --}}
                        </span>
                        <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                    </a>
                    <div class="flex flex-col gap-6">
                        {{ $slot }}
                    </div>
                </div>
            @endif
            
        </div>
        @fluxScripts
    </body>
</html>
