<?php

namespace App\Http\Controllers;

use App\Repositories\CommentRepositoryInterface;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function index()
    {
        return response()->json($this->commentRepository->all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required',
            'post_id' => 'required|exists:posts,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $comment = $this->commentRepository->create($validated);
        return response()->json($comment, 201);
    }

    public function show($id)
    {
        return response()->json($this->commentRepository->find($id));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'content' => 'required',
        ]);

        $comment = $this->commentRepository->update($id, $validated);
        return response()->json($comment);
    }

    public function destroy($id)
    {
        $this->commentRepository->delete($id);
        return response()->json(null, 204);
    }
}
