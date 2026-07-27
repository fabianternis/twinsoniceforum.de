<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shout;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $shouts = Shout::with('user')->latest()->take(20)->get()->reverse()->values();
        return response()->json($shouts);
    }

    public function post(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Bitte melde dich an, um in den Fan-Chat zu schreiben.'], 401);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $shout = Shout::create([
            'user_id' => Auth::id(),
            'message' => $validated['message'],
        ]);

        $shout->load('user');

        return response()->json($shout);
    }
}
