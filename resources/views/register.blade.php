<?php
use App\Icon;
?>
@extends('layout.app')

@section('content')
<div class="container" style="padding-top: 3rem; padding-bottom: 6rem; max-width: 520px;">
    
    <div class="glass-card" style="padding: 2.5rem;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <div class="brand-icon" style="margin: 0 auto 1rem; width: 48px; height: 48px; font-size: 1.5rem;">{!! Icon::render('sparkle') !!}</div>
            <h1 style="font-size: 1.8rem; color: #fff; font-weight: 800;">Mitglied werden</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.25rem;">Werde Teil der Twins on Ice Fan-Community</p>
        </div>

        @if ($errors->any())
            <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; color: #fca5a5; padding: 0.8rem 1rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; font-size: 0.88rem; text-align: center;">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="/register" method="POST">
            @csrf

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; color: var(--text-main); font-size: 0.9rem; font-weight: 600; margin-bottom: 0.4rem;">
                    Wunsch-Benutzername *
                </label>
                <input type="text" name="username" class="input-field" placeholder="z.B. IceQueen_2026" required minlength="3">
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; color: var(--text-main); font-size: 0.9rem; font-weight: 600; margin-bottom: 0.4rem;">
                    E-Mail-Adresse *
                </label>
                <input type="email" name="email" class="input-field" placeholder="deine.email@example.de" required>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; color: var(--text-main); font-size: 0.9rem; font-weight: 600; margin-bottom: 0.4rem;">
                    Passwort *
                </label>
                <input type="password" name="password" class="input-field" placeholder="Mindestens 6 Zeichen" required minlength="6">
            </div>

            <div style="margin-bottom: 1.75rem;">
                <label style="display: block; color: var(--text-main); font-size: 0.9rem; font-weight: 600; margin-bottom: 0.4rem;">
                    Ueber dich / Bio (Optional)
                </label>
                <textarea name="bio" class="input-field" rows="3" placeholder="Z.B. Seit wann schaust du die Vlogs? Laeufst du selbst Schlittschuh?"></textarea>
            </div>

            <button type="submit" class="btn btn-accent" style="width: 100%; padding: 0.75rem;">Konto erstellen & Beitreten</button>
        </form>

        <div style="text-align: center; margin-top: 1.75rem; font-size: 0.9rem; color: var(--text-muted);">
            Bereits registriert? <a href="/login" style="color: var(--primary-cyan); font-weight: 600;">Hier anmelden</a>
        </div>
    </div>

</div>
@endsection
