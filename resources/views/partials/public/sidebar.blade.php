{{-- ----------------------------------------------------------------------------------------------- --}}
{{-- ----------------------------------------------------------------------------------------------- --}}
<!-- Sidebar -->
<aside class="lg:col-span-1 space-y-6">

    <!-- Topics -->
    @livewire('public/sidebar/topics')

    <!-- Tags -->
    {{-- @livewire('public/sidebar/tags') --}}

    <!-- Top Authors -->
    @livewire('public/sidebar/authors')

    <!-- Quote of the Day -->
    {{-- @livewire('public/sidebar/quote') --}}

    @auth
         <!-- Mini Poll / Question -->
        {{-- @livewire('public/sidebar/question') --}}

        <!-- Feedback -->
        @livewire('public/sidebar/feedback')
    @endauth
   
    <!-- Newsletter Signup -->
    @livewire('public/sidebar/newsletter')

</aside>