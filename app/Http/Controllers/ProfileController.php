<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Topic;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show($username)
    {
        $profileUser = User::where('username', $username)->firstOrFail();
        $userTopics = Topic::with('category')->where('user_id', $profileUser->id)->latest()->take(10)->get();
        $postCount = Post::where('user_id', $profileUser->id)->count();

        return view('profile', compact('profileUser', 'userTopics', 'postCount'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'avatar_url' => 'nullable|url',
            'rank_badge' => 'nullable|string|max:50',
            'bio' => 'nullable|string',
        ]);

        $user->update([
            'avatar_url' => $validated['avatar_url'] ?? $user->avatar_url,
            'rank_badge' => $validated['rank_badge'] ?? $user->rank_badge,
            'bio' => $validated['bio'] ?? $user->bio,
        ]);

        activity()
            ->performedOn($user)
            ->causedBy($user)
            ->useLogName('profile_update')
            ->log('Profil wurde aktualisiert');

        return redirect()->route('user.profile', $user->username);
    }
}
