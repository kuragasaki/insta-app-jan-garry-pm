<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    const ADMIN_ROLE_ID = 1; // admin user
    const USER_ROLE_ID = 2; // regular user

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

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

    # Use this method to get all the posts of a user
    public function posts(){
        return $this->hasMany(Post::class)->latest();
    }

    # One-To-Many method relationship
    # Use this method to get the followers of the user
    public function followers(){
        return $this->hasMany(Follow::class, 'following_id');
    }

    # Use this method to get all the users that the AUTH user is following
    public function following(){
        return $this->hasMany(Follow::class, 'follower_id');
    }

    # This method is going to return TRUE the user is already being followed
    public function isFollowed(){ //TRUE
        return $this->followers()->where('follower_id', Auth::user()->id)->exists();
    }
}
