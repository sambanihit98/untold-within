<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

use App\Models\Post;
use App\Models\Topic;
use App\Models\Tag;
use App\Models\View;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;

new #[Layout('components.layouts.app', ['title' => 'My Posts'])] class extends Component {

    public bool $delete_modal = false;
    public bool $update_privacy_modal = false;
    public bool $edit_modal = false;

    public $post;

    public $post_id;
    public $title;
    public $slug;
    public $content;
    public bool $is_public;

    public bool $liked = false;
    public bool $saved = false;
    public bool $viewed = false;
    public bool $commented = false;

    //------------------------------------------
    // for Comments variables
    public $commentText = '';
    public $replyText = [];

    public $delete_comment_modal = false;
    public $commentToDelete = null;
    //------------------------------------------

    //----------------------------------------------------------------
    //----------------------------------------------------------------
    //Topics variables
    public $searchTopics = '';
    public $selectedTopics = [];
    public $resultsTopics = [];
    public $showDropdownTopics = false;

    // TAGS variables
    public $searchTags = '';
    public $selectedTags = [];
    public $resultsTags = [];
    public $showDropdownTags = false;
    //----------------------------------------------------------------
    //----------------------------------------------------------------
    
    //Show data
    public function mount($id){
        $this->post = Post::where('id', $id)
                        ->with(['topics', 'tags'])
                        ->withCount(['likes', 'saves', 'comments'])
                        ->firstOrFail();

        $this->liked = $this->post->isLikedBy(auth('web')->user());
        $this->saved = $this->post->isSavedBy(auth('web')->user());
        $this->viewed = $this->post->isViewedBy(auth('web')->user());
        $this->commented = $this->post->isCommentedBy(auth('web')->user());

        $this->recordView($this->post->id);

        // reflects the latest view count immediately
        $this->post->refresh();
        $this->viewed = true;
    }

    public function hydrate()
    {
        $this->post->loadCount(['likes', 'saves', 'comments']);
    }

    //Close Modal
    public function closeModal(){
        $this->edit_modal = false;
        $this->delete_modal = false;
        $this->update_privacy_modal = false;
    }

    //-----------------------------------------------------------
    //Edit logic
    public function editPost($id){

        $post = Post::with(['topics', 'tags'])->findOrFail($id);

        $this->post_id = $post->id;
        $this->title = $post->title;
        $this->slug = $post->slug;
        $this->content = $post->content;
        $this->is_public = $post->is_public;

        // Load related topics and tags
        $this->selectedTopics = $post->topics->pluck('id')->toArray();
        $this->selectedTags = $post->tags->pluck('id')->toArray();

        // Reset dropdown states
        $this->searchTopics = '';
        $this->searchTags = '';
        $this->resultsTopics = [];
        $this->resultsTags = [];

        $this->edit_modal = true;
    }

    //-----------------------------------------------------------
    //Auto-fill slug logic
    public function updatedTitle($value)
    {
        $this->slug = Str::slug(Str::lower($value));
    }

    //----------------------------------------------------------------
    //----------------------------------------------------------------
    //TOPICS Logic
    public function updatedSearchTopics()
    {
        $this->showDropdownTopics = true;

        $this->resultsTopics = Topic::query()
            ->where('name', 'like', '%' . $this->searchTopics . '%')
            ->whereNotIn('id', $this->selectedTopics)
            // ->take(10)
            ->get();
    }

    public function showAllTopics()
    {
        $this->showDropdownTopics = true;
        $this->resultsTopics = Topic::whereNotIn('id', $this->selectedTopics)->take(10)->get();
    }

    public function selectTopic($topicId)
    {
        if (!in_array($topicId, $this->selectedTopics)) {
            $this->selectedTopics[] = $topicId;
        }

        $this->searchTopics = '';
        $this->showDropdownTopics = false;
        $this->resultsTopics = [];
    }

    // Remove a topic from the selected list
    public function removeTopic($topicId)
    {
        // Remove topic
        $this->selectedTopics = array_values(array_filter($this->selectedTopics, fn($id) => $id != $topicId));

        // Remove tags under that topic
        $tagsToRemove = \App\Models\Tag::where('topic_id', $topicId)->pluck('id')->toArray();
        $this->selectedTags = array_values(array_diff($this->selectedTags, $tagsToRemove));
    }

    //----------------------------------------------------------------
    //----------------------------------------------------------------
    //TAGS logic
    public function updatedSearchTags()
    {
        $this->showDropdownTags = true;

        // Only show tags under selected topics
        if (empty($this->selectedTopics)) {
            $this->resultsTags = collect();
            return;
        }

        $this->resultsTags = \App\Models\Tag::query()
            ->whereIn('topic_id', $this->selectedTopics)
            ->whereNotIn('id', $this->selectedTags)
            ->where('name', 'like', '%' . $this->searchTags . '%')
            // ->take(10)
            ->get();

    }

    public function showAllTags()
    {
        if (empty($this->selectedTopics)) {
            $this->resultsTags = collect();
            return;
        }

        $this->showDropdownTags = true;
        $this->resultsTags = \App\Models\Tag::whereIn('topic_id', $this->selectedTopics)
            ->whereNotIn('id', $this->selectedTags)
            ->take(10)
            ->get();
    }

    public function selectTag($tagId)
    {
        if (!in_array($tagId, $this->selectedTags)) {
            $this->selectedTags[] = $tagId;
        }

        $this->searchTags = '';
        $this->showDropdownTags = false;
        $this->resultsTags = [];
    }

    public function removeTag($tagId)
    {
        $this->selectedTags = array_values(array_filter($this->selectedTags, fn($id) => $id != $tagId));
    }

    public function savePostBtn(){
        $validated = $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|unique:posts,slug,' . $this->post_id,
            'content' => 'required|string',
        ]);

         $post = Post::findOrFail($this->post_id);

        // Update post data
        $post->update([
            'title'     => $this->title,
            'slug'      => $this->slug,
            'content'   => $this->content,
            'is_public' => $this->is_public,
        ]);

        // Sync topics and tags
        $post->topics()->sync($this->selectedTopics ?? []);
        $post->tags()->sync($this->selectedTags ?? []);

        $this->post = $post->fresh()->loadCount(['likes', 'saves', 'comments']);

        //Pop up message / Toast
        $this->dispatch('showToast', 'Post updated successfully!', 'info');

        $this->closeModal();

    }

    //-----------------------------------------------------------
    //Update privacy logic
    public function updatePrivacy($id){
        $post = Post::findOrFail($id);
        $this->post_id = $post->id;
        $this->is_public = $post->is_public;
        $this->update_privacy_modal = true;
    }

    public function updatePrivacyBtn(){
        //Find post
        $post = Post::findOrFail($this->post->id);

        $post->update(['is_public' => $this->is_public]);

        $this->post = $post->fresh()->loadCount(['likes', 'saves', 'comments']);

        //Pop up message / Toast
        $this->dispatch('showToast', 'Update privacy successfully!', 'info');

        $this->closeModal();
        $this->reset(['is_public']);
    }

    //-----------------------------------------------------------
    //Delete logic
    public function deletePost($id){
        $post = Post::findOrFail($id);
        $this->post_id = $post->id;
        $this->delete_modal = true;
    }

    public function deletePostBtn(){
        //Find post
        $post = Post::findOrFail($this->post->id);

        //Delete
        $post->delete();

        // Set toast session for redirect
        session()->flash('toast', [
            'message' => 'Post deleted successfully!',
            'type' => 'error',
        ]);

        //Redirect to post page
        return redirect()->route('my-posts');
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

    //-----------------------------------------------------------
    //-----------------------------------------------------------
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

            $this->post->timestamps = false;
            $this->post->decrement('likes_count');
            $this->post->timestamps = true;
        } else {
            // Like (only if doesn’t exist)
            $this->post->likes()->create(['user_id' => $user->id]);
            $this->liked = true;

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

            $this->post->timestamps = false;
            $this->post->decrement('saves_count');
            $this->post->timestamps = true;
        } else {
            // Save (only if doesn’t exist)
            $this->post->saves()->create(['user_id' => $user->id]);
            $this->saved = true;

            $this->post->timestamps = false;
            $this->post->increment('saves_count');
            $this->post->timestamps = true;
        }
    }

    //--------------------------------------------------
    //--------------------------------------------------
    //Comment Logic
    public function comment()
    {
        $this->validate([
            'commentText' => 'required|string|min:2|max:500',
        ]);

        Comment::create([
            'post_id' => $this->post->id,
            'user_id' => auth('web')->id(),
            'content' => $this->commentText,
        ]);

        $this->commentText = '';

        // Checks if the user has a comment on the post / if yes, the comment icon's appearance changes
        $this->commented = $this->post->isCommentedBy(auth('web')->user());

        $this->post->refresh(); // reload updated comments
        
    }

    public function reply($commentId)
    {
        $this->validate([
            "replyText.$commentId" => 'required|string|min:1|max:500',
        ]);

        Comment::create([
            'post_id' => $this->post->id,
            'user_id' => auth('web')->id(),
            'parent_id' => $commentId,
            'content' => $this->replyText[$commentId],
        ]);

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

        $this->delete_comment_modal = false;
        $this->commentToDelete = null;

        // Refresh post and comment state
        $this->commented = $this->post->isCommentedBy(auth('web')->user());
        $this->post->refresh();
    }


}; ?>

