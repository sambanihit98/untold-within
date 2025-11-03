<?php

use App\Models\Topic;
use Livewire\Volt\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    
    // Modal state
    public bool $add_modal = false;
    public bool $update_modal = false;
    public bool $delete_modal = false;
    
    // Form data
    public string $name = '';
    public string $slug = '';
    public string $description = '';

    public $topic_id;

    //----------------------------------------------
    //----------------------------------------------
    //Create new topic
    public function openAddModal()
    {
        $this->resetValidation();
        $this->reset(['name', 'slug', 'description']);
        $this->add_modal = true;
    }

    public function closeModal(){
        $this->add_modal = false;
        $this->update_modal = false;
        $this->delete_modal = false;
    }

    public function updatedName($value)
    {
        $this->slug = Str::slug(Str::lower($value));
    }

    public function save(){

        // Validate
        $this->validate([
            'name' => 'required|string|max:255|unique:topics,name',
            'slug' => 'required|unique:topics,slug',
            'description' => 'required|string|'
        ]);

        // Create
        Topic::create([
            'admin_id' =>Auth::guard('admin')->id(),
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
        ]);
            
        $this->dispatch('refresh'); // ->self() //still works without this
        $this->closeModal();
        $this->reset(['name', 'slug', 'description']);
    
    }
    //----------------------------------------------
    //----------------------------------------------
    //Update Topic
    public function openUpdateModal($id)
    {
        $topic = Topic::findOrFail($id);
        $this->topic_id = $topic->id;
        $this->name = $topic->name;
        $this->slug = $topic->slug;
        $this->description = $topic->description;

        $this->update_modal = true;
    }

    public function update(){
        //Validate
        $this->validate([
            'name' => 'required|string|max:255|unique:topics,name',
            'slug' => 'required|unique:topics,slug',
            'description' => 'required|string',
        ]);

        //Find the topic
        $topic = Topic::findOrFail($this->topic_id);

        //Update
        $topic->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
        ]);

        $this->dispatch('refresh');
        $this->closeModal();
        $this->reset(['name', 'slug', 'description']);
    }
    //----------------------------------------------
    //----------------------------------------------
    //Delete Topic
    public function openDeleteModal($id){
        $topic = Topic::findOrFail($id);
        $this->topic_id = $topic->id;
        $this->delete_modal = true;
    }

    public function delete(){

        //Find topic
        $topic = Topic::findOrFail($this->topic_id);

        //Delete
        $topic->delete();

        //Refresh
        $this->dispatch('refresh');
        $this->closeModal();
    }

    //----------------------------------------------
    //----------------------------------------------

    #[Computed]
    public function topics(){
        return Topic::latest()->withCount(['posts', 'tags'])->paginate(10);
    }

}; ?>

<div class="flex flex-col gap-6 p-6">

    <!-- Breadcrumbs -->
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Topics</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Heading Title --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Topics') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Read and explore content that matters to you') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    @if($this->topics->count())

        <div class="flex items-center">
            <flux:button variant="primary" icon="plus" wire:click="openAddModal" class="cursor-pointer">
                Add New Topic
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
                                    <th scope="col" class="px-6 py-5 text-start text-xs font-medium uppercase">Topic Name</th>
                                    <th scope="col" class="px-6 py-5 text-start text-xs font-medium uppercase">Slug</th>
                                    <th scope="col" class="px-6 py-5 text-start text-xs font-medium uppercase">Description</th>
                                    <th scope="col" class="px-6 py-5 text-start text-xs font-medium uppercase">No. of Tags</th>
                                    <th scope="col" class="px-6 py-5 text-start text-xs font-medium uppercase">No. of Posts</th>
                                    <th scope="col" class="px-6 py-5 text-end text-xs font-medium uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 dark:text-neutral-200 text-gray-800">

                                    @foreach ($this->topics as $topic)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-normal break-words text-sm font-medium">{{ $topic->name }}</td>
                                            <td class="px-6 py-4 whitespace-normal break-words text-sm font-medium">{{ $topic->slug }}</td>
                                            <td class="px-6 py-4 whitespace-normal break-words text-sm">{{ $topic->description }}</td>
                                            <td class="px-6 py-4 whitespace-normal break-words text-sm">{{ $topic->tags_count }}</td>
                                            <td class="px-6 py-4 whitespace-normal break-words text-sm">{{ $topic->posts_count }}</td>
                                            <td class="px-6 py-4 whitespace-normal break-words text-end text-sm font-medium">

                                            <flux:dropdown>
                                                <flux:button icon:trailing="chevron-down">Actions</flux:button>

                                                <flux:menu>
                                                    <flux:menu.item href="topics/{{ $topic->id }}">View Tags</flux:menu.item>
                                                    <flux:menu.separator />
                                                    <flux:menu.item wire:click="openUpdateModal({{ $topic->id }})">Update</flux:menu.item>
                                                    <flux:menu.separator />
                                                    <flux:menu.item wire:click="openDeleteModal({{ $topic->id }})" variant="danger">Delete</flux:menu.item>
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

        <div>
            {{ $this->topics->links() }}
        </div>
    @else
        <div class="flex flex-col items-center justify-center text-center py-12 border border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
            <div class="flex flex-col items-center space-y-3">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-neutral-200">
                    No topics found
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm">
                    No topics created — add one to get started.
                </p>
                <flux:button variant="primary" icon="plus" wire:click="openAddModal" class="mt-4">
                    Add New Topic
                </flux:button>
            </div>
        </div>
    @endif

    <!-- Add Topic Modal -->
    <flux:modal wire:model="add_modal" class="w-full">
        <flux:heading size="lg">Add New Topic</flux:heading>

        <div class="mt-4 space-y-4">
            <flux:input label="Topic Name" wire:model.live="name"/>
            <flux:input label="Slug" wire:model="slug"/>
            <flux:textarea label="Description" wire:model="description" rows="5" />
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <flux:button variant="ghost" wire:click="closeModal">Cancel</flux:button>
            <flux:button variant="primary" wire:click="save">Save</flux:button>
        </div>
    </flux:modal>

    <!-- Update Topic Modal -->
    <flux:modal wire:model="update_modal" class="w-full">
        <flux:heading size="lg">Update Topic</flux:heading>

        <div class="mt-4 space-y-4">
            <flux:input label="Topic Name" wire:model.live="name"/>
            <flux:input label="Slug" wire:model="slug"/>
            <flux:textarea label="Description" wire:model="description" rows="5" />
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <flux:button variant="ghost" wire:click="closeModal">Cancel</flux:button>
            <flux:button variant="primary" wire:click="update">Update</flux:button>
        </div>
    </flux:modal>

    <!-- Delete Topic Modal -->
    <flux:modal wire:model="delete_modal" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete Topic?</flux:heading>
                <flux:text class="mt-5">
                    You're about to delete this data.
                    This action cannot be reversed.
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost" wire:click="closeModal">Cancel</flux:button>
                </flux:modal.close>
                <flux:button wire:click="delete" variant="danger">Delete</flux:button>
            </div>
        </div>
    </flux:modal>

</div>
