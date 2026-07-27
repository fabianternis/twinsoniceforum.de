<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Category;
use App\Models\Post;
use App\Models\Reaction;
use App\Models\Poll;
use App\Models\PollVote;
use App\Models\PollOption;
use Illuminate\Support\Facades\Auth;

class TopicController extends Controller
{
    public function show($id)
    {
        $topic = Topic::with(['user', 'category', 'posts.user', 'poll.options'])->findOrFail($id);
        $topic->increment('views');

        $reactions = Reaction::where('item_type', 'topic')
            ->where('item_id', $id)
            ->selectRaw('reaction_type, count(*) as count')
            ->groupBy('reaction_type')
            ->pluck('count', 'reaction_type')
            ->toArray();

        return view('topic', compact('topic', 'reactions'));
    }

    public function createForm()
    {
        $categories = Category::all();
        return view('topic_create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $topic = Topic::create([
            'category_id' => $validated['category_id'],
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        activity()
            ->performedOn($topic)
            ->causedBy(Auth::user())
            ->log('Thema wurde von ' . Auth::user()->username . ' verfasst');

        return redirect()->route('topics.show', $topic->id);
    }

    public function reply(Request $request, $id)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $topic = Topic::findOrFail($id);

        Post::create([
            'topic_id' => $topic->id,
            'user_id' => Auth::id(),
            'content' => $validated['content'],
        ]);

        $topic->increment('replies_count');
        $topic->touch();

        activity()
            ->performedOn($topic)
            ->causedBy(Auth::user())
            ->log('Antwort im Thema #' . $topic->id . ' verfasst');

        return redirect()->route('topics.show', $topic->id);
    }

    public function react(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Anmeldung erforderlich'], 401);
        }

        $type = $request->input('type', 'heart');
        $userId = Auth::id();

        $existing = Reaction::where('item_type', 'topic')
            ->where('item_id', $id)
            ->where('user_id', $userId)
            ->where('reaction_type', $type)
            ->first();

        if ($existing) {
            $existing->delete();
            $added = false;
        } else {
            Reaction::create([
                'item_type' => 'topic',
                'item_id' => $id,
                'user_id' => $userId,
                'reaction_type' => $type,
            ]);
            $added = true;
        }

        return response()->json(['success' => true, 'added' => $added]);
    }

    public function votePoll(Request $request, $id)
    {
        $validated = $request->validate([
            'poll_id' => 'required|exists:polls,id',
            'option_id' => 'required|exists:poll_options,id',
        ]);

        $userId = Auth::id();
        $pollId = $validated['poll_id'];
        $optionId = $validated['option_id'];

        if (PollVote::where('poll_id', $pollId)->where('user_id', $userId)->exists()) {
            return back()->with('error', 'Du hast bereits abgestimmt!');
        }

        PollVote::create([
            'poll_id' => $pollId,
            'option_id' => $optionId,
            'user_id' => $userId,
        ]);

        PollOption::where('id', $optionId)->increment('votes');

        return back();
    }

    public function destroy($id)
    {
        $topic = Topic::findOrFail($id);
        
        // Soft delete topic using SoftDeletes trait
        $topic->delete();

        activity()
            ->performedOn($topic)
            ->causedBy(Auth::user())
            ->useLogName('topic_softdelete')
            ->log('Thema wurde in den Papierkorb verschoben (SoftDelete)');

        return redirect()->route('home')->with('status', 'Thema wurde in den Papierkorb verschoben.');
    }
}
