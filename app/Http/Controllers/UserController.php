<?php

namespace App\Http\Controllers;

use App\Models\comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $users = User::where('name', 'like', '%' . $query . '%')->get();
        $posts = Post::where('title', 'like', '%' . $query . '%')->get();
        $comments = Comment::where('content', 'like', '%' . $query . '%')->get();

        return response()->json([
            'users' => $users,
            'posts' => $posts,
            'comments' => $comments,
        ]);
    }

    public function show(User $user)
    {
        $rating = $user->calculateRating();
        return response()->json([
            'user' => $user,
            'rating' => $rating,
        ]);
    }
}
