@include('partials.header')

    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('home') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            @if(auth('web')->check())
                <flux:navlist variant="outline" class="gap-y-5">
                    <flux:navlist.group :heading="__('Platform')" class="grid">
                        {{-- <flux:navlist.item icon="home"        :href="route('home')" :current="request()->routeIs('home')" wire:navigate>{{ __('Home') }}</flux:navlist.item> --}}
                        <flux:navlist.item icon="layout-grid" :href="route('dashboard')"   :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                        <flux:navlist.item icon="folder"      :href="route('my-posts')"    :current="request()->routeIs('my-posts', 'my-posts.*')" wire:navigate>{{ __('My Posts') }}</flux:navlist.item>
                        <flux:navlist.item icon="heart"       :href="route('liked-posts')" :current="request()->routeIs('liked-posts')" wire:navigate>{{ __('Liked Posts') }}</flux:navlist.item>
                        <flux:navlist.item icon="bookmark"    :href="route('saved-posts')" :current="request()->routeIs('saved-posts')" wire:navigate>{{ __('Saved Posts') }}</flux:navlist.item>
                    </flux:navlist.group>

                    <flux:navlist.group :heading="__('Social / Community')" class="grid">
                        <flux:navlist.item icon="users"     :href="route('followers')" :current="request()->routeIs('followers')" wire:navigate>{{ __('Followers') }}</flux:navlist.item>
                        <flux:navlist.item icon="user-plus" :href="route('following')" :current="request()->routeIs('following')"  wire:navigate>{{ __('Following') }}</flux:navlist.item>
                        {{-- <flux:navlist.item icon="inbox" wire:navigate>{{ __('Messages | Inbox') }}</flux:navlist.item> --}}
                        <flux:navlist.item icon="bell" :href="route('notifications')" :current="request()->routeIs('notifications', 'notifications.*')" wire:navigate>
                            {{ __('Notifications') }}   
                        </flux:navlist.item>

                        <flux:navlist.item icon="megaphone" :href="route('news-and-alerts')" :current="request()->routeIs('news-and-alerts', 'news-and-alerts.*')" wire:navigate>
                            {{ __('News & Alerts') }}   
                        </flux:navlist.item>
                    </flux:navlist.group>
                </flux:navlist>
            @elseif (auth('admin')->check())
                <flux:navlist variant="outline" class="gap-y-5">
                    <!-- Dashboard & Overview -->
                    <flux:navlist.group :heading="__('Overview')" class="grid">
                        <flux:navlist.item 
                            icon="rectangle-group" 
                            :href="route('admin.dashboard')" 
                            :current="request()->routeIs('admin.dashboard')" 
                            wire:navigate>
                            {{ __('Dashboard') }}
                        </flux:navlist.item>
                    </flux:navlist.group>

                    <!-- Content Management -->
                    <flux:navlist.group :heading="__('Content Management')" class="grid">
                        <flux:navlist.item 
                            icon="rectangle-group" 
                            :href="route('admin.topics')" 
                            :current="request()->routeIs('admin.topics', 'admin.topics.*')" 
                            wire:navigate>
                            {{ __('Topics') }}
                        </flux:navlist.item>

                        <flux:navlist.item 
                            icon="megaphone" 
                            :href="route('admin.news-and-alerts')" 
                            :current="request()->routeIs('admin.news-and-alerts', 'admin.news-and-alerts.*')" 
                            wire:navigate>
                            {{ __('News & Alerts') }}
                        </flux:navlist.item>
                    </flux:navlist.group>

                    <!-- User Management -->
                    <flux:navlist.group :heading="__('User Management')" class="grid">
                        <flux:navlist.item icon="users" :href="route('admin.users-management')" :current="request()->routeIs('admin.users-management', 'admin.users-management.*')" wire:navigate>
                            {{ __('Users Management') }}
                        </flux:navlist.item>

                        <flux:navlist.item icon="chat-bubble-bottom-center-text" :href="route('admin.feedbacks')" :current="request()->routeIs('admin.feedbacks', 'admin.feedbacks.*')" wire:navigate>
                            {{ __('Feedbacks') }}
                        </flux:navlist.item>

                        <flux:navlist.item icon="envelope" :href="route('admin.newsletter')" :current="request()->routeIs('admin.newsletter', 'admin.newsletter.*')" wire:navigate>
                            {{ __('Newsletter') }}
                        </flux:navlist.item>
                    </flux:navlist.group>

                    <!-- System & Settings -->
                    <flux:navlist.group :heading="__('System Settings')" class="grid">
                        <flux:navlist.item icon="list-bullet" :href="route('admin.activity-logs')" :current="request()->routeIs('admin.activity-logs', 'admin.activity-logs.*')" wire:navigate>
                            {{ __('Activity Logs') }}
                        </flux:navlist.item>

                        <flux:navlist.item icon="cog-8-tooth" wire:navigate>
                            {{ __('System Configuration') }}
                        </flux:navlist.item>
                    </flux:navlist.group>

                </flux:navlist>
            @endif

            <flux:spacer />

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :username="auth()->user()->username"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->username }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        @if(auth('web')->check())
                            <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                        @elseif (auth('admin')->check())
                            <flux:menu.item :href="route('admin.settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                        @endif
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
            
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->username }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
