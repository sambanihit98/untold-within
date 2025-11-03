<?php

use Livewire\Volt\Component;
use App\Models\Tag;
use Livewire\Attributes\Computed;

new class extends Component {

    #[Computed]
    public function tags(){
        return Tag::with('topic')->get();
    }
    
}; ?>

<div>
    <div class="bg-white dark:bg-zinc-900 shadow-md rounded-lg p-6">
        <h3 class="text-lg font-semibold text-brown-600 dark:text-neutral-100 mb-4">Stories People Relate To</h3>
        <div class="flex flex-wrap gap-2">
            @foreach ($this->tags as $tag)
                <x-public.post-tag href="/topics/{{ $tag->topic->id }}/{{ $tag->id }}">{{ $tag->name }}</x-public.post-tag>
            @endforeach
        </div>
    </div>
</div>
