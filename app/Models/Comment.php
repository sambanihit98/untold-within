<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // A comment can have replies (self relationship)
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    // A reply belongs to a parent comment
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    //-------------------------------------------------------------------------
    //-------------------------------------------------------------------------
    protected static function booted()
    {
        // Automatically update the post's comments_count when a comment is created
        static::created(function ($comment) {
            if ($comment->post) {
                $post = $comment->post;
                $post->timestamps = false; // prevent updated_at from changing
                $post->increment('comments_count');
                $post->timestamps = true;
            }
        });
    }
}
