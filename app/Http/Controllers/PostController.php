<?php

namespace App\Http\Controllers;

use App\Repositories\PostRepositoryInterface;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function index()
    {
        return response()->json($this->postRepository->all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'user_id' => 'required|exists:users,id',
        ]);

        $post = $this->postRepository->create($validated);
        return response()->json($post, 201);
    }

    public function show($id)
    {
        return response()->json($this->postRepository->find($id));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $post = $this->postRepository->update($id, $validated);
        return response()->json($post);
    }

    public function destroy($id)
    {
        $this->postRepository->delete($id);
        return response()->json(null, 204);
    }
}
