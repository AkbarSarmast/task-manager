<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::with('user')->latest()->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
        ]);

        $validated['user_id'] = auth()->id();

        $category = Category::create($validated);

        return response()->json($category->load('user'), 201);
    }

    public function show(Category $category)
    {
        return $category->load('user', 'tasks');
    }

    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $validated = $request->validate([
            'name' => 'string|max:255',
            'color' => 'nullable|string|max:7',
        ]);

        $category->update($validated);

        return $category->load('user');
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        
        $category->delete();
        return response()->json(null, 204);
    }
}