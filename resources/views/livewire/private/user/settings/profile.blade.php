<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new #[Layout('components.layouts.private.app')] class extends Component {

    public $user;
    public $showEditModal = false;

     // Editable fields
    public $username, $firstname, $lastname;
    public $middlename = '';
    public $bio, $tagline, $birthdate, $gender, $phonenumber, $email;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->user = Auth::user();

        // Fill fields for editing
        $this->fill($this->user->only([
            'username', 'firstname', 'middlename', 'lastname',
            'bio', 'tagline', 'birthdate', 'gender', 'phonenumber', 'email'
        ]));

        // Ensure the date is in the correct format for the input
        if ($this->user->birthdate) {
            $this->birthdate = $this->user->birthdate->format('Y-m-d');
        }
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'username' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id)
            ],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', username: $user->username);
    }

    // Send an email verification notification to the current user.
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();
        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));
            return;
        }
        $user->sendEmailVerificationNotification();
        $this->dispatch('showToast', 'A new verification link has been sent to your email address', 'info');
    }

    public function openEditModal()
    {
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
    }

    public function updateProfile()
    {
        $validated = $this->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($this->user->id)],
            'firstname'      => ['required', 'string', 'max:255'],
            'middlename'     => ['nullable', 'string', 'max:255'],
            'lastname'       => ['required', 'string', 'max:255'],
            'bio'            => ['nullable', 'string'],
            'tagline'        => ['nullable', 'string'],
            'birthdate'      => ['required', 'date'],
            'gender'         => ['required', Rule::in(['Male','Female','Other'])],
            'phonenumber'    => ['required', 'string', 'max:255', Rule::unique('users', 'phonenumber')->ignore($this->user->id)],
            'email'          => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user->id),
            ],
        ]);

         // Check if the email was changed
        if ($this->user->email !== $validated['email']) {
            $validated['email_verified_at'] = null;
        }

        $this->user->update($validated);
        auth()->user()->refresh();

        $this->showEditModal = false;

        $this->dispatch('showToast', 'Profile updated successfully!', 'success');
    }
}; ?>

<section class="w-full">

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

    @include('partials.private.user.settings-heading')

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your user account info')">

        <div class="my-6 w-full space-y-4">
            <!-- Profile Info Display -->
            <div class="grid md:grid-cols-6 gap-4">

                <x-private.user.info label="Anonymous Name" :value="$user->username" class="md:col-span-6"/>
                <x-private.user.info label="First Name" :value="$user->firstname" class="md:col-span-2"/>
                <x-private.user.info label="Middle Name" :value="$user->middlename" class="md:col-span-2"/>
                <x-private.user.info label="Last Name" :value="$user->lastname" class="md:col-span-2"/>
                <x-private.user.info label="Bio" :value="$user->bio" class="md:col-span-6"/>
                <x-private.user.info label="Tagline" :value="$user->tagline" class="md:col-span-6"/>
                <x-private.user.info label="Birthdate" :value="$user->birthdate ? $user->birthdate->format('F d, Y') : '—'" class="md:col-span-3"/>
                <x-private.user.info label="Gender" :value="$user->gender" class="md:col-span-3"/>
                <x-private.user.info label="Phone Number" :value="$user->phonenumber" class="md:col-span-2"/>
                <x-private.user.info label="Email" :value="$user->email" class="md:col-span-2"/>
                <x-private.user.info label="Joined On" :value="$user->created_at->format('F d, Y')" class="md:col-span-2"/>
            </div>

            <div class="pt-6">
                <flux:button variant="primary" wire:click="openEditModal">
                    {{ __('Edit Profile') }}
                </flux:button>
            </div>
        </div>

        <!-- Edit Modal -->
        <flux:modal wire:model="showEditModal" class="md:w-[60rem] max-w-none">
            <x-slot name="title">Edit Profile</x-slot>

            <form wire:submit.prevent="updateProfile" class="space-y-4">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-3">
                        <flux:input wire:model="username" label="Anonymous Name" />
                    </div>

                    <flux:input wire:model="firstname" label="First Name" />
                    <flux:input wire:model="middlename" label="Middle Name" />
                    <flux:input wire:model="lastname" label="Last Name" />

                    <div class="md:col-span-3 space-y-4">
                        <flux:textarea wire:model="bio" label="Bio" rows="10"/>
                        <flux:textarea wire:model="tagline" label="Tagline" rows="5"/>
                    </div>
                    
                    <flux:input wire:model="birthdate" label="Birthdate" type="date" />
                    <flux:select wire:model="gender" label="Gender">
                        <option value="">Select Gender</option>
                        <option>Male</option>
                        <option>Female</option>
                        <option>Other</option>
                    </flux:select>
                    <flux:input wire:model="phonenumber" label="Phone Number" />
                    
                    <div class="md:col-span-3 space-y-4">
                        <flux:input wire:model="email" label="Email" type="email" />
                    </div>
                    
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <flux:button type="button" variant="ghost" wire:click="closeEditModal">
                        Cancel
                    </flux:button>
                    <flux:button type="submit" variant="primary">
                        Save Changes
                    </flux:button>
                </div>
            </form>
        </flux:modal>

        {{-- <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <flux:input wire:model="username" :label="__('Anonymous Name')" type="text" required autofocus autocomplete="username" />

            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Your email address is unverified.') }}

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to send the verification email.') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </flux:text>
                        @endif 
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form> --}}

        <livewire:private.user.settings.delete-user-form />
    </x-settings.layout>
</section>
