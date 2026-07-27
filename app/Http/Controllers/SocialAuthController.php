<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    private array $allowedProviders = [
        'google',
        'github',
        'discord',
        'twitch',
        'twitter-oauth-2',
        'microsoft',
    ];

    public function redirect(string $provider)
    {
        if (!in_array($provider, $this->allowedProviders)) {
            return redirect()->route('login')->with('error', 'Nicht unterstuetzter OAuth Anbieter.');
        }

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider)
    {
        if (!in_array($provider, $this->allowedProviders)) {
            return redirect()->route('login')->with('error', 'Nicht unterstuetzter OAuth Anbieter.');
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Fehler bei der OAuth-Authentifizierung: ' . $e->getMessage());
        }

        $email = $socialUser->getEmail();
        $providerId = $socialUser->getId();

        // Find existing user by provider_id or email
        $user = User::where('provider', $provider)->where('provider_id', $providerId)->first();

        if (!$user && $email) {
            $user = User::where('email', $email)->first();
        }

        if (!$user) {
            // Generate clean username from name or email
            $baseUsername = Str::slug($socialUser->getNickname() ?? $socialUser->getName() ?? explode('@', $email)[0]);
            $username = $baseUsername ?: 'user_' . Str::random(6);

            // Ensure unique username
            $count = 1;
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . '_' . $count++;
            }

            $user = User::create([
                'name' => $socialUser->getName() ?? $username,
                'username' => $username,
                'email' => $email ?? ($providerId . '@' . $provider . '.oauth'),
                'avatar_url' => $socialUser->getAvatar() ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=250&q=80',
                'provider' => $provider,
                'provider_id' => $providerId,
                'provider_token' => $socialUser->token,
                'provider_refresh_token' => $socialUser->refreshToken ?? null,
                'rank_badge' => 'OAuth Fan',
                'role' => 'member',
            ]);

            // Assign Spatie Member Role
            $user->assignRole('member');

            activity()
                ->causedBy($user)
                ->useLogName('oauth_registration')
                ->log('Neuer Benutzer registriert via OAuth (' . ucfirst($provider) . ')');
        } else {
            // Update provider details
            $user->update([
                'provider' => $provider,
                'provider_id' => $providerId,
                'provider_token' => $socialUser->token,
                'provider_refresh_token' => $socialUser->refreshToken ?? $user->provider_refresh_token,
                'avatar_url' => $user->avatar_url ?: ($socialUser->getAvatar() ?? $user->avatar_url),
            ]);

            activity()
                ->causedBy($user)
                ->useLogName('oauth_login')
                ->log('Benutzer angemeldet via OAuth (' . ucfirst($provider) . ')');
        }

        Auth::login($user, true);

        return redirect()->route('home')->with('status', 'Erfolgreich angemeldet mit ' . ucfirst($provider) . '!');
    }
}
