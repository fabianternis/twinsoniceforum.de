<?php
use App\Icon;
?>
@extends('layout.app')

@section('content')
<div class="container" style="padding-top: 3rem; padding-bottom: 6rem; max-width: 520px;">
    
    <div class="glass-card" style="padding: 2.5rem;">
        <div style="text-align: center; margin-bottom: 1.75rem;">
            <div class="brand-icon" style="margin: 0 auto 1rem; width: 48px; height: 48px; font-size: 1.5rem;">{!! Icon::render('skate') !!}</div>
            <h1 style="font-size: 1.8rem; color: #fff; font-weight: 800;">Willkommen zurueck!</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.25rem;">Melde dich im Twins on Ice Forum an</p>
        </div>

        @if (session('error'))
            <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; color: #fca5a5; padding: 0.8rem 1rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; font-size: 0.88rem; text-align: center;">
                {{ session('error') }}
            </div>
        @endif

        @if (session('status'))
            <div style="background: rgba(34, 197, 94, 0.15); border: 1px solid #166534; color: #4ade80; padding: 0.8rem 1rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; font-size: 0.88rem; text-align: center;">
                {{ session('status') }}
            </div>
        @endif

        <!-- OAuth Login Grid -->
        <div style="margin-bottom: 1.75rem;">
            <div style="font-size: 0.8rem; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; margin-bottom: 0.75rem; text-align: center;">
                Schnell-Anmeldung mit OAuth
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem;">
                <a href="/auth/google" class="btn btn-secondary" style="font-size: 0.85rem; padding: 0.5rem; justify-content: center;">
                    {!! Icon::render('google') !!} Google
                </a>
                <a href="/auth/github" class="btn btn-secondary" style="font-size: 0.85rem; padding: 0.5rem; justify-content: center;">
                    {!! Icon::render('github') !!} GitHub
                </a>
                <a href="/auth/discord" class="btn btn-secondary" style="font-size: 0.85rem; padding: 0.5rem; justify-content: center;">
                    {!! Icon::render('discord') !!} Discord
                </a>
                <a href="/auth/twitch" class="btn btn-secondary" style="font-size: 0.85rem; padding: 0.5rem; justify-content: center;">
                    {!! Icon::render('twitch') !!} Twitch
                </a>
                <a href="/auth/twitter-oauth-2" class="btn btn-secondary" style="font-size: 0.85rem; padding: 0.5rem; justify-content: center;">
                    {!! Icon::render('twitter') !!} Twitter / X
                </a>
                <a href="/auth/microsoft" class="btn btn-secondary" style="font-size: 0.85rem; padding: 0.5rem; justify-content: center;">
                    {!! Icon::render('microsoft') !!} Microsoft
                </a>
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.75rem;">
            <div style="flex: 1; height: 1px; background: #334155;"></div>
            <span style="font-size: 0.75rem; color: var(--text-dim); text-transform: uppercase;">oder mit E-Mail</span>
            <div style="flex: 1; height: 1px; background: #334155;"></div>
        </div>

        <form action="/login" method="POST">
            @csrf

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; color: var(--text-main); font-size: 0.9rem; font-weight: 600; margin-bottom: 0.4rem;">
                    Benutzername oder E-Mail
                </label>
                <input type="text" name="username" class="input-field" placeholder="z.B. EmiliaFan" required autofocus>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; color: var(--text-main); font-size: 0.9rem; font-weight: 600; margin-bottom: 0.4rem;">
                    Passwort
                </label>
                <input type="password" name="password" class="input-field" placeholder="••••••••" required>
            </div>

            <!-- Cloudflare Turnstile Captcha Widget -->
            <div style="margin-bottom: 1.5rem; display: flex; justify-content: center;">
                <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key') }}" data-theme="dark"></div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem;">Jetzt Anmelden</button>
        </form>

        <div style="text-align: center; margin-top: 1.75rem; font-size: 0.9rem; color: var(--text-muted);">
            Noch kein Konto? <a href="/register" style="color: var(--primary-cyan); font-weight: 600;">Jetzt kostenlos registrieren</a>
        </div>
    </div>

</div>

<!-- Cloudflare Turnstile Script -->
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
@endsection
