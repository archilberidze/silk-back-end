<?php

namespace Database\Seeders;

use App\Models\comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->count(10)->create()->each(function ($user) {
            $posts = Post::factory()->count(5)->make();
            $user->posts()->saveMany($posts);

            $posts->each(function ($post) {
                $comments = Comment::factory()->count(3)->make();
                $post->comments()->saveMany($comments);
            });
        });

        Post::all()->each(function ($post) {
            $likes = Like::factory()->count(5)->make(['type' => 'like']);
            $dislikes = Like::factory()->count(2)->make(['type' => 'dislike']);
            $post->likes()->saveMany($likes);
            $post->likes()->saveMany($dislikes);
        });

        Comment::all()->each(function ($comment) {
            $likes = Like::factory()->count(3)->make(['type' => 'like']);
            $dislikes = Like::factory()->count(1)->make(['type' => 'dislike']);
            $comment->likes()->saveMany($likes);
            $comment->likes()->saveMany($dislikes);
        });
    }
}
