<?php

use Livewire\Volt\Component;
use App\Models\Notification;

use App\Models\Post;
use App\Models\Comment;

use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app', ['title' => 'Notifications'])] class extends Component {
    
    public $notification;

    public function mount($id){
        $this->notification =  Notification::where('id', $id)->with(['recipient', 'creator'])->firstOrFail();

        // Mark as read if not already
        if (!$this->notification->is_read) {
            $this->notification->update(['is_read' => true]);
        }

        // Decode JSON to associative array
        $details = json_decode($this->notification->details, true);

        // Fetch post title if post_id exists
        if (isset($details['post_id'])) {
            $post = Post::find($details['post_id']);
            $details['post_title'] = $post ? $post->title : 'Post not found';
        }

        if (isset($details['comment_id'])) {
            $comment = Comment::find($details['comment_id']);
            $details['content'] = $comment ? $comment->content : 'Post not found';

             // Get the parent/original comment and its author
            if ($comment && $comment->parent_id) {
                $parentComment = Comment::find($comment->parent_id);
                $details['parent_comment'] = $parentComment ? $parentComment->content : 'Original comment not found';
                $details['parent_comment_author'] = $parentComment && $parentComment->user
                    ? $parentComment->user->username
                    : 'Unknown author';
                $details['parent_comment_author_id'] = $parentComment ? $parentComment->user_id : null;
            } else {
                $details['parent_comment'] = 'No parent comment (this is a top-level comment)';
                $details['parent_comment_author'] = null;
            }
        }

        // Assign back the updated details array
        $this->notification->details = $details;
    }
}; ?>

