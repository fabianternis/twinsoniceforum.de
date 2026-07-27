<?php
use App\Icon;
?>
@extends('layout.app')

@section('content')
<div class="container" style="padding-top: 3rem; padding-bottom: 5rem;">
    
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 2rem; color: #fff; font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
                {!! Icon::render('shield') !!} Admin Control Panel
            </h1>
            <p style="color: var(--text-muted);">Verwalte Kategorien, Spatie Audit-Logs & SoftDeletes Papierkorb</p>
        </div>
        <div style="display: flex; gap: 0.75rem;">
            <a href="/admin/audit" class="btn btn-secondary" style="font-size: 0.85rem;">
                {!! Icon::render('shield') !!} Audit-Logs (Spatie Activity)
            </a>
            <a href="/admin/trash" class="btn btn-secondary" style="font-size: 0.85rem;">
                {!! Icon::render('pin') !!} Papierkorb (SoftDeletes)
            </a>
        </div>
    </div>

    <!-- Overview Stats -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        <div class="glass-card" style="padding: 1.5rem; text-align: center;">
            <div style="font-size: 2.5rem; font-weight: 800; color: var(--primary-cyan);">{{ $usersCount }}</div>
            <div style="color: var(--text-muted); font-size: 0.9rem;">Registrierte Mitglieder</div>
        </div>
        <div class="glass-card" style="padding: 1.5rem; text-align: center;">
            <div style="font-size: 2.5rem; font-weight: 800; color: #38bdf8;">{{ $topicsCount }}</div>
            <div style="color: var(--text-muted); font-size: 0.9rem;">Forum Themen</div>
        </div>
        <div class="glass-card" style="padding: 1.5rem; text-align: center;">
            <div style="font-size: 2.5rem; font-weight: 800; color: #94a3b8;">{{ $postsCount }}</div>
            <div style="color: var(--text-muted); font-size: 0.9rem;">Beitraege & Antworten</div>
        </div>
    </div>

    <!-- Admin Actions Grid -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        
        <!-- Add Category Form -->
        <div class="glass-card" style="padding: 2rem;">
            <h2 style="font-size: 1.3rem; color: #fff; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                {!! Icon::render('plus') !!} Neue Kategorie erstellen (Spatie Sluggable)
            </h2>
            
            <form action="/admin/category/create" method="POST">
                @csrf
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; color: var(--text-main); font-size: 0.85rem; margin-bottom: 0.3rem;">Kategorie Name</label>
                    <input type="text" name="name" class="input-field" placeholder="z.B. Fan Art & Design" required>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; color: var(--text-main); font-size: 0.85rem; margin-bottom: 0.3rem;">Icon Key</label>
                    <select name="icon" class="input-field">
                        <option value="skate">Skate (Ice)</option>
                        <option value="music">Music</option>
                        <option value="video">Video</option>
                        <option value="fashion">Fashion</option>
                        <option value="calendar">Calendar</option>
                        <option value="chat">Chat</option>
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; color: var(--text-main); font-size: 0.85rem; margin-bottom: 0.3rem;">Badge Farbe</label>
                    <select name="badge_color" class="input-field">
                        <option value="cyan">Cyan</option>
                        <option value="blue">Blue</option>
                        <option value="amber">Amber</option>
                    </select>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; color: var(--text-main); font-size: 0.85rem; margin-bottom: 0.3rem;">Beschreibung</label>
                    <textarea name="description" class="input-field" rows="2" placeholder="Kurze Beschreibung..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Kategorie anlegen</button>
            </form>
        </div>

        <!-- Registered Users List -->
        <div class="glass-card" style="padding: 2rem;">
            <h2 style="font-size: 1.3rem; color: #fff; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                {!! Icon::render('user') !!} Neueste Mitglieder (Spatie Roles)
            </h2>
            
            <div style="display: flex; flex-direction: column; gap: 0.75rem; max-height: 400px; overflow-y: auto;">
                @foreach ($recentUsers as $u)
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.6rem 0.8rem; background: #0f172a; border-radius: var(--radius-sm);">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <img src="{{ $u->avatar_url }}" class="avatar-sm" style="width: 32px; height: 32px;">
                            <div>
                                <div style="font-weight: 700; color: #fff; font-size: 0.9rem;">{{ $u->username }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-dim);">{{ $u->email }}</div>
                            </div>
                        </div>
                        <span class="badge badge-{{ $u->role === 'admin' ? 'amber' : 'cyan' }}" style="font-size: 0.7rem;">
                            {{ $u->role }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection
