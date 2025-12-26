<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        return Task::with(['creator', 'category', 'assignedUsers', 'comments'])
            ->latest()
            ->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'in:low,medium,high',
            'due_date' => 'nullable|date',
            'category_id' => 'nullable|exists:categories,id',
            'assigned_users' => 'array',
            'assigned_users.*' => 'exists:users,id',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status'] = 'todo';

        $task = Task::create($validated);

        if ($request->has('assigned_users')) {
            $task->assignedUsers()->sync($request->assigned_users);
        }

        return response()->json($task->load(['creator', 'category', 'assignedUsers']), 201);
    }

    public function show(Task $task)
    {
        return $task->load(['creator', 'category', 'assignedUsers', 'comments.user']);
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'priority' => 'in:low,medium,high',
            'due_date' => 'nullable|date',
            'status' => 'in:todo,in_progress,done',
            'category_id' => 'nullable|exists:categories,id',
            'assigned_users' => 'array',
            'assigned_users.*' => 'exists:users,id',
        ]);

        $task->update($validated);

        if ($request->has('assigned_users')) {
            $task->assignedUsers()->sync($request->assigned_users);
        }

        return $task->load(['creator', 'category', 'assignedUsers']);
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();
        return response()->json(null, 204);
    }
}