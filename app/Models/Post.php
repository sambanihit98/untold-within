<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Notification;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    protected $guarded = [];

    //--------------------------------------------------------------------
    //--------------------------------------------------------------------

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //--------------------------------------------------------------------
    //--------------------------------------------------------------------
    // Many-to-Many with Topic
    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'post_topics')->withTimestamps();
    }

    // Many-to-Many with Tag
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tags')->withTimestamps();
    }
    //--------------------------------------------------------------------
    //--------------------------------------------------------------------

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function saves()
    {
        return $this->hasMany(Save::class);
    }

    public function views()
    {
        return $this->hasMany(View::class);
    }

    //Helper Methods
    public function isLikedBy($user)
    {
        return $user ? $this->likes()->where('user_id', $user->id)->exists() : false;
    }

    public function isSavedBy($user)
    {
        return $user ? $this->saves()->where('user_id', $user->id)->exists() : false;
    }

    public function isViewedBy($user)
    {
        return $user ? $this->views()->where('user_id', $user->id)->exists() : false;
    }

    public function isCommentedBy($user)
    {
        return $user ? $this->comments()->where('user_id', $user->id)->exists() : false;
    }

    protected static function booted()
    {
        static::deleting(function ($post) {
            // Delete all notifications that reference this post ID in their details
            Notification::cursor()->each(function ($notif) use ($post) {
                $details = is_array($notif->details) ? $notif->details : json_decode($notif->details, true);

                if (!empty($details) && (string) data_get($details, 'post_id') === (string) $post->id) {
                    $notif->delete();
                }
            });
        });
    }
}
