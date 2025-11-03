<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

//test repo
//----------------------------------------------------------------
//----------------------------------------------------------------
//Public Pages
Volt::route('/', 'public/home')->name('home');

Volt::route('/about', 'public/about')->name('about');

Volt::route('/posts', 'public/posts/index')->name('posts');
Volt::route('/posts/{id}', 'public/posts/show');

Volt::route('/authors', 'public/authors/index')->name('authors');
Volt::route('/authors/{id}', 'public/authors/show');

Volt::route('/topics', 'public/topics/index')->name('topics');
Volt::route('/topics/{id}', 'public/topics/show');
Volt::route('/topics/{id}/{tag}', 'public/topics/tag');

//----------------------------------------------------------------
//----------------------------------------------------------------
//ADMIN

Route::middleware(['auth:admin', 'verified'])->group(function () {
    Volt::route('admin/dashboard', 'private.admin.dashboard')->name('admin.dashboard');
    Volt::route('admin/topics', 'private.admin.topics.index')->name('admin.topics');
    Volt::route('admin/topics/{id}', 'private.admin.topics.tags')->name('admin.topics.tags');

    Volt::route('admin/news-and-alerts', 'private.admin.news-and-alerts.index')->name('admin.news-and-alerts');

    //SETTINGS
    Route::redirect('admin/settings', 'private.admin.settings/profile');
    Volt::route('admin/settings/profile', 'private.admin.settings.profile')->name('admin.settings.profile');
    Volt::route('admin/settings/password', 'private.admin.settings.password')->name('admin.settings.password');
    Volt::route('admin/settings/appearance', 'private.admin.settings.appearance')->name('admin.settings.appearance');
});

//----------------------------------------------------------------
//----------------------------------------------------------------
//USER
Route::middleware(['auth:web'])->group(function () {
    Volt::route('dashboard', 'private.user.dashboard')->name('dashboard');

    Volt::route('my-posts', 'private.user.my-posts.index')->name('my-posts');
    Volt::route('my-posts/create', 'private.user.my-posts.create')->name('my-posts.create');
    Volt::route('my-posts/{id}', 'private.user.my-posts.show')->name('my-posts.show');

    Volt::route('saved-posts', 'private.user.saved-posts')->name('saved-posts');
    Volt::route('liked-posts', 'private.user.liked-posts')->name('liked-posts');

    Volt::route('followers', 'private.user.followers')->name('followers');
    Volt::route('following', 'private.user.following')->name('following');

    Volt::route('notifications', 'private.user.notifications.index')->name('notifications');
    Volt::route('notifications/{id}', 'private.user.notifications.show')->name('notifications.show');

    Volt::route('news-and-alerts', 'private.user.news-and-alerts.index')->name('news-and-alerts');

    //SETTINGS
    Route::redirect('settings', 'private.user.settings/profile');
    Volt::route('settings/profile', 'private.user.settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'private.user.settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'private.user.settings.appearance')->name('settings.appearance');
});

Route::middleware(['auth'])->group(function () {});

require __DIR__ . '/auth.php';
