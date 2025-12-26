<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Task;
use App\Policies\CategoryPolicy;
use App\Policies\CommentPolicy;
use App\Policies\TaskPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Category::class => CategoryPolicy::class,
        Comment::class => CommentPolicy::class,
        Task::class => TaskPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}