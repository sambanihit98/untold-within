<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

use App\Models\Post;
use App\Models\View;
use App\Models\Comment;
use App\Models\Notification;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;

new #[Layout('components.layouts.public.app', ['title' => 'Posts | Untold Within'])] class extends Component {
    
    public $post;
    public $otherPosts;
    
    //------------------------------------------
    // for Comments variables
    public $commentText = '';
    public $replyText = [];
    public $delete_comment_modal = false;
    public $commentToDelete = null;
    //------------------------------------------

    public bool $liked  = false;
    public bool $saved  = false;
    public bool $viewed = false;
    public bool $commented = false;

    public function mount($id){
        $this->post = Post::where('id', $id)
                        ->where('is_public', true)
                        ->with(['user', 'topics', 'tags', 'comments.user', 'comments.replies.user'])
                        ->firstOrFail();

        $this->otherPosts = Post::where('user_id', $this->post->user_id)
            ->where('is_public', true)
            ->where('id', '!=', $this->post->id)
            ->with(['user', 'topics', 'tags'])
            ->latest()
            ->take(2)
            ->get();

        $this->liked     = $this->post->isLikedBy(auth('web')->user());
        $this->saved     = $this->post->isSavedBy(auth('web')->user());
        $this->viewed    = $this->post->isViewedBy(auth('web')->user());
        $this->commented = $this->post->isCommentedBy(auth('web')->user());

        $this->recordView($this->post->id);

        // reflects the latest view count immediately
        $this->post->refresh();
        $this->viewed = true;
    }

    public function otherPosts(): mixed {
        return ['otherPosts' => $this->otherPosts];
    }

    //--------------------------------------------------
    //--------------------------------------------------
    //Comment Logic
    public function comment()
    {
        $user = auth('web')->user();

        $this->validate([
            'commentText' => 'required|string|min:2|max:500',
        ]);

        $comment = Comment::create([
            'post_id' => $this->post->id,
            'user_id' => auth('web')->id(),
            'content' => $this->commentText,
        ]);

        if($this->post->user->id !== $user->id){
            //Notification
            Notification::create([
                'user_id' => $this->post->user->id,
                'title' => 'New Comment on Your Post',
                'summary' => 'Someone commented on your post',
                'details'   => json_encode([
                    'post_id'    => $this->post->id,
                    'comment_id' => $comment->id,
                ]),
                'category' => 'comment',
                'link' => '/posts/'. $this->post->id,
                'is_read' => false,
                'created_by' => $user->id,
            ]);
        }

        $this->commentText = '';

        // Checks if the user has a comment on the post / if yes, the comment icon's appearance changes
        $this->commented = $this->post->isCommentedBy(auth('web')->user());

        $this->post->refresh(); // reload updated comments
        
    }

    public function reply($commentId)
    {

        $user = auth('web')->user();

        $this->validate([
            "replyText.$commentId" => 'required|string|min:1|max:500',
        ]);

        $commentReply = Comment::create([
            'post_id' => $this->post->id,
            'user_id' => auth('web')->id(),
            'parent_id' => $commentId,
            'content' => $this->replyText[$commentId],
        ]);

        if($this->post->user->id !== $user->id){
            //Notification
            Notification::create([
                'user_id' => $this->post->user->id,
                'title' => 'New Reply to a Comment on Your Post',
                'summary' => 'Someone replied to a comment on your post',
                'details'   => json_encode([
                    'post_id'    => $this->post->id,
                    'comment_id' => $commentReply->id,
                ]),
                'category' => 'comment-reply',
                'link' => '/posts/'. $this->post->id,
                'is_read' => false,
                'created_by' => $user->id,
            ]);
        }

        $this->replyText[$commentId] = '';

        // Checks if the user has a comment on the post / if yes, the comment icon's appearance changes
        $this->commented = $this->post->isCommentedBy(auth('web')->user());

        $this->post->refresh();
    }

    #[Computed]
    public function comments()
    {
        return Comment::where('post_id', $this->post->id)
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->latest()
            ->get();
    }

    public function confirmDelete($commentId)
    {
        $this->commentToDelete = $commentId;
        $this->delete_comment_modal = true;
    }

    public function deleteCommentConfirmed()
    {
        if (!$this->commentToDelete) return;

        $comment = Comment::findOrFail($this->commentToDelete);

        // Ownership check
        if ($comment->user_id !== auth('web')->id()) return;

        DB::transaction(function () use ($comment) {
            // Delete replies first
            $comment->replies->each->delete();

            // Delete the parent comment
            $comment->delete();

            // Recompute the true comment count for the post
            $postId = $comment->post_id;
            $total = Comment::where('post_id', $postId)->count();

            $post = Post::find($postId);
            if ($post) {
                $post->timestamps = false;
                $post->update(['comments_count' => $total]);
                $post->timestamps = true;
            }
        });

        // Delete the related notification
        Notification::cursor()->each(function ($notif) use ($comment) {
            $details = is_array($notif->details)
                ? $notif->details
                : json_decode($notif->details, true);

            if (!empty($details) && (string) data_get($details, 'comment_id') === (string) $comment->id) {
                $notif->delete();
            }
        });

        $this->delete_comment_modal = false;
        $this->commentToDelete = null;

        // Refresh post and comment state
        $this->commented = $this->post->isCommentedBy(auth('web')->user());
        $this->post->refresh();
    }

    //--------------------------------------------------
    //--------------------------------------------------
    //View Logic
    protected function recordView($postId)
    {
        $user = auth('web')->user();
        $sessionId = session()->getId();
        $ip = request()->ip();

        // Check if a view already exists for this user/session/ip
        $viewExists = View::where('post_id', $postId)
            ->when($user, fn($q) => $q->where('user_id', $user->id)) 
            ->when(!$user, fn($q) => $q->where(function ($query) use ($sessionId, $ip) {
                $query->where('session_id', $sessionId)
                    ->orWhere('ip_address', $ip); 
            }))
            ->exists();

        // If not yet viewed, create new record and increment counter
        if (!$viewExists) {
            View::create([
                'post_id' => $postId,
                'user_id' => $user?->id,
                'session_id' => $sessionId,
                'ip_address' => $ip,
            ]);

            $post = Post::find($postId);

            if ($post) {
                $post->timestamps = false; // don’t update updated_at
                $post->increment('views_count');
                $post->timestamps = true;
            }
        }
    }

    //--------------------------------------------------
    //--------------------------------------------------
    //Like Logic
    public function toggleLike()
    {
        $user = auth()->user();

        if (!$user) return;

        $existingLike = $this->post->likes()->where('user_id', $user->id)->first();

        if ($existingLike) {
            // Unlike
            $existingLike->delete();
            $this->liked = false;

            // Delete the related notification
            Notification::where('user_id', $this->post->user->id)
                ->where('created_by', $user->id)
                ->where('category', 'like')
                ->delete();

            $this->post->timestamps = false;
            $this->post->decrement('likes_count');
            $this->post->timestamps = true;
        } else {
            // Like (only if doesn’t exist)
            $this->post->likes()->create(['user_id' => $user->id]);
            $this->liked = true;

            if($this->post->user->id !== $user->id){
                //Notification
                Notification::create([
                    'user_id' => $this->post->user->id,
                    'title' => 'New Like on Your Post',
                    'summary' => 'Someone liked your post',
                    'details'   => json_encode([
                        'post_id'    => $this->post->id,
                    ]),
                    'category' => 'like',
                    'link' => '/posts/'. $this->post->id,
                    'is_read' => false,
                    'created_by' => $user->id,
                ]);
            }
            
            $this->post->timestamps = false;
            $this->post->increment('likes_count');
            $this->post->timestamps = true;
        }
    }

    //--------------------------------------------------
    //--------------------------------------------------
    //Save Logic
    public function toggleSave()
    {
        $user = auth()->user();

        if (!$user) return;

        $existingSave = $this->post->saves()->where('user_id', $user->id)->first();

        if ($existingSave) {
            // Unsave
            $existingSave->delete();
            $this->saved = false;

            // Delete the related notification
            Notification::where('user_id', $this->post->user->id)
                ->where('created_by', $user->id)
                ->where('category', 'save')
                ->delete();

            $this->post->timestamps = false;
            $this->post->decrement('saves_count');
            $this->post->timestamps = true;
        } else {
            // Save (only if doesn’t exist)
            $this->post->saves()->create(['user_id' => $user->id]);
            $this->saved = true;

            if($this->post->user->id !== $user->id){
                //Notification
                Notification::create([
                    'user_id' => $this->post->user->id,
                    'title' => 'New Save on Your Post',
                    'summary' => 'Someone saved your post',
                    'details'   => json_encode([
                        'post_id'    => $this->post->id,
                    ]),
                    'category' => 'save',
                    'link' => '/posts/'. $this->post->id,
                    'is_read' => false,
                    'created_by' => $user->id,
                ]);
            }

            $this->post->timestamps = false;
            $this->post->increment('saves_count');
            $this->post->timestamps = true;
        }
    }
    
}; ?>

