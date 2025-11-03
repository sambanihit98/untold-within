<?php

use App\Models\Topic;
use App\Models\Tag;

use Livewire\Volt\Component;
use Livewire\Attributes\Computed;

new class extends Component {

    // Modal state
    public bool $add_modal= false;
    public bool $update_modal= false;
    public bool $delete_modal= false;

    public $tag_id;
    public string $name = '';
    public $topic;

    //----------------------------------------------
    //----------------------------------------------
    //Create new tag
    public function openAddModal()
    {
        $this->resetValidation();
        $this->reset(['name']);
        $this->add_modal = true;
    }

    public function closeModal(){
        $this->add_modal = false;
        $this->update_modal = false;
        $this->delete_modal = false;
    }

    public function save(){
        // Validate
        $this->validate([
            'name' => 'string|unique:tags,name',
        ]);

        // Create
        Tag::create([
            'admin_id' => Auth::guard('admin')->id(),
            'topic_id' => $this->topic->id,
            'name' => $this->name,
        ]);

        $this->dispatch('refresh');
        $this->closeModal();
        $this->reset('name');

    }
    //----------------------------------------------
    //----------------------------------------------
    //Update tag
    public function openUpdateModal($id){

        $tag = Tag::findOrFail($id);
        $this->tag_id = $tag->id;
        $this->name = $tag->name;

        $this->update_modal = true;
    }

    public function update(){
        
        //Validate
        $this->validate(['name' => 'required|string|max:255|unique:tags,name']);

        //Find the tag
        $tag = Tag::findOrFail($this->tag_id);

        //Update
        $tag->update([
            'name' => $this->name
        ]);

        //Refresh the page
        $this->dispatch('refresh');
        $this->closeModal();
        $this->reset(['name']);

    }
    //----------------------------------------------
    //----------------------------------------------
    //Delete tag
    public function openDeleteModal($id){
        $tag = Tag::findOrFail($id);
        $this->tag_id = $tag->id;
        $this->delete_modal = true;
    }

    public function delete(){
        //Find tag
        $tag = Tag::findOrFail($this->tag_id);
        
        //Delete
        $tag->delete();

        //Refresh
        $this->dispatch('refresh');
        $this->closeModal();
    }

    //----------------------------------------------
    //----------------------------------------------
    
    public function mount($id){
        $this->topic = Topic::where('id', $id)->with('tags')->firstOrFail();
    }
    #[Computed]
    public function tags()
    {
        return $this->topic->tags()->latest()->withCount('posts')->paginate(10);
    }
     
}; ?>

<div class="flex flex-col gap-6 p-6">

    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('admin.topics') }}">Topics</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>{{ $this->topic->name }}</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Tags</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Heading Title --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Tags') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">These are the tags under the <b>{{ $this->topic->name }}</b> topic</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    @if($this->tags->count())

        <div class="flex items-center">
            <flux:button variant="primary" icon="plus" wire:click="openAddModal" class="cursor-pointer">
                Add New Tag
            </flux:button>
        </div>

        <div class="overflow-hidden mt-3">
            <div class="flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-700 ">
                                <thead class="bg-gray-50 dark:bg-zinc-900 dark:text-neutral-200 text-gray-800">
                                    <tr>
                                    <th scope="col" class="px-6 py-5 text-start text-xs font-medium uppercase">Tag Name</th>
                                    <th scope="col" class="px-6 py-5 text-start text-xs font-medium uppercase">No. of Posts</th>
                                    <th scope="col" class="px-6 py-5 text-end text-xs font-medium uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 dark:text-neutral-200 text-gray-800">

                                    @foreach ($this->tags as $tag)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-normal break-words text-sm font-medium">{{ $tag->name }}</td>
                                            <td class="px-6 py-4 whitespace-normal break-words text-sm">{{ $tag->posts_count }}</td>
                                            <td class="px-6 py-4 whitespace-normal break-words text-end text-sm font-medium">
                                                <flux:dropdown>
                                                    <flux:button icon:trailing="chevron-down">Actions</flux:button>

                                                    <flux:menu>
                                                        <flux:menu.item wire:click="openUpdateModal({{ $tag->id }})">Update</flux:menu.item>
                                                        <flux:menu.separator />
                                                        <flux:menu.item wire:click="openDeleteModal({{ $tag->id }})" variant="danger">Delete</flux:menu.item>
                                                    </flux:menu>
                                                </flux:dropdown>
                                            </td>
                                        </tr>
                                    @endforeach
                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="flex flex-col items-center justify-center text-center py-12 border border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
            <div class="flex flex-col items-center space-y-3">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-neutral-200">
                    No tags found
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm">
                    This topic doesn’t have any tags yet. Add one to start organizing your content.
                </p>
                <flux:button variant="primary" icon="plus" wire:click="openAddModal" class="mt-4">
                    Add Tag
                </flux:button>
            </div>
        </div>
    @endif

    <div>
        {{ $this->tags->links() }}
    </div>
    
    <!-- Add Tag Modal -->
    <flux:modal wire:model="add_modal" class="w-full">
        <flux:heading size="lg">Add New Tag</flux:heading>

        <div class="mt-4 space-y-4">
            <flux:input label="Tag Name" wire:model.defer="name"/>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <flux:button variant="ghost" wire:click="closeModal">Cancel</flux:button>
            <flux:button variant="primary" wire:click="save">Save</flux:button>
        </div>
    </flux:modal>

    <!-- Update Tag Modal -->
    <flux:modal wire:model="update_modal" class="w-full">
        <flux:heading size="lg">Update Tag</flux:heading>

        <div class="mt-4 space-y-4">
            <flux:input label="Tag Name" wire:model.defer="name"/>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <flux:button variant="ghost" wire:click="closeModal">Cancel</flux:button>
            <flux:button variant="primary" wire:click="update">Update</flux:button>
        </div>
    </flux:modal>

    <!-- Delete Tag Modal -->
    <flux:modal wire:model="delete_modal" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete Tag?</flux:heading>
                <flux:text class="mt-5">
                    You're about to delete this data.
                    This action cannot be reversed.
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button wire:click="delete" variant="danger">Delete</flux:button>
            </div>
        </div>
    </flux:modal>

</div>
