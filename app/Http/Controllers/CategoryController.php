<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Topic;

class CategoryController extends Controller
{
    public function show($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $topics = Topic::with('user')
            ->where('category_id', $category->id)
            ->orderBy('is_pinned', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        return view('category', compact('category', 'topics'));
    }
}
