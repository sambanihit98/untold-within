<?php

use Livewire\Volt\Component;

use Livewire\Attributes\Computed;
use App\Models\Notification;
use App\Models\Post;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app', ['title' => 'Notifications'])] class extends Component {
    
    #[Computed]
    public function notifications(){
        $notifications = Notification::where('user_id', auth('web')->user()->id)
            ->with(['creator'])
            ->latest()
            ->paginate(10);

        // Decode details and fetch post title dynamically
        foreach ($notifications as $notification) {
            $details = json_decode($notification->details, true);
            
            if (isset($details['post_id'])) {
                $post = Post::find($details['post_id']);
                $details['post_title'] = $post ? $post->title : '(Post deleted)';
            }

            $notification->details = $details;
        }

        return $notifications;
    }

    public function markAsRead($id){
        $notification = Notification::where('id', $id);
        $notification->update(['is_read' => true]);

        $this->dispatch('refresh');
    }

    public function markAllAsRead(){
        $notification = Notification::where('user_id', auth('web')->user()->id);
        $notification->update(['is_read' => true]);

        //Pop up message / Toast
        $this->dispatch('showToast', 'Marked all notifications as read', 'info');

        $this->dispatch('refresh');
    }

}; ?>

<div class="flex flex-col gap-6 p-6">

    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Notifications</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Heading Title --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Notifications') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('All recent updates, alerts, and important notices in one place.') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    @if($this->notifications->count())

    <div class="flex justify-end">
        <flux:button variant="primary" wire:click="markAllAsRead">Mark all as read</flux:button>
    </div>
    
    <!-- Notifications List -->
    <div class="bg-white dark:bg-zinc-900 shadow-sm rounded-2xl divide-y divide-gray-100 dark:divide-gray-700">
        @foreach ($this->notifications as $notification)

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
    </div>
    <div>
        {{ $this->notifications->links() }}
    </div>

     @else
        <div class="flex flex-col items-center justify-center text-center py-12 border border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
            <div class="flex flex-col items-center space-y-3">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-neutral-200">
                    No notifications found
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm">
                    You’re all caught up! There are no new notifications at the moment.
                </p>
            </div>
        </div>
    @endif

</div>