<div class="flex flex-col gap-6 p-6">
    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('notifications') }}">Notifications</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>{{ $notification->title }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Heading Title --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Notifications') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('All recent updates, alerts, and important notices in one place.') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>


    @if($notification->category === 'like')
        {{-- Like Notification Card --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300">

            <!-- Header -->
            <div class="px-6 pt-5 flex items-center justify-between">
                <div class="flex flex-col">
                    <h2 class="text-lg font-semibold text-gray-800">{{ $notification->title }}</h2>
                    {{-- <p class="text-sm text-gray-500">Notification Category: <span class="text-blue-600 font-medium">{{ ucwords($notification->category) }}</span></p> --}}
                </div>
                <span class="text-sm text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
            </div>

            <!-- Divider -->
            <div class="my-4 mx-6 border-t border-gray-100"></div>

            <!-- Body -->
            <div class="px-6 pb-6 space-y-4">
                <!-- Summary -->
                <div class="space-y-2 text-sm text-gray-700">
                    <p>
                        <strong>Post Title:</strong>
                        <a href="/posts/{{ $notification->details['post_id'] }}" class="hover:underline">{{ $notification->details['post_title'] }}</a>
                    </p>
                    <p>
                        <strong>Liked by:</strong>
                        <a href="/authors/{{ $notification->created_by }}" class="hover:underline">{{ $notification->creator->username }}</a>
                    </p>
                    <p>
                        <strong>Date:</strong>
                        {{ \Carbon\Carbon::parse($notification->created_at)->format('F j, Y | g:i A') }}
                    </p>
                </div>

                <!-- Action Button -->
                <div class="flex justify-end mt-6">
                    <flux:button variant="primary" tag="a" href="{{ $notification->link }}">
                        View Post →
                    </flux:button>
                </div>
            </div>
        </div>
    @elseif ($notification->category === 'save')
        {{-- Save Notification Card --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300">

            <!-- Header -->
            <div class="px-6 pt-5 flex items-center justify-between">
                <div class="flex flex-col">
                    <h2 class="text-lg font-semibold text-gray-800">{{ $notification->title }}</h2>
                    {{-- <p class="text-sm text-gray-500">Notification Category: <span class="text-blue-600 font-medium">{{ ucwords($notification->category) }}</span></p> --}}
                </div>
                <span class="text-sm text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
            </div>

            <!-- Divider -->
            <div class="my-4 mx-6 border-t border-gray-100"></div>

            <!-- Body -->
            <div class="px-6 pb-6 space-y-4">
                <!-- Summary -->
                <div class="space-y-2 text-sm text-gray-700">
                    <p>
                        <strong>Post Title:</strong>
                        <a href="/posts/{{ $notification->details['post_id'] }}" class="hover:underline">{{ $notification->details['post_title'] }}</a>
                    </p>
                    <p>
                        <strong>Saved by:</strong>
                        <a href="/authors/{{ $notification->created_by }}" class="hover:underline">{{ $notification->creator->username }}</a>
                    </p>
                    <p>
                        <strong>Date:</strong>
                        {{ \Carbon\Carbon::parse($notification->created_at)->format('F j, Y | g:i A') }}
                    </p>
                </div>

                <!-- Action Button -->
                <div class="flex justify-end mt-6">
                    <flux:button variant="primary" tag="a" href="{{ $notification->link }}">
                        View Post →
                    </flux:button>
                </div>
            </div>
        </div>

    @elseif ($notification->category === 'comment')
        {{-- Comment Notification Card --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300">

            <!-- Header -->
            <div class="px-6 pt-5 flex items-center justify-between">
                <div class="flex flex-col">
                    <h2 class="text-lg font-semibold text-gray-800">{{ $notification->title }}</h2>
                    {{-- <p class="text-sm text-gray-500">Notification Category: <span class="text-blue-600 font-medium">{{ ucwords($notification->category) }}</span></p> --}}
                </div>
                <span class="text-sm text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
            </div>

            <!-- Divider -->
            <div class="my-4 mx-6 border-t border-gray-100"></div>

            <!-- Body -->
            <div class="px-6 pb-6 space-y-4">
                <!-- Summary -->
                <div class="space-y-2 text-sm text-gray-700">
                    <p>
                        <strong>Post Title:</strong>
                        <a href="/posts/{{ $notification->details['post_id'] }}" class="hover:underline">{{ $notification->details['post_title'] }}</a>
                    </p>
                    <p>
                        <strong>Commented by:</strong>
                        <a href="/authors/{{ $notification->created_by }}" class="hover:underline">{{ $notification->creator->username }}</a>
                    </p>
                    <p>
                        <strong>Comment:</strong>
                        {{ $notification->details['content'] }}
                    </p>
                    <p>
                        <strong>Date:</strong>
                        {{ \Carbon\Carbon::parse($notification->created_at)->format('F j, Y | g:i A') }}
                    </p>
                </div>

                <!-- Action Button -->
                <div class="flex justify-end mt-6">
                    <flux:button variant="primary" tag="a" href="{{ $notification->link }}">
                        View Post →
                    </flux:button>
                </div>
            </div>
        </div>
    @elseif ($notification->category === 'comment-reply')
        {{-- Comment Notification Card --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300">

            <!-- Header -->
            <div class="px-6 pt-5 flex items-center justify-between">
                <div class="flex flex-col">
                    <h2 class="text-lg font-semibold text-gray-800">{{ $notification->title }}</h2>
                    {{-- <p class="text-sm text-gray-500">Notification Category: <span class="text-blue-600 font-medium">{{ ucwords($notification->category) }}</span></p> --}}
                </div>
                <span class="text-sm text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
            </div>

            <!-- Divider -->
            <div class="my-4 mx-6 border-t border-gray-100"></div>

            <!-- Body -->
            <div class="px-6 pb-6 space-y-4">
                <!-- Summary -->
                <div class="space-y-2 text-sm text-gray-700">
                    <p>
                        <strong>Post Title:</strong>
                        <a href="/posts/{{ $notification->details['post_id'] }}" class="hover:underline">{{ $notification->details['post_title'] }}</a>
                    </p>
                    <p class="mt-5">
                        <strong>Comment by:</strong>
                        <a href="/authors/{{ $notification->details['parent_comment_author_id'] }}" class="hover:underline">{{ $notification->details['parent_comment_author'] }}</a>
                    </p>
                    <p>
                        <strong>Comment:</strong>
                        {{ $notification->details['parent_comment'] }}
                    </p>

                    <p class="mt-5">
                        <strong>Reply Comment by:</strong>
                        <a href="/authors/{{ $notification->created_by }}" class="hover:underline">{{ $notification->creator->username }}</a>
                    </p>
                    <p>
                        <strong>Reply Comment:</strong>
                        {{ $notification->details['content'] }}
                    </p>
                    <p class="mt-5">
                        <strong>Date:</strong>
                        {{ \Carbon\Carbon::parse($notification->created_at)->format('F j, Y | g:i A') }}
                    </p>
                </div>

                <!-- Action Button -->
                <div class="flex justify-end mt-6">
                    <flux:button variant="primary" tag="a" href="{{ $notification->link }}">
                        View Post →
                    </flux:button>
                </div>
            </div>
        </div>
    @elseif ($notification->category === 'follow')
        {{-- Comment Notification Card --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300">

            <!-- Header -->
            <div class="px-6 pt-5 flex items-center justify-between">
                <div class="flex flex-col">
                    <h2 class="text-lg font-semibold text-gray-800">{{ $notification->title }}</h2>
                    {{-- <p class="text-sm text-gray-500">Notification Category: <span class="text-blue-600 font-medium">{{ ucwords($notification->category) }}</span></p> --}}
                </div>
                <span class="text-sm text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
            </div>

            <!-- Divider -->
            <div class="my-4 mx-6 border-t border-gray-100"></div>

            <!-- Body -->
            <div class="px-6 pb-6 space-y-4">
                <!-- Summary -->
                <div class="space-y-2 text-sm text-gray-700">
                    <p class="mt-5">
                        <strong>Follower:</strong>
                        <a href="/authors/{{ $notification->creator->id }}" class="hover:underline">{{ $notification->creator->username }}</a>
                    </p>
                    <p class="mt-5">
                        <strong>Date:</strong>
                        {{ \Carbon\Carbon::parse($notification->created_at)->format('F j, Y | g:i A') }}
                    </p>
                </div>

                <!-- Action Button -->
                <div class="flex justify-end mt-6">
                    <flux:button variant="primary" tag="a" href="{{ $notification->link }}">
                        View Post →
                    </flux:button>
                </div>
            </div>
        </div>
    @endif

    {{-- Password Change Notification Card --}}
    {{-- <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300">

        <!-- Header -->
        <div class="px-6 pt-5 flex items-center justify-between">
            <div class="flex flex-col">
                <h2 class="text-lg font-semibold text-gray-800">Password Changed Successfully</h2>
                <p class="text-sm text-gray-500">Notification Category: <span class="text-blue-600 font-medium">Security</span></p>
            </div>
            <span class="text-sm text-gray-400">October 22, 2025 — 9:12 AM</span>
        </div>

        <!-- Divider -->
        <div class="my-4 mx-6 border-t border-gray-100"></div>

        <!-- Body -->
        <div class="px-6 pb-6 space-y-4">
            <!-- Summary -->
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                <p class="text-gray-700 leading-relaxed">
                    Your account password was changed successfully. If you made this change, no further action is required.
                </p>
            </div>

            <!-- Details Section -->
            <div class="grid sm:grid-cols-2 gap-x-8 gap-y-2 text-sm text-gray-700">
                <p><strong>Action:</strong> Password Change</p>
                <p><strong>Changed By:</strong> You</p>
                <p><strong>Date:</strong> October 22, 2025 — 9:12 AM</p>
                <p><strong>IP Address:</strong> 192.168.1.14</p>
            </div>

            <!-- Action Button -->
            <div class="flex justify-end">
                <a href="/account/security"
                class="inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-blue-700 transition">
                    Review Account Security →
                </a>
            </div>
        </div>
    </div> --}}




    {{-- Comment Notification Card --}}
    {{-- <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300">

        <!-- Header -->
        <div class="px-6 pt-5 flex items-center justify-between">
            <div class="flex flex-col">
                <h2 class="text-lg font-semibold text-gray-800">New Comment on Your Post</h2>
                <p class="text-sm text-gray-500">Notification Category: <span class="text-green-600 font-medium">Comment</span></p>
            </div>
            <span class="text-sm text-gray-400">October 22, 2025 — 2:43 PM</span>
        </div>

        <!-- Divider -->
        <div class="my-4 mx-6 border-t border-gray-100"></div>

        <!-- Body -->
        <div class="px-6 pb-6 space-y-4">
            <!-- Summary -->
            <div class="bg-green-50 border border-green-100 rounded-xl p-4">
                <p class="text-gray-700 leading-relaxed">
                    <strong class="text-green-700">Anna Mendoza</strong> left a new comment on your post
                    <strong class="text-gray-800">“Weekend Ride Adventure.”</strong>
                </p>
            </div>

            <!-- Comment Preview -->
            <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 text-gray-700 text-sm italic">
                “This looks awesome! Can’t wait to see more.”
            </div>

            <!-- Details Section -->
            <div class="grid sm:grid-cols-2 gap-x-8 gap-y-2 text-sm text-gray-700">
                <p><strong>Commented By:</strong> Anna Mendoza</p>
                <p><strong>Post Title:</strong> Weekend Ride Adventure</p>
                <p><strong>Date:</strong> October 22, 2025 — 2:43 PM</p>
                <p><strong>Message:</strong> Someone interacted with your post by leaving a new comment.</p>
            </div>

            <!-- Action Button -->
            <div class="flex justify-end">
                <a href="/posts/23"
                class="inline-flex items-center gap-2 bg-green-600 text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-green-700 transition">
                    View Full Thread →
                </a>
            </div>
        </div>
    </div> --}}



    


</div>
