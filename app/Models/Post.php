<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model
{
    use HasFactory, Sluggable;
    // use HasFactory, SoftDeletes, Sluggable;

    protected $appends = ['published_at', 'liked', 'bookmarked'];

    // protected $withCount = ['comments'];
    protected $guarded = ['id'];

    protected function getPublishedAtAttribute()
    {
        return $this->created_at->diffForHumans();
    }
    protected function getLikedAttribute()
    {
        return auth()->check() ? $this->likes->contains(fn($value, $key) => $value->user_id == auth()->id()) : false;
    }
    protected function getBookmarkedAttribute()
    {
        return auth()->check() ? $this->bookmarks->contains(fn($value) => $value->user_id == auth()->id()) : false;
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function bookmarks()
    {
        // return $this->hasMany(Bookmark::class);
        return $this->belongsToMany(User::class, 'bookmarks', 'post_id', 'user_id');
    }
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
