<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Topic;
use App\Models\Post;
use App\Models\Category;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $usersCount = User::count();
        $topicsCount = Topic::count();
        $postsCount = Post::count();

        $recentUsers = User::latest()->take(10)->get();
        $categories = Category::orderBy('display_order', 'asc')->get();

        return view('admin', compact('usersCount', 'topicsCount', 'postsCount', 'recentUsers', 'categories'));
    }

    public function auditLogs()
    {
        // Spatie Activity Log viewer
        $activities = Activity::with('causer')->latest()->paginate(25);
        return view('admin_audit', compact('activities'));
    }

    public function trash()
    {
        // SoftDeletes items inspection
        $trashedTopics = Topic::onlyTrashed()->with(['user', 'category'])->paginate(20);
        return view('admin_trash', compact('trashedTopics'));
    }

    public function restoreTopic($id)
    {
        $topic = Topic::onlyTrashed()->findOrFail($id);
        $topic->restore();

        activity()
            ->performedOn($topic)
            ->causedBy(Auth::user())
            ->useLogName('topic_restore')
            ->log('Thema wurde aus dem Papierkorb wiederhergestellt');

        return redirect()->route('admin.trash')->with('status', 'Thema wurde wiederhergestellt.');
    }

    public function forceDeleteTopic($id)
    {
        $topic = Topic::onlyTrashed()->findOrFail($id);
        $topic->forceDelete();

        activity()
            ->causedBy(Auth::user())
            ->useLogName('topic_force_delete')
            ->log('Thema wurde endgueltig geloescht');

        return redirect()->route('admin.trash')->with('status', 'Thema wurde endgueltig geloescht.');
    }

    public function createCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'icon' => 'required|string|max:50',
            'badge_color' => 'required|string|max:30',
            'description' => 'nullable|string',
        ]);

        $cat = Category::create([
            'name' => $validated['name'],
            'icon' => $validated['icon'],
            'badge_color' => $validated['badge_color'],
            'description' => $validated['description'] ?? '',
        ]);

        activity()
            ->performedOn($cat)
            ->causedBy(Auth::user())
            ->useLogName('category_create')
            ->log('Kategorie "' . $cat->name . '" wurde von Admin angelegt');

        return redirect()->route('admin');
    }
}