<div class="flex flex-col gap-6 p-6">
    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('my-posts') }}">My Post</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>{{ $this->post->title }}</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Heading Title --}}
    <div class="relative w-full">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <!-- Post Info -->
            <div class="space-y-2">
                <flux:heading size="xl" level="1">{{ $this->post->title }}</flux:heading>
                <flux:subheading size="lg" class="mt-1 text-gray-500">
                    Posted {{ $this->post->created_at->diffForHumans() }}
                    @if ($this->post->updated_at->gt($this->post->created_at))
                        • Updated {{ $this->post->updated_at->diffForHumans() }}
                    @endif
                </flux:subheading>

                <!-- Visibility Badge -->
                @if ($this->post->is_public)
                    <flux:badge color="teal" variant="soft" size="sm" class="flex items-center gap-1">
                        <flux:icon name="globe-alt" class="w-4 h-4" />
                        Public
                    </flux:badge>
                @else
                    <flux:badge color="rose" variant="soft" size="sm" class="flex items-center gap-1">
                        <flux:icon name="lock-closed" class="w-4 h-4" />
                        Private
                    </flux:badge>
                @endif
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-3">
                <flux:dropdown align="end">
                    <flux:button size="sm" variant="outline" icon="ellipsis-horizontal" class="p-2" />

                    <flux:menu>
                        <flux:menu.item icon="pencil-square" wire:click="editPost({{ $this->post->id }})">
                            Edit
                        </flux:menu.item>

                        <flux:menu.item icon="lock-closed" wire:click="updatePrivacy({{ $this->post->id }})">
                            Update Privacy
                        </flux:menu.item>

                        <flux:menu.item icon="trash" color="danger" wire:click="deletePost({{ $this->post->id }})">
                            Delete
                        </flux:menu.item>
                    </flux:menu>
                </flux:dropdown>
            </div>

        </div>

        <flux:separator variant="subtle" class="mt-4" />
    </div>

    <!-- Content -->
    <div class="whitespace-pre-line text-lg leading-relaxed text-brown-900 dark:text-neutral-100 space-y-4 ">
        <p>{{ $this->post->content }}</p>
    </div>

    <!-- Topics -->
    <div class="mt-3 flex flex-wrap items-center gap-2">
        @if ($this->post->topics->count())
            <span class="text-sm font-semibold text-brown-900 dark:text-neutral-100">Topics:</span>
            @foreach ($this->post->topics as $topic)
                 <x-public.post-tag>{{ $topic->name }}</x-public.post-tag>
            @endforeach
        @endif
    </div>

    <!-- Tags -->
    <div class="flex flex-wrap items-center gap-2">
        @if ($this->post->tags->count())
            <span class="text-sm font-semibold text-brown-900 dark:text-neutral-100">Tags:</span>
            @foreach ($this->post->tags as $tag)
                 <x-public.post-tag>{{ $tag->name }}</x-public.post-tag>
            @endforeach
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
                        {{ $viewed ? 'stroke-brown-600 dark:stroke-cyan-600 fill-none' : 'stroke-gray-500 dark:stroke-neutral-100 fill-none' }}"
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
            <textarea 
                wire:model.defer="commentText"
                rows="3"
                placeholder="Share your thoughts..." 
                class="w-full p-4 border rounded-lg focus:ring-2 focus:ring-brown-600 dark:focus:ring-cyan-600 focus:outline-none dark:text-neutral-300"
            ></textarea>
            <flux:button variant="primary" wire:click="comment">Post Comment</flux:button>
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

    {{-- ------------------------------------------------------------------------------------ --}}
    {{-- ------------------------------------------------------------------------------------ --}}
    {{-- MODALS --}}

    <!-- Edit Post Modal -->
    <flux:modal wire:model="edit_modal" class="md:w-[60rem] max-w-none">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Update profile</flux:heading>
                <flux:text class="mt-2">Make changes to your personal details.</flux:text>
            </div>
            <!-- Title -->
            <flux:field>
                <flux:label for="title">Title</flux:label>
                <flux:input id="title" wire:model.live="title" placeholder="Enter your post title..." />
                <flux:error name="title" />
            </flux:field>

            <!-- Slug -->
            <flux:field>
                <flux:label for="slug">Slug</flux:label>
                <flux:input id="slug" wire:model="slug"/>
                <flux:error name="slug" />
            </flux:field>

            <!-- Content -->
            <flux:field>
                <flux:label for="content">Content</flux:label>
                <flux:textarea id="content" wire:model.defer="content" rows="20" placeholder="Write your thoughts here..." />
                <flux:error name="content" />
            </flux:field>

            {{-- //----------------------------------------------------------- --}}
            <!-- Search Topics -->
            <div class="space-y-2">
                <flux:label>Topics</flux:label>
                <!-- Selected topics -->
                <div class="flex flex-wrap gap-2">
                    @foreach (App\Models\Topic::whereIn('id', $selectedTopics)->get() as $topic)
                        <div
                            class="flex items-center gap-2 bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 
                                text-xs font-medium px-3 py-1.5 rounded-full shadow-sm transition hover:shadow-md"
                        >
                            <span>{{ $topic->name }}</span>
                            <button
                                type="button"
                                wire:click.stop="removeTopic({{ $topic->id }})"
                                class="text-blue-600 dark:text-blue-400 hover:text-red-500 transition"
                            >
                                ✕
                            </button>
                        </div>
                    @endforeach
                </div>

                <!-- Search input -->
                <div class="relative" wire:mouseenter="$set('showDropdownTopics', true)" wire:mouseleave="$set('showDropdownTopics', false)">
                    <flux:input 
                        wire:model.live="searchTopics" 
                        placeholder="Search topics..."
                        wire:focus="showAllTopics"
                    />

                    @if($showDropdownTopics && !empty($resultsTopics))
                        <ul 
                            class="absolute z-10 w-full bg-white dark:bg-zinc-900 border rounded shadow"
                        >
                            @foreach ($resultsTopics as $resultsTopic)
                                <li
                                    wire:click.prevent="selectTopic({{ $resultsTopic->id }})"
                                    class="px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-zinc-800 cursor-pointer"
                                >
                                    {{ $resultsTopic->name }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- Hidden inputs (for form submission) -->
                @foreach ($selectedTopics as $id)
                    <input type="hidden" name="topics[]" value="{{ $id }}">
                @endforeach
            </div>

            {{-- //----------------------------------------------------------- --}}
            <!-- Search Tags -->
            <div class="space-y-2 mt-6">
                <flux:label>Tags</flux:label>

                <!-- Selected tags -->
                <div class="flex flex-wrap gap-2">
                    @foreach (App\Models\Tag::whereIn('id', $selectedTags)->get() as $tag)
                        <div
                            class="flex items-center gap-2 bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300 
                                text-xs font-medium px-3 py-1.5 rounded-full shadow-sm transition hover:shadow-md"
                        >
                            <span>{{ $tag->name }}</span>
                            <button
                                type="button"
                                wire:click.stop="removeTag({{ $tag->id }})"
                                class="text-purple-600 dark:text-purple-400 hover:text-red-500 transition"
                            >
                                ✕
                            </button>
                        </div>
                    @endforeach
                </div>

                <!-- Search input -->
                <div class="relative" wire:mouseenter="$set('showDropdownTags', true)" wire:mouseleave="$set('showDropdownTags', false)">
                    <flux:input 
                        wire:model.live="searchTags" 
                        placeholder="{{ count($selectedTopics) ? 'Search tags...' : 'Select topics first...' }}"
                        wire:focus="showAllTags"
                        :disabled="! count($selectedTopics)"
                    />

                    @if($showDropdownTags && !empty($resultsTags))
                        <ul class="absolute z-10 w-full bg-white dark:bg-zinc-900 border rounded shadow">
                            @foreach ($resultsTags as $resultTag)
                                <li
                                    wire:click.prevent="selectTag({{ $resultTag->id }})"
                                    class="px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-zinc-800 cursor-pointer"
                                >
                                    {{ $resultTag->name }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- Hidden inputs (for form submission) -->
                @foreach ($selectedTags as $id)
                    <input type="hidden" name="tags[]" value="{{ $id }}">
                @endforeach
            </div>  
            {{-- //----------------------------------------------------------- --}}

            <!-- Visibility (Radio Buttons) -->
            <flux:field>
                <flux:label>Visibility</flux:label>

                <div class="flex gap-6 mt-2 items-center">
                    <label class="inline-flex items-center gap-2">
                        <input type="radio" wire:model="is_public" value="1" class="form-radio" />
                        <span>Public</span>
                    </label>

                    <label class="inline-flex items-center gap-2">
                        <input type="radio" wire:model="is_public" value="0" class="form-radio" />
                        <span>Private</span>
                    </label>
                </div>

                <flux:error name="is_public" />
            </flux:field>

            <div class="flex">
                <flux:spacer />
                <flux:button variant="ghost" wire:click="closeModal">Cancel</flux:button>
                <flux:button  variant="primary" wire:click="savePostBtn">Save changes</flux:button>
            </div>
        </div>
    </flux:modal>

    <!-- Update Privacy Modal -->
    <flux:modal wire:model="update_privacy_modal" class="md:w-96">
        <flux:heading size="lg">Update Post Privacy</flux:heading>

        <div class="mt-4 space-y-4">
            <!-- Visibility (Radio Buttons) -->
            <flux:field>
                <div class="flex gap-6 mt-2 items-center">
                    <label class="inline-flex items-center gap-2">
                        <input type="radio" wire:model="is_public" value="1" class="form-radio" />
                        <span>Public</span>
                    </label>

                    <label class="inline-flex items-center gap-2">
                        <input type="radio" wire:model="is_public" value="0" class="form-radio" />
                        <span>Private</span>
                    </label>
                </div>
                <flux:error name="is_public" />
            </flux:field>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <flux:button variant="ghost" wire:click="closeModal">Cancel</flux:button>
            <flux:button variant="primary" wire:click="updatePrivacyBtn">Update</flux:button>
        </div>
    </flux:modal>

    <!-- Delete Modal -->
    <flux:modal wire:model="delete_modal" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete Post?</flux:heading>
                <flux:text class="mt-5">
                    You're about to delete this post.
                    This action cannot be reversed.
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button wire:click="deletePostBtn" variant="danger">Delete</flux:button>
            </div>
        </div>
    </flux:modal>
    {{-- ------------------------------------------------------------------------------------ --}}
    {{-- ------------------------------------------------------------------------------------ --}}
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

