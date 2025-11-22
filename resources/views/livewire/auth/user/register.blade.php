<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule; 
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth', ['title' => 'Register | Untold Within'])] class extends Component {
    public $username;
    public $firstname;
    public $middlename;
    public $lastname;
    public $bio;
    public $tagline;
    public $birthdate;
    public $gender;
    public $phonenumber;
    public $status;
    public $avatar;
    public $email;
    public $password;
    public $password_confirmation;

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'firstname'      => ['required', 'string', 'max:255'],
            'middlename'     => ['nullable', 'string', 'max:255'],
            'lastname'       => ['required', 'string', 'max:255'],
            'bio'            => ['nullable', 'string'],
            'tagline'        => ['nullable', 'string'],
            'birthdate'      => ['required', 'date'],
            'gender'         => ['required', Rule::in(['Male','Female','Other'])],
            'phonenumber'    => ['required', 'string', 'max:255', 'unique:users,phonenumber'],
            'status'         => ['nullable', Rule::in(['Active','Inactive','Deactivated','Suspended','Banned'])],
            'avatar'         => ['nullable', 'string', 'max:255'],
            'email'          => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'       => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['status'] = $validated['status'] ?? 'Active';

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Anonymous Name -->
        <div class="md:col-span-3">
            <flux:input
                wire:model="username"
                :label="__('Anonymous Name')"
                type="text"
                required
                autocomplete="username"
                :placeholder="__('Anonymous Name')"
                badge="Required"
            />
        </div>

        <!-- First Name -->
        <flux:input
            wire:model="firstname"
            :label="__('First Name')"
            type="text"
            required
            autocomplete="firstname"
            :placeholder="__('First Name')"
            badge="Required"
        />

        <!-- Middle Name -->
        <flux:input
            wire:model="middlename"
            :label="__('Middle Name')"
            type="text"
            autocomplete="middlename"
            :placeholder="__('Middle Name')"
            badge="Optional"
        />

        <!-- Last Name -->
        <flux:input
            wire:model="lastname"
            :label="__('Last Name')"
            type="text"
            required
            autocomplete="lastname"
            :placeholder="__('Last Name')"
            badge="Required"
        />

        <!-- Bio -->
        <div class="md:col-span-3">
            <flux:textarea
                wire:model="bio"
                :label="__('Bio')"
                autocomplete="bio"
                :placeholder="__('Tell us about yourself')"
                badge="Optional"
            />
        </div>

        <!-- Tagline -->
        <div class="md:col-span-3">
            <flux:textarea
                wire:model="tagline"
                :label="__('Tagline')"
                autocomplete="tagline"
                :placeholder="__('Tagline')"
                rows="2"
                badge="Optional"
            />
        </div>

        <!-- Birthdate -->
        <flux:input
            wire:model="birthdate"
            :label="__('Birthdate')"
            type="date"
            required
            placeholder="birthdate"
            badge="Required"
        />

        <!-- Gender -->
        <flux:select wire:model="gender" label="Gender" badge="Required">
            <flux:select.option value="">Choose gender...</flux:select.option>
            <flux:select.option value="Male">Male</flux:select.option>
            <flux:select.option value="Female">Female</flux:select.option>
            <flux:select.option value="Other">Other</flux:select.option>
        </flux:select>



        <!-- Phone Number -->
        <flux:input 
            wire:model="phonenumber" 
            label="Phone Number" 
            type="phone" 
            placeholder="(000) 000-0000" 
            mask="(999) 999-9999" 
            badge="Required"
        />

        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Email address')"
            type="email"
            required
            autocomplete="email"
            placeholder="email@example.com"
            badge="Required"
        />

        <!-- Password -->
        <flux:input
            wire:model="password"
            :label="__('Password')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Password')"
            viewable
            badge="Required"
        />

        <!-- Confirm Password -->
        <flux:input
            wire:model="password_confirmation"
            :label="__('Confirm password')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Confirm password')"
            viewable
            badge="Required"
        />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Create account') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        {{ __('Already have an account?') }}
        <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
    </div>
</div>
