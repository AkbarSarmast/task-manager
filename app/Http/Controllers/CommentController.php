<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Task $task)
    {
        return $task->comments()->with('user')->latest()->get();
    }

    public function store(Request $request, Task $task)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['task_id'] = $task->id;

        $comment = Comment::create($validated);

        return response()->json($comment->load('user'), 201);
    }

    public function show(Task $task, Comment $comment)
    {
        return $comment->load('user');
    }

    public function update(Request $request, Task $task, Comment $comment)
    {
        $this->authorize('update', $comment);

        $validated = $request->validate([
            'content' => 'string',
        ]);

        $comment->update($validated);

        return $comment->load('user');
    }

    public function destroy(Task $task, Comment $comment)
    {
        $this->authorize('delete', $comment);
        
        $comment->delete();
        return response()->json(null, 204);
    }
}