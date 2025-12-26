<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Task $task): bool
    {
        return $user->id === $task->created_by 
            || $task->assignedUsers->contains($user->id) 
            || $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Task $task): bool
    {
        return $user->id === $task->created_by || $user->isAdmin();
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->id === $task->created_by || $user->isAdmin();
    }
}