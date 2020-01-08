<?php

namespace App\Http\Controllers;

use App\Blog;
use App\Http\Requests\BlogRequest;

class BlogsController extends Controller
{
    public function index()
    {
        $blogs = Blog::all();
        return view('blogs.index', compact('blogs'));
    }

    public function create()
    {
        return view('blogs.create');
    }

    public function store(BlogRequest $request)
    {
        Blog::create($request->all());
        return redirect('/');
    }

    // public function show(Blog $blog)
    // {
    //     return view('blogs.show', compact('blog'));
    // }

    public function edit(Blog $blog)
    {
        return view('blogs.edit', compact('blog'));
    }

    public function update(BlogRequest $request, Blog $blog)
    {
        $blog->update($request->all());
        return redirect()->route('blogs.index', $blog);
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();
        return redirect('/blogs');
    }
}
