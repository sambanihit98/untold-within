<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

use App\Models\User;
use App\Models\Post;
use App\Models\Like;
use App\Models\Notification;
use Livewire\Attributes\Computed;
use Carbon\Carbon;

new #[Layout('components.layouts.app', ['title' => 'Dashboard'])]  class extends Component {
    
    public $user;
    public $posts;
    public $monthlyPosts;
    public $monthlyFollowers;
    public $monthlyFollowings;

    public function mount()
    {
        $user = auth('web')->user();

        // Fetch user with follower/following/post counts
        $this->user = User::withCount(['followers', 'followings', 'post'])
            ->find($user->id);

        // Fetch all posts by this user
        $this->posts = Post::where('user_id', $user->id)
            ->withCount(['likes', 'saves', 'comments', 'views'])
            ->get();

        // Fetch posts only for current month
        $this->monthlyPosts = Post::where('user_id', $user->id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->withCount(['likes', 'saves', 'comments', 'views'])
            ->get();

        // Followers gained this month
        $this->monthlyFollowers = $user->followers()
            ->whereMonth('user_follows.created_at', Carbon::now()->month)
            ->whereYear('user_follows.created_at', Carbon::now()->year)
            ->count();

        //Followings gained this month
        $this->monthlyFollowings = $user->followings()
            ->whereMonth('user_follows.created_at', Carbon::now()->month)
            ->whereYear('user_follows.created_at', Carbon::now()->year)
            ->count();
    }

    #[Computed]
    public function totalPublicPosts()
    {
        return $this->posts->where('is_public', true)->count();
    }

    #[Computed]
    public function totalPrivatePosts()
    {
        return $this->posts->where('is_public', false)->count();
    }

    #[Computed]
    public function totalLikes()
    {
        return $this->posts->sum('likes_count');
    }

    #[Computed]
    public function totalSaves()
    {
        return $this->posts->sum('saves_count');
    }

    #[Computed]
    public function totalComments()
    {
        return $this->posts->sum('comments_count');
    }

    #[Computed]
    public function totalViews()
    {
        return $this->posts->sum('views_count');
    }

    //CURRENT MONTH TOTALS -----
    #[Computed]
    public function totalPublicPostsThisMonth() { return $this->monthlyPosts->where('is_public', true)->count(); }

    #[Computed]
    public function totalPrivatePostsThisMonth() { return $this->monthlyPosts->where('is_public', false)->count(); }

    #[Computed]
    public function totalLikesThisMonth() { return $this->monthlyPosts->sum('likes_count'); }

    #[Computed]
    public function totalSavesThisMonth() { return $this->monthlyPosts->sum('saves_count'); }

    #[Computed]
    public function totalCommentsThisMonth() { return $this->monthlyPosts->sum('comments_count'); }

    #[Computed]
    public function totalViewsThisMonth() { return $this->monthlyPosts->sum('views_count'); }

    #[Computed] 
    public function totalFollowersThisMonth() { return $this->monthlyFollowers; }

    #[Computed] 
    public function totalFollowingsThisMonth() { return $this->monthlyFollowings; }

    // -------------------------------------------------------------------------------------
    // -------------------------------------------------------------------------------------
    // Notification Logic
    #[Computed]
    public function recentNotifications(){
        $recentNotifications = Notification::where('user_id', $this->user->id)
                                ->latest()
                                ->take(5)
                                ->get();

                                // Decode details and fetch post title dynamically
        foreach ($recentNotifications as $notification) {
            $details = json_decode($notification->details, true);
            
            if (isset($details['post_id'])) {
                $post = Post::find($details['post_id']);
                $details['post_title'] = $post ? $post->title : '(Post deleted)';
            }

            $notification->details = $details;
        }

        return $recentNotifications;
    }

    public function markAsRead($id){
        $notification = Notification::where('id', $id);
        $notification->update(['is_read' => true]);

        $this->dispatch('refresh');
    }
    // -------------------------------------------------------------------------------------
    // -------------------------------------------------------------------------------------

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        //Pop up message / Toast
        $this->dispatch('showToast', 'A new verification link has been sent to your email address', 'info');
    }

}; ?>

