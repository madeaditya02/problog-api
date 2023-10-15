<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function bookmarks()
    {
        // return $this->hasMany(Bookmark::class);
        // return $this->hasManyThrough(Bookmark::class, Post::class);
        return $this->belongsToMany(Post::class, 'bookmarks', 'user_id', 'post_id')->orderByPivot('created_at', 'desc');
    }
    
    public function followers()
    {
        return $this->morphMany(Follower::class, 'followerable');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function followed() {
        return auth()->check() ? $this->followers->contains(fn($user) => $user->user_id == auth()->id()) : false;
    }
}
