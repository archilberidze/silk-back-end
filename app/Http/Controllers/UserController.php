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

        $userIdsByName = User::where('name', 'like', '%' . $query . '%')->pluck('id')->toArray();


        $postUserIds = Post::where('title', 'like', '%' . $query . '%')->pluck('user_id')->toArray();
        $commentUserIds = Comment::where('content', 'like', '%' . $query . '%')->pluck('user_id')->toArray();


        $userIds = array_unique(array_merge($userIdsByName, $postUserIds, $commentUserIds));

        $users = User::whereIn('id', $userIds)->get();


        return response()->json($users);
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
