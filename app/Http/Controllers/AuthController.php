<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function loginForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('login');
    }

    public function processLogin(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Support login by username or email
        $field = filter_var($credentials['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$field => $credentials['username'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            activity()
                ->causedBy(Auth::user())
                ->useLogName('user_login')
                ->log('Benutzer hat sich angemeldet');

            return redirect()->intended(route('home'));
        }

        return back()->with('error', 'Ungueltiger Benutzername oder Passwort.');
    }

    public function registerForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('register');
    }

    public function processRegister(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|min:3|max:50|unique:users',
            'email' => 'required|email|max:100|unique:users',
            'password' => 'required|string|min:6',
            'bio' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['username'],
            'username' => $validated['username'],
            'email' => strtolower($validated['email']),
            'password' => Hash::make($validated['password']),
            'avatar_url' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=250&q=80',
            'bio' => $validated['bio'] ?? '',
            'rank_badge' => 'Ice Fan',
            'role' => 'member',
        ]);

        // Assign Spatie Member Role
        $memberRole = Role::firstOrCreate(['name' => 'member']);
        $user->assignRole($memberRole);

        Auth::login($user);

        activity()
            ->causedBy($user)
            ->useLogName('user_registration')
            ->log('Neuer Benutzer registriert');

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            activity()
                ->causedBy(Auth::user())
                ->useLogName('user_logout')
                ->log('Benutzer hat sich abgemeldet');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
