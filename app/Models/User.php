<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function calculateRating()
    {
        $postPoints = $this->posts()->withCount([
            'likes as post_likes' => function($query) {
                $query->where('type', 'like');
            },
            'likes as post_dislikes' => function($query) {
                $query->where('type', 'dislike');
            }
        ])->get()->sum(function($post) {
            return ($post->post_likes - $post->post_dislikes);
        });

        $commentPoints = $this->comments()->withCount([
            'likes as comment_likes' => function($query) {
                $query->where('type', 'like');
            },
            'likes as comment_dislikes' => function($query) {
                $query->where('type', 'dislike');
            }
        ])->get()->sum(function($comment) {
            return ($comment->comment_likes - $comment->comment_dislikes);
        });

        return $postPoints + $commentPoints;
    }
}