<div class="flex flex-col p-6">


    @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
        <!-- Email Verification Alert (Static) -->
        <div class="mb-6 p-6 border-l-4 border-yellow-500 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                    <!-- Alert Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M4.93 19h14.14a2 2 0 001.73-3L13.73 4.99a2 2 0 00-3.46 0L3.2 16a2 2 0 001.73 3z" />
                    </svg>

                    <!-- Alert Text -->
                    <div>
                        <p class="text-sm text-yellow-800 dark:text-yellow-300 font-medium">
                            Your email address is not verified.
                        </p>
                        <p class="text-xs text-yellow-700 dark:text-yellow-400 mt-1">
                            Please verify your email within <strong>14 days</strong> to avoid automatic account deletion.
                        </p>
                    </div>
                </div>

                <!-- Verify Button -->
               <flux:button variant="primary" wire:click.prevent="resendVerificationNotification" class="cursor-pointer">Verify Email</flux:button>

            </div>
        </div>
    @endif

    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item>Dashboard</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Heading Title --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Dashboard') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Manage your content, track updates, and stay organized.') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <!-- Profile Overview -->
    <div class="mt-10 flex flex-col md:flex-row gap-6 items-start 
        bg-gradient-to-br from-[#FFFFFF] to-[#FAF6F2] 
        dark:from-zinc-900 dark:to-zinc-800
        p-6 rounded-2xl border border-gray-100 dark:border-zinc-700 shadow-sm">

        <!-- Profile Avatar -->
        <div class="flex-shrink-0">
            <div class="w-24 h-24 rounded-full 
                bg-gradient-to-tr from-brown-200 to-brown-400
                dark:from-cyan-200 dark:to-cyan-400
                flex items-center justify-center text-3xl font-bold text-brown-900 dark:text-cyan-900 shadow-lg">
                {{ strtoupper(substr($user->username ?? 'U', 0, 1)) }}
            </div>
        </div>

        <!-- User Info -->
        <div class="flex-1">
            <h2 class="text-2xl font-semibold text-brown-900 dark:text-neutral-100">
                {{ $user->username ?? 'User Name' }}
            </h2>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $user->email }}</p>

            <!-- Tagline -->
            <p class="text-brown-600 dark:text-cyan-700 text-sm mt-1">{{ $user->tagline }}</p>

            <p class="mt-4 text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                {{ $user->bio ?? 'No bio yet. Share something about yourself!' }}
            </p>

            <!-- Stats -->
            <div class="flex flex-wrap gap-4 mt-5 text-sm text-gray-700 dark:text-gray-300">
                <div class="flex items-center gap-1">
                    📝 <strong>{{ $user->post_count ?? 0 }}</strong> <span>Posts</span>
                </div>
                <div class="flex items-center gap-1">
                    👥 <strong>{{ $user->followers_count ?? 0 }}</strong> <span>Followers</span>
                </div>
                <div class="flex items-center gap-1">
                    🫱 <strong>{{ $user->followings_count ?? 0 }}</strong> <span>Following</span>
                </div>
                <div class="flex items-center gap-1">
                    📅 Joined <strong>{{ $user->created_at->format('M. j, Y') }}</strong>
                </div>
            </div>
        </div>

        <!-- Edit Button -->
        <div>
            <flux:button tag="a" href="/settings/profile" variant="primary" class="shadow-sm hover:shadow-md transition-all duration-200">
                Edit Profile
            </flux:button>
        </div>
    </div>

    <!-- Profile Overview -->
    <div class="mt-10">
        <h3 class="text-lg font-semibold text-brown-900 dark:text-neutral-100 mb-4">Profile Overview</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            
            <!-- Followers -->
            <div class="p-5 border rounded-2xl bg-gradient-to-br from-white to-brown-50 dark:from-zinc-900 dark:to-zinc-800 border-gray-200 dark:border-zinc-700 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Followers</p>
                    <flux:icon name="user-group" class="w-5 h-5 text-brown-600" />
                </div>
                <p class="text-3xl font-semibold text-brown-900 dark:text-neutral-100">{{ $user->followers_count }}</p>
                <p class="text-xs text-gray-400 mt-1">+{{ $this->totalFollowersThisMonth }} this month</p>
            </div>

            <!-- Following -->
            <div class="p-5 border rounded-2xl bg-gradient-to-br from-white to-brown-50 dark:from-zinc-900 dark:to-zinc-800 border-gray-200 dark:border-zinc-700 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Following</p>
                    <flux:icon name="user-plus" class="w-5 h-5 text-brown-600" />
                </div>
                <p class="text-3xl font-semibold text-brown-900 dark:text-neutral-100">{{ $user->followings_count }}</p>
                <p class="text-xs text-gray-400 mt-1">+{{ $this->totalFollowingsThisMonth }} this month</p>
            </div>

            <!-- Public Posts -->
            <div class="p-5 border rounded-2xl bg-gradient-to-br from-white to-brown-50 dark:from-zinc-900 dark:to-zinc-800 border-gray-200 dark:border-zinc-700 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Public Posts</p>
                    <flux:icon name="globe-alt" class="w-5 h-5 text-brown-600" />
                </div>
                <p class="text-3xl font-semibold text-brown-900 dark:text-neutral-100">{{ $this->totalPublicPosts }}</p>
                <p class="text-xs text-gray-400 mt-1">+{{ $this->totalPublicPostsThisMonth }} this month</p>
            </div>

            <!-- Private Posts -->
            <div class="p-5 border rounded-2xl bg-gradient-to-br from-white to-brown-50 dark:from-zinc-900 dark:to-zinc-800 border-gray-200 dark:border-zinc-700 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Private Posts</p>
                    <flux:icon name="lock-closed" class="w-5 h-5 text-brown-600" />
                </div>
                <p class="text-3xl font-semibold text-brown-900 dark:text-neutral-100">{{ $this->totalPrivatePosts }}</p>
                <p class="text-xs text-gray-400 mt-1">+{{ $this->totalPrivatePostsThisMonth }} this month</p>
            </div>
        </div>
    </div>

    <!-- Post Insights -->
    <div class="mt-10">
        <h3 class="text-lg font-semibold text-brown-900 dark:text-neutral-100 mb-4">Post Insights</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

            <!-- Likes -->
            <div class="p-5 border rounded-2xl bg-white dark:bg-zinc-900 border-gray-200 dark:border-zinc-700 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Likes</p>
                    <flux:icon name="heart" class="w-5 h-5 text-red-500" />
                </div>
                <p class="text-3xl font-semibold text-brown-900 dark:text-neutral-100">{{  $this->totalLikes }}</p>
                <p class="text-xs text-gray-400 mt-1">+{{ $this->totalLikesThisMonth }} this month</p>
            </div>

            <!-- Saves -->
            <div class="p-5 border rounded-2xl bg-white dark:bg-zinc-900 border-gray-200 dark:border-zinc-700 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Saves</p>
                    <flux:icon name="bookmark" class="w-5 h-5 text-yellow-600" />
                </div>
                <p class="text-3xl font-semibold text-brown-900 dark:text-neutral-100">{{  $this->totalSaves }}</p>
                <p class="text-xs text-gray-400 mt-1">+{{ $this->totalSavesThisMonth }} this month</p>
            </div>

            <!-- Comments -->
            <div class="p-5 border rounded-2xl bg-white dark:bg-zinc-900 border-gray-200 dark:border-zinc-700 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Comments</p>
                    <flux:icon name="chat-bubble-left-right" class="w-5 h-5 text-blue-500" />
                </div>
                <p class="text-3xl font-semibold text-brown-900 dark:text-neutral-100">{{  $this->totalComments }}</p>
                <p class="text-xs text-gray-400 mt-1">+{{ $this->totalCommentsThisMonth }} this month</p>
            </div>

            <!-- Views -->
            <div class="p-5 border rounded-2xl bg-white dark:bg-zinc-900 border-gray-200 dark:border-zinc-700 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Views</p>
                    <flux:icon name="eye" class="w-5 h-5 text-green-600" />
                </div>
                <p class="text-3xl font-semibold text-brown-900 dark:text-neutral-100">{{  $this->totalViews }}</p>
                <p class="text-xs text-gray-400 mt-1">+{{ $this->totalViewsThisMonth }} this month</p>
            </div>
        </div>
    </div>
    
    <!-- Notifications -->
    <div class="mt-10">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-semibold text-brown-900 dark:text-neutral-100">Recent Notifications</h3>
            <a href="/notifications" class="text-sm text-brown-600 dark:text-cyan-600 font-medium hover:underline">
                View all notifications →
            </a>
        </div>
        <flux:separator variant="subtle" class="mb-4" />

        @if ($this->recentNotifications->count())
            <!-- Notifications List -->
            <div class="bg-white dark:bg-zinc-900 shadow-sm rounded-2xl divide-y divide-gray-100 dark:divide-gray-700">
            
                <!-- Unread Notification -->
                @foreach ($this->recentNotifications as $notification)

                     <div class="flex items-start gap-4 p-5 
                        {{ !$notification->is_read ? 'bg-brown-50/50 dark:bg-zinc-900' : 'bg-white dark:bg-zinc-800' }} 
                        @if($loop->first) rounded-t-2xl @endif 
                        @if($loop->last) rounded-b-2xl @endif">

                        <div class="flex-shrink-0 w-3 h-3 rounded-full mt-2
                            {{ !$notification->is_read ? 'bg-brown-600 dark:bg-cyan-600' : 'bg-gray-300 dark:bg-gray-500' }}"></div>

                       <div class="flex-1">
                            <h3 class="text-gray-900 dark:text-neutral-100 font-semibold">
                                {{ $notification->title }}
                            </h3>
                            {{-- Summary --}}
                            <p class="text-gray-600 dark:text-neutral-400 text-sm mt-1">
                                @if ($notification->category === 'like')
                                    {{ $notification->creator->username }} liked your post "{{ $notification->details['post_title'] }}"
                                @elseif ($notification->category === 'save')
                                    {{ $notification->creator->username }} saved your post "{{ $notification->details['post_title'] }}"
                                @elseif ($notification->category === 'comment')
                                    {{ $notification->creator->username }} commented on your post "{{ $notification->details['post_title'] }}"
                                @elseif ($notification->category === 'comment-reply')
                                    {{ $notification->creator->username }} replied to a comment on your post "{{ $notification->details['post_title'] }}"
                                @elseif ($notification->category === 'follow')
                                    {{ $notification->creator->username }} followed you
                                @endif
                            </p>
                            <div class="mt-2 flex items-center gap-4 text-xs text-gray-400">
                                <span>{{ $notification->created_at->diffForHumans() }}</span>
                                <a href="/notifications/{{ $notification->id }}" class="text-brown-600 dark:text-cyan-600 hover:underline">View Details</a>
                                @if(!$notification->is_read)
                                    <span wire:click="markAsRead({{ $notification->id }})" class="text-brown-600 dark:text-cyan-600 hover:underline cursor-pointer">Mark as read</span>
                                @else
                                    <span class="text-gray-400 italic">Read</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                

                <!-- Read Notification -->
                {{-- <div class="flex items-start gap-4 p-5 hover:bg-gray-50 transition opacity-75">
                    <div class="flex-shrink-0 w-3 h-3 bg-gray-300 rounded-full mt-2"></div>
                    <div class="flex-1">
                        <h3 class="text-gray-900 font-semibold">
                            System Maintenance Scheduled
                        </h3>
                        <p class="text-gray-600 text-sm mt-1">
                            Our servers will undergo maintenance tonight from 11:00 PM to 1:00 AM.
                        </p>
                        <div class="mt-2 flex items-center gap-4 text-xs text-gray-400">
                            <span>2 hours ago</span>
                            <a href="#" class="text-blue-600 hover:underline">View Details</a>
                        </div>
                    </div>
                </div> --}}
            </div>
        @else
            <div class="flex flex-col items-center justify-center text-center py-12 border border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
                <div class="flex flex-col items-center space-y-3">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-neutral-200">
                        You have no notifications yet
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm">
                        Stay tuned for updates and alerts here.
                    </p>
                </div>
            </div>
        @endif
        
    </div>

</div>


