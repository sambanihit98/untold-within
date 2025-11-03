<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birthdate' => 'date',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->username)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function post()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Users that this user follows
    public function followings()
    {
        return $this->belongsToMany(
            User::class,
            'user_follows',
            'follower_user_id', // this user’s ID is stored here
            'following_user_id' // and they are following these users
        )->withTimestamps();
    }

    // Users that follow this user
    public function followers()
    {
        return $this->belongsToMany(
            User::class,
            'user_follows',
            'following_user_id', // this user’s ID is stored here
            'follower_user_id' // and these users follow me
        )->withTimestamps();
    }

    //Helper Methods
    // public function isFollowedBy($user) {}

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function createdNotifications()
    {
        return $this->hasMany(Notification::class, 'created_by');
    }
}
