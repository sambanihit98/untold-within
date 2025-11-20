<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Computed;
use App\Models\User;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination;

    public $search = '';
    
    #[Computed]
    public function followings(){
        $user = auth('web')->user();

        return $user->followings()
                    ->when($this->search, fn($q) =>
                        $q->where('username', 'like', "%{$this->search}%")
                    )
                    ->withCount('followers')
                    ->latest()
                    ->paginate(12);
    }

    #[Computed]
    public function totalFollowings(){
        $user = auth('web')->user();

        return $user->followings->count();
    }

    public function highlight($text)
    {
        if (!$this->search) return $text;

        $pattern = '/' . preg_quote($this->search, '/') . '/i';
        return preg_replace($pattern, '<mark class="bg-yellow-200 text-black font-semibold">$0</mark>', e($text));
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

}; ?>

<div class="flex flex-col gap-6 p-6">

     <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Following</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Heading Title --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Following') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Stay connected with the people and creators you follow.') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <!-- Following Overview -->
    <div class="flex items-center justify-between">
        <p class="text-gray-600 dark:text-gray-400">
            <span class="font-semibold text-brown-900 dark:text-neutral-100">{{ $this->totalFollowings; }}</span> Total Following
        </p>

        <!-- Search Bar -->
        <div class="relative">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search"
                placeholder="Search following..." 
                class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 focus:ring-2 focus:ring-brown-600 dark:focus:ring-cyan-600 focus:outline-none w-64 dark:text-neutral-200"
            >
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" />
            </svg>
        </div>
    </div>

    <div class="mt-5">
        <!-- Results summary -->
        @if($search)
            @if($this->followings->count() > 0)
                <p class="text-sm text-gray-500 mt-2">
                    Showing <strong>{{ $this->followings->total() }}</strong> {{ Str::plural('result', $this->followings->total()) }}
                </p>
            @else
                <div class="flex flex-col items-center justify-center text-center py-12 border border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
                    <div class="flex flex-col items-center space-y-3">
                        <h3 class="text-lg font-semibold text-gray-700 dark:text-neutral-200">
                            No followings found for "<span class="font-semibold">{{ $search }}</span>".
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm">
                            We couldn’t find any followings matching your search. Try using different keywords.
                        </p>
                    </div>
                </div>
            @endif
        @endif
    </div>

    @if ($this->followings->count())

        <!-- Following List -->
        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-gray-200 dark:border-neutral-700 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Following Card -->
                @foreach ($this->followings as $following)
                    <div class="flex items-center gap-4 p-4 border rounded-xl bg-white dark:bg-neutral-900 border-gray-200 dark:border-neutral-700 shadow-sm hover:shadow-md transition">
                        <!-- Avatar -->
                        <div class="w-12 h-12 flex-none rounded-full bg-brown-50 dark:bg-cyan-50 text-brown-900 dark:text-cyan-900 font-bold text-3xl flex items-center justify-center">
                            {{ strtoupper(substr($following->username, 0, 1)) }}
                        </div>

                        <div class="flex-1">
                            <p class="font-semibold text-brown-900 dark:text-neutral-100">
                                {!! $this->highlight($following->username) !!}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">Joined {{ $following->created_at->format('M. d, Y') }}</p>
                        </div>
                        <flux:button variant="outline" tag="a" href="/authors/{{ $following->id }}">View Profile</flux:button>
                    </div>
                @endforeach
            </div>
        </div>

    @else

        @if(!$search)
            <div class="flex flex-col items-center justify-center text-center py-12 border border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
                <div class="flex flex-col items-center space-y-3">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-neutral-200">
                        Not Following Anyone
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm">
                        Follow others to see their latest posts and activities.
                    </p>
                </div>
            </div>
        @endif

    @endif

</div>
