<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::all();
        $query = Post::query();

        if ($request->has('category') && $request->category != '') {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $posts = $query->latest()->paginate(10);

        return view('posts.index', compact('posts', 'categories'));
    }

    public function show(string $id): View
    {
        $post = Post::with(['author', 'categories', 'comments'])->find($id);

        if (!$post) {
            abort(404, 'Post not found');
        }

        return view('posts.show', compact('post'));
    }

    public function create(): View
    {
        $categories = Category::all();

        return view('posts.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
        ]);

        $post = Post::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'author_id' => auth()->id(),
        ]);

        $post->categories()->sync($request->input('categories'));

        return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }

    public function edit(Post $post): RedirectResponse|View
    {
        if ($post->author_id !== auth()->id()) {
            return redirect()->route('posts.index')->with('error', 'You are not authorized to edit this post.');
        }

        $categories = Category::all();

        return view('posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        if ($post->author_id !== auth()->id()) {
            return redirect()->route('posts.index')->with('error', 'You are not authorized to update this post.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
        ]);

        $post->update($request->only(['title', 'content']));

        $post->categories()->sync($request->input('categories', []));

        return redirect()->route('posts.show', $post)->with('success', 'Post updated successfully.');
    }


    public function destroy(Post $post): RedirectResponse
    {
        if ($post->author_id !== auth()->id()) {
            return redirect()->route('posts.index')->with('error', 'You are not authorized to delete this post.');
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }

}
