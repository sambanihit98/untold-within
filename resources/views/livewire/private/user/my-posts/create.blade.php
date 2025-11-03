<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

use App\Models\Topic;

new #[Layout('components.layouts.app', ['title' => 'My Posts | Add New'])] class extends Component {

    public string $title = '';
    public string $slug = '';
    public string $content = '';
    public bool $is_public = true;

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
    //TOPICS Logic
    public function updatedSearchTopics()
    {
        $this->showDropdownTopics = true;

        $this->resultsTopics = Topic::query()
            ->where('name', 'like', '%' . $this->searchTopics . '%')
            ->whereNotIn('id', $this->selectedTopics)
            ->take(10)
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
            ->take(10)
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
    //----------------------------------------------------------------
    //----------------------------------------------------------------
    //Publish Logic

    public function updatedTitle($value)
    {
        $this->slug = Str::slug(Str::lower($value));
    }

    public function publish(){
        
        $validated = $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|unique:posts,slug',
            'content' => 'required|string',
        ]);

        // Create the post first
        $post = \App\Models\Post::create([
            'user_id' => auth('web')->id(),
            'title'   => $validated['title'],
            'slug'   => $validated['slug'],
            'content' => $validated['content'],
            'is_public' => $this->is_public,
        ]);

        // Attach topics
        if (!empty($this->selectedTopics)) {
            $post->topics()->sync($this->selectedTopics);
        }

        // Attach tags
        if (!empty($this->selectedTags)) {
            $post->tags()->sync($this->selectedTags);
        }

        // Optional: reset form
        $this->reset(['title', 'content', 'selectedTopics', 'selectedTags']);

        // Set toast session for redirect
        session()->flash('toast', [
            'message' => 'Post added successfully!',
            'type' => 'success',
        ]);

        // Redirect after success
        return redirect()->route('my-posts');

    }

    //----------------------------------------------------------------
    //----------------------------------------------------------------

}; ?>

<div class="flex flex-col gap-6 p-6">

    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('my-posts') }}">My Post</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Add New Post</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Heading Title --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Add New Post') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Create and share your latest thoughts or stories with the community.') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    {{-- Post Form --}}
    <div class="space-y-6 max-w-3xl">
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
            <flux:textarea id="content" wire:model.defer="content" rows="6" placeholder="Write your thoughts here..." />
            <flux:error name="content" />
        </flux:field>


        {{-- //----------------------------------------------------------- --}}
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

        <!-- Submit Button -->
        <div class="pt-4">
            <flux:button variant="primary" icon="check" wire:click="publish">
                Publish Post
            </flux:button>
        </div>
    </div>
    
</div>