<div>
    <section id="all-posts" class="mb-20 mt-40">
        <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Posts Area -->
            <div class="lg:col-span-3 space-y-8">

            <!-- Breadcrumbs -->
            <nav class="text-sm mb-6 border-b border-gray-200 pb-5">
                <ol class="flex items-center space-x-2">
                <li>
                    <a href="/" class="text-brown-600 dark:text-neutral-100 font-medium hover:underline">Home</a>
                </li>
                <li class="text-gray-400">›</li>
                <li>
                    <a href="/posts" class="text-brown-600 dark:text-neutral-100 font-medium hover:underline">Posts</a>
                </li>
                <li class="text-gray-400">›</li>
                <li class="py-1 rounded text-brown-600 dark:text-neutral-100 font-semibold">
                    {{ $post->title }}
                </li>
                </ol>
            </nav>
            
            <!-- Heading -->
            <x-public.section-heading>{{ $post->title }}</x-public.section-heading>

            <!-- Meta Info -->
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <div class="flex items-center gap-2">

                    <a href="/authors/{{ $post->user->id }}" class="flex items-center gap-x-2 hover:scale-105 transition-all duration-200 ease-in-out">
                        <div class="w-10 h-10 rounded-full bg-brown-50 dark:bg-cyan-50 text-brown-900 dark:text-cyan-900 flex items-center justify-center font-semibold ">
                            {{ strtoupper(substr($post->user->username, 0, 1)) }}
                        </div>
                        <span class="font-medium text-brown-900 dark:text-neutral-100 transition-all duration-200 ease-in-out hover:font-bold hover:underline">{{ $post->user->username }}</span>
                    </a>
                    

                </div>
                <span>•</span>
                <span>
                    Posted {{ $post->created_at->diffForHumans() }}
                    @if ($post->updated_at->gt($post->created_at))
                        • Updated {{ $post->updated_at->diffForHumans() }}
                    @endif
                </span>
            </div>

            <!-- Content -->
            <div class="whitespace-pre-line text-lg leading-relaxed text-brown-900 dark:text-neutral-100 space-y-4 ">
                <p>{{ $this->post->content }}</p>
            </div>

            <div>
                @if ($post->topics->count())
                    <!-- Topics -->
                    <div class="mt-3 flex flex-wrap items-center gap-2">
                         <span class="text-sm font-semibold text-brown-600 dark:text-cyan-600">Topics:</span>
                        @foreach ($post->topics as $topic)
                            <x-public.post-tag href="/topics/{{ $topic->id }}">
                                {{ $topic->name }}
                            </x-public.post-tag>
                        @endforeach
                    </div>
                @endif
            
                @if ($post->tags->count())
                    <!-- Tags -->
                    <div class="mt-3 flex flex-wrap items-center gap-2">
                         <span class="text-sm font-semibold text-brown-600 dark:text-cyan-600">Tags:</span>
                        @foreach ($post->tags as $tag)
                            <x-public.post-tag href="/topics/{{ $tag->topic_id }}/{{ $tag->id }}">
                                {{ $tag->name }}
                            </x-public.post-tag>
                        @endforeach
                    </div>
                @endif
            </div>
                
            <!-- Post Stats -->
            <div class="flex justify-start gap-x-15 items-center border-t border-gray-300 dark:border-neutral-700 pt-4 mt-4 text-sm">

                <!-- Likes -->
                <div class="flex flex-col items-center gap-1">
                    <div class="flex items-center gap-1">
                        <svg
                            wire:click="toggleLike({{ $post->id }})"
                            class="w-6 h-6 cursor-pointer transition-transform duration-300 hover:scale-115
                                {{ $liked ? 'fill-brown-600 stroke-brown-600 dark:fill-cyan-600 dark:stroke-cyan-600' : 'fill-none stroke-gray-500 dark:stroke-neutral-100' }}"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4.318 6.318a5.5 5.5 0 017.778 0L12 6.586l-.096-.096a5.5 5.5 0 117.778 7.778L12 21.364l-7.682-7.682a5.5 5.5 0 010-7.778z"/>
                        </svg>
                        <span class="text-gray-600 dark:text-neutral-100 font-medium">{{ $post->likes_count }}</span>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-neutral-400">Likes</span>
                </div>

                <!-- Saves -->
                <div class="flex flex-col items-center gap-1">
                    <div class="flex items-center gap-1">
                        <svg 
                            wire:click="toggleSave({{ $post->id }})"
                            class="w-6 h-6 cursor-pointer transition-transform duration-300 hover:scale-115
                                {{ $saved ? 'fill-brown-600 stroke-brown-600 dark:fill-cyan-600 dark:stroke-cyan-600' : 'fill-none stroke-gray-500 dark:stroke-neutral-100' }}"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M5 5v14l7-4 7 4V5H5z"/>
                        </svg>
                        <span class="text-gray-600 dark:text-neutral-100 font-medium">{{ $post->saves_count }}</span>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-neutral-400">Saves</span>
                </div>

                <!-- Comments -->
                <div class="flex flex-col items-center gap-1">
                    <div class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg"

                            class="w-6 h-6 cursor-pointer transition-transform duration-300 hover:scale-115
                                {{ $commented ? 'stroke-brown-600 dark:stroke-cyan-600 fill-none' : 'stroke-gray-500 dark:stroke-neutral-100 fill-none' }}
                            "
                            viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                        <span class="text-gray-600 dark:text-neutral-100 font-medium">{{ $post->comments_count }}</span>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-neutral-400">Comments</span>
                </div>

                <!-- Views -->
                <div class="flex flex-col items-center gap-1">
                    <div class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-6 h-6 
                            {{ $viewed ? 'stroke-brown-600 dark:stroke-cyan-600 fill-none' : 'stroke-gray-500 dark:stroke-neutral-100 fill-none' }}
                            "
                            viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7
                                -1.274 4.057-5.065 7-9.542 7
                                -4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span class="text-gray-600 dark:text-neutral-100 font-medium">{{ $post->views_count }}</span>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-neutral-400">Views</span>
                </div>

            </div>

            <!-- Comment Section -->
            <div class="mt-10">
                <h3 class="text-2xl font-semibold text-brown-900 dark:text-neutral-100 mb-6">Comments</h3>

                <!-- Comment Form -->
                <div class="mb-8">
                    @auth('web')
                        <textarea 
                            wire:model.defer="commentText"
                            rows="3"
                            placeholder="Share your thoughts..." 
                            class="w-full p-4 border rounded-lg focus:ring-2 focus:ring-brown-600 dark:focus:ring-cyan-600 focus:outline-none dark:text-neutral-300"
                        ></textarea>
                        <flux:button variant="primary" wire:click="comment">Post Comment</flux:button>
                    @else
                        <div class="p-4 border rounded-lg bg-gray-50 dark:bg-zinc-800 text-center">
                            <p class="text-sm text-gray-600 dark:text-neutral-300">
                                <a href="{{ route('login') }}" class="text-brown-600 dark:text-cyan-300 hover:underline">
                                    Login
                                </a> 
                                or 
                                <a href="{{ route('register') }}" class="text-brown-600 dark:text-cyan-300 hover:underline">
                                    register
                                </a> 
                                to post a comment.
                            </p>
                        </div>
                    @endauth
                </div>

                <!-- Comments List -->
                <div class="space-y-8">
                    @forelse ($this->comments as $comment)
                        <div class="comment" x-data="{ open: false }">
                            <div class="flex items-start gap-3">
                                <!-- Avatar -->
                                <div class="w-10 h-10 rounded-full bg-brown-50 dark:bg-cyan-50 flex items-center justify-center font-semibold text-brown-900 dark:text-cyan-900">
                                    {{ strtoupper(substr($comment->user->username, 0, 1)) }}
                                </div>

                                <!-- Comment Body -->
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-medium text-brown-900 dark:text-neutral-100">
                                                {{ $comment->user->username }}
                                            </p>
                                            <p class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                                        </div>

                                        <!-- Delete button (only for comment owner) -->
                                        @if (auth('web')->id() === $comment->user_id)
                                            <button 
                                                x-on:click="$wire.confirmDelete({{ $comment->id }})"
                                                class="text-sm text-red-600 dark:text-red-400 hover:underline"
                                            >
                                                Delete
                                            </button>
                                        @endif
                                    </div>

                                    <p class="mt-2 text-brown-900 dark:text-neutral-100">{{ $comment->content }}</p>

                                    {{-- ---------------------------------------------------------------------- --}}
                                    @auth('web')
                                        <!-- Reply Button -->
                                        <button 
                                            @click="open = !open" 
                                            class="mt-2 text-sm text-brown-600 dark:text-cyan-300 hover:underline"
                                        >
                                            Reply
                                        </button>

                                        <!-- Reply Form -->
                                        <div x-show="open" x-transition class="mt-4 pl-8">
                                            <textarea 
                                                wire:model.defer="replyText.{{ $comment->id }}"
                                                rows="2"
                                                placeholder="Write your reply..." 
                                                class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-brown-600 dark:focus:ring-cyan-600 focus:outline-none dark:text-neutral-300"
                                            ></textarea>
                                            <flux:button variant="primary" wire:click="reply({{ $comment->id }})">Reply</flux:button>
                                        </div>
                                    @endauth
                                    {{-- ---------------------------------------------------------------------- --}}
                                    
                                    <!-- Replies -->
                                    @if ($comment->replies->count())
                                        <div class="mt-4 pl-8 border-l border-gray-200 dark:border-neutral-700 space-y-4">
                                            @foreach ($comment->replies as $reply)
                                                <div class="flex items-start gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-brown-50 dark:bg-cyan-50 flex items-center justify-center font-semibold text-brown-900 dark:text-cyan-900">
                                                        {{ strtoupper(substr($reply->user->username, 0, 1)) }}
                                                    </div>
                                                    <div class="flex-1">
                                                        <div class="flex items-center justify-between">
                                                            <div>
                                                                <p class="font-medium text-brown-900 dark:text-neutral-100">
                                                                    {{ $reply->user->username }}
                                                                </p>
                                                                <p class="text-sm text-gray-500">{{ $reply->created_at->diffForHumans() }}</p>
                                                            </div>

                                                            <!-- Delete button (only for reply owner) -->
                                                            @if (auth('web')->id() === $reply->user_id)
                                                                <button 
                                                                    x-on:click="$wire.confirmDelete({{ $reply->id }})"
                                                                    class="text-sm text-red-600 dark:text-red-400 hover:underline"
                                                                >
                                                                    Delete
                                                                </button>
                                                            @endif
                                                        </div>
                                                        <p class="mt-1 text-brown-900 dark:text-neutral-100">{{ $reply->content }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">No comments yet. Be the first to share your thoughts!</p>
                    @endforelse
                </div>
            </div>

            @if ($otherPosts->count())
                <!-- More from Author Section -->
                <div class="mt-16 border-t pt-4">
                    <h3 class="text-2xl font-semibold text-brown-900 dark:text-neutral-100 mb-6">More from <a href="/authors/{{ $post->user->id }}" class="font-semibold transition-all hover:underline hover:font-bold duration-200 ease-in-out text-brown-600 dark:text-cyan-300">{{ $post->user->username }}</a></h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                        @foreach ($otherPosts as $otherPost)
                            <x-public.post-card>
                            <x-public.post-heading>{{ $otherPost->title }}</x-public.post-heading>
                            <x-public.post-subheading>
                                <a href="/authors/{{ $otherPost->user->id }}" 
                                   class="font-medium transition-all hover:underline hover:font-bold duration-200 ease-in-out">
                                        {{ $otherPost->user->username }}
                                </a> • {{ $otherPost->created_at->diffForHumans() }}
                            </x-public.post-subheading>
                            <x-public.post-content>{{ $otherPost->content }}</x-public.post-content>

                            @if ($otherPost->topics->count())
                                <!-- Topics -->
                                <div class="mt-3 flex flex-wrap items-center gap-2">
                                    <span class="text-sm font-semibold text-brown-600 dark:text-cyan-600">Topics:</span>
                                    @foreach ($otherPost->topics as $topic)
                                        <x-public.post-tag href="/topics/{{ $topic->id }}">
                                            {{ $topic->name }}
                                        </x-public.post-tag>
                                    @endforeach
                                </div>
                            @endif
                            

                            @if ($otherPost->tags->count())
                                <!-- Tags -->
                                <div class="mt-3 flex flex-wrap items-center gap-2">
                                    <span class="text-sm font-semibold text-brown-600 dark:text-cyan-600">Tags:</span>
                                    @foreach ($otherPost->tags as $tag)
                                        <x-public.post-tag href="/topics/{{ $tag->topic_id }}/{{ $tag->id }}">
                                            {{ $tag->name }}
                                        </x-public.post-tag>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-4">
                                <a href="/posts/{{ $otherPost->id }}" class="text-brown-600 dark:text-cyan-600 hover:underline font-medium block mb-3">Read More →</a>
                                <div class="flex items-center gap-6 text-gray-500 dark:text-neutral-100 text-sm justify-end">

                                <div class="flex items-center gap-1">
                                    <x-public.heroicons :icon="'heart'"/>
                                    <span>{{ $otherPost->likes_count }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <x-public.heroicons :icon="'save'"/>
                                    </svg>
                                    <span>{{ $otherPost->saves_count }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <x-public.heroicons :icon="'comment'"/>
                                    <span>{{ $otherPost->comments_count }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <x-public.heroicons :icon="'views'"/>
                                    <span>{{ $otherPost->views_count }}</span>
                                </div>
                                </div>
                            </div>
                            </x-public.post-card>
                        @endforeach

                    </div>
                </div>
            @endif

            </div>

            {{-- Side Bar --}}
            @include('partials.public.sidebar')

        </div>
        </div>
    </section>

    {{-- Delete Comment Modal --}}
    <flux:modal wire:model="delete_comment_modal" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete Comment?</flux:heading>
                <flux:text class="mt-5">
                    You're about to delete this comment. 
                    This action cannot be reversed.
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button wire:click="deleteCommentConfirmed" variant="danger">Delete</flux:button>
            </div>
        </div>
    </flux:modal>
    
</div>
