<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Topic;
use App\Models\Shout;
use App\SubdomainHandler;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('topics')->orderBy('display_order', 'asc')->get();
        $subdomainConfig = SubdomainHandler::getSubdomainConfig();

        $selectedCatId = null;
        if ($subdomainConfig && isset($subdomainConfig['category_slug'])) {
            $catMatch = Category::where('slug', $subdomainConfig['category_slug'])->first();
            if ($catMatch) {
                $selectedCatId = $catMatch->id;
            }
        }

        $query = Topic::with(['user', 'category'])->withCount('posts');

        if ($selectedCatId) {
            $query->where('category_id', $selectedCatId);
        }

        $latestTopics = $query->orderBy('is_pinned', 'desc')->orderBy('updated_at', 'desc')->take(20)->get();
        $recentShouts = Shout::with('user')->latest()->take(15)->get()->reverse();

        return view('home', compact('categories', 'latestTopics', 'recentShouts', 'subdomainConfig'));
    }
}
