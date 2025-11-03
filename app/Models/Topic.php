<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    /** @use HasFactory<\Database\Factories\TopicFactory> */
    use HasFactory;

    public $guarded = [];

    // Many-to-Many with Post
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_topics');
    }

    // One-to-Many with Tag
    public function tags()
    {
        return $this->hasMany(Tag::class);
    }
}
